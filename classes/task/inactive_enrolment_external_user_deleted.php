<?php

namespace local_user\task;

use local_user\utils\local_user_database_interface;
use local_user\utils\local_user_service;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/user/lib.php');

/**
 * Deletion of external user accounts that have not been enrolled in a course for more than 30 days
 */
class inactive_enrolment_external_user_deleted extends \core\task\scheduled_task
{
    /**
     * @var local_user_database_interface
     */
    protected $dbi;

    /**
     * @var local_user_service
     */
    protected $luservice;

    public function __construct()
    {
        $this->dbi = new local_user_database_interface;
        $this->luservice = new local_user_service;
    }

    public function get_name(): string
    {
        return get_string('task_inactive_enrolment_external_user_deleted', 'local_user');
    }

    public function execute():  void
    {
        global $DB, $CFG;

        $users = $this->dbi->inactive_enrolment_external_users(['cohort'], $CFG->time_before_delete);

        $userstodelete = array_filter($users, function($user) use ($CFG): bool {
            return $this->luservice->user_can_be_deleted_checked_by_time($user->id, $user->timecreated, strtotime($CFG->time_before_delete));
        });

        $noerror = local_user_deleted_users_for_task($userstodelete);

        if ($noerror == false) {
            \core\task\manager::scheduled_task_failed($this);
        }
    }
}
