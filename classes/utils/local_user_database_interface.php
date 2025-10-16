<?php

namespace local_user\utils;

defined('MOODLE_INTERNAL') || die();

class local_user_database_interface
{
    /**
     * @var \moodle_database
     */
    protected $db;

    public function __construct()
    {
        global $DB;

        $this->db = $DB;
    }

    /**
     * Get all users that have never been enrolled for time
     * 
     * @param array $enrolcohortlist ['cohort', 'manual', ...]
     * @param int $externaluseroleid
     * @param string $timelimite '30 days'
     * @return array
     */
    public function inactive_enrolment_external_users(array $enrolcohortlist, int $externaluseroleid, string $timelimite): array
    {
        global $DB;

        [$inclause, $paramsinclause] = $DB->get_in_or_equal(
            items: $enrolcohortlist,
            type: SQL_PARAMS_NAMED,
            equal: false
        );

        $inactiveenrolextusers = $this->inactive_enrolment_external_users_query($inclause, $paramsinclause, $externaluseroleid, $timelimite);

        return $inactiveenrolextusers;
    }

    /**
     * Query to get all external users that have been inactive for time
     * 
     * @param string $inclause IN or NOT IN ('cohort', 'program', ...)
     * @param array $params Don't forget IN clause params
     * @param int $externaluseroleid
     * @param string $timelimite '30 days'
     * @return array
     */
    public function inactive_enrolment_external_users_query(string $inclause, array $params, int $externaluseroleid, string $timelimite): array
    {
        global $DB;

        $sql = "
            SELECT
                u.id,
                u.username,
                u.auth,
                u.email
            FROM {user} u
            INNER JOIN {role_assignments} ra
                ON u.id = ra.userid
                AND ra.roleid = :roleid
            WHERE NOT EXISTS (
                SELECT 1
                FROM {user_enrolments} ue 
                INNER JOIN {enrol} e 
                    ON ue.enrolid = e.id 
                    AND e.enrol $inclause
                WHERE ue.userid = u.id 
            ) 
            AND TO_TIMESTAMP(u.timecreated) + INTERVAL '$timelimite' < CURRENT_DATE
        ";

        $params['roleid'] = $externaluseroleid;

        return $DB->get_records_sql($sql, $params);
    }
}
