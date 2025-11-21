<?php

namespace local_user\utils;

use moodle_database;

defined('MOODLE_INTERNAL') || die();

class local_user_database_interface
{
    /**
     * @var \moodle_database
     */
    protected $db;

    public function __construct(?moodle_database $db = null)
    {
        global $DB;

        $this->db = $db ?? $DB;
    }

    /**
     * Get all users that have never been enrolled for time
     * 
     * @param array $enrolcohortlist ['cohort', 'manual', ...]
     * @param int $externaluseroleid
     * @param string $timelimite '30 days'
     * @return array
     */
    public function inactive_enrolment_external_users(array $enrolcohortlist, string $timelimite = ''): array
    {
        $externaluserrole = $this->db->get_record('role', ['shortname' => 'utilisateurexterne'], 'id');

        [$inclause, $params] = $this->db->get_in_or_equal(
            items: $enrolcohortlist,
            type: SQL_PARAMS_NAMED,
            equal: false
        );

        $sql = "SELECT
                    u.id,
                    u.username,
                    u.auth,
                    u.email,
                    u.firstname,
                    u.lastname,
                    u.timecreated
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
                AND u.username NOT IN ('admin', 'guest')
            "; // les utilisateurs 'admin' et 'guest' ne doivent pas être supprimé

        if ($timelimite !== '')
            $sql .= "AND TO_TIMESTAMP(u.timecreated) + INTERVAL '$timelimite' < CURRENT_DATE";

        $params['roleid'] = $externaluserrole->id;

        return $this->db->get_records_sql($sql, $params);
    }

    /**
     * Get the last user deleted enrolment
     * 
     * @param mixed $userid
     */
    public function last_user_enrolment_deleted_record(int $userid): mixed
    {
        $sql = "SELECT id, timecreated
                FROM {logstore_standard_log}
                WHERE eventname = :eventname
                AND action = 'deleted'
                AND relateduserid = :userid
                ORDER BY timecreated DESC
                LIMIT 1
            ";

        $params = [
            'eventname' => '\core\event\user_enrolment_deleted',
            'userid' => $userid
        ];

        return $this->db->get_record_sql($sql, $params);
    }
}
