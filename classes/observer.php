<?php


/**
 * Plugin observers
 *
 * @package local_user
 */

defined('MOODLE_INTERNAL') || die();

use local_user\repository\local_user_repository;

require_once "$CFG->dirroot/enrol/externallib.php";

class local_user_observer {
    /**
     * @var int 
     */
    private static $userid;

    /**
     * @var int 
     */
    private static $contextid;

    /**
     * @var int 
     */
    private static $courseid;

    /**
     * Update the user role to 'participant' if a user is enrol by the 'program' method and will had
     * the 'formateur' role
     *
     * @param \core\event\role_assigned $event
     * @throws Exception
     */
    public static function update_program_enrol_user_role(\core\event\role_assigned $event): bool {
        global $DB;

        $localuserrepository = new local_user_repository();

        $data = $event->get_data();

        $roleid = $data['objectid'];
        self::$userid = $data['relateduserid'];
        self::$contextid = $data['contextid'];
        self::$courseid = $data['courseid'];

        $role = $DB->get_record('role', ['id' => $roleid], 'id, shortname');
        $enrol = $localuserrepository->get_enrol_name_by_course_and_user_id(self::$courseid, self::$userid);

        if (($role && $role->shortname == 'formateur') && ($enrol && $enrol->enrol == 'program')) {
            $newrole = $DB->get_record('role', ['shortname' => 'participant'], 'id');

            $paramsunasignrole[] = self::build_core_role_external_params($roleid);
            \core_role_external::unassign_roles($paramsunasignrole);

            $paramsassignrole[] = self::build_core_role_external_params($newrole->id);
            \core_role_external::assign_roles($paramsassignrole);

            return true;
        }

        return false;
    }

    private static function build_core_role_external_params(int $roleid): array
    {
        return [
            'roleid'        => $roleid,
            'userid'        => self::$userid,
            'contextid'     => self::$contextid,
            'contextlevel'  => "course",
            'instanceid'    => self::$courseid,
        ];
    }
}
