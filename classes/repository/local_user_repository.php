<?php

/**
 * Class local_user
 *
 * @package local_user
 */

namespace local_user\repository;

defined('MOODLE_INTERNAL') || die();

class local_user_repository
{
    /**
     * @var \moodle_database
     */
    protected $db;

    public function __construct() {
        global $DB;
        $this->db = $DB;
    }

    /**
     *  Get the enrol name by given course and user id
     * 
     * @param int $courseid
     * @param int $userid
     * @return \stdClass|bool|\dml_exception
     */
    public function get_enrol_name_by_course_and_user_id (int $courseid, int $userid): \stdClass|bool|\dml_exception
    {
        $enrolsql = "SELECT e.enrol
                    FROM {enrol} e
                    INNER JOIN {user_enrolments} ue ON e.id = ue.enrolid
                    WHERE e.courseid = :courseid
                    AND ue.userid = :userid
                    ";

        $enrolparams = [
            'courseid'  => $courseid,
            'userid'    => $userid
        ];

        return $this->db->get_record_sql($enrolsql, $enrolparams);
    }
}
