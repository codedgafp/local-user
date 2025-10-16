<?php

namespace local_user\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/user/lib.php');

use local_user\utils\local_user_database_interface;

/**
 * Deletion of external user accounts that have not been enrolled in a course for more than 30 days
 */
class inactive_enrolment_external_user_deleted extends \core\task\scheduled_task
{
    /**
     * @var local_user_database_interface
     */
    protected $dbi;

    public function __construct()
    {
        $this->dbi = new local_user_database_interface;
    }

    public function get_name(): string
    {
        return get_string('task_inactive_enrolment_external_user_deleted', 'local_user');
    }

    public function execute():  void
    {
        global $DB;

        $externaleuserrole = $DB->get_record('role', ['shortname' => 'utilisateurexterne']);

        $users = $this->dbi->inactive_enrolment_external_users(['cohort'], $externaleuserrole->id, '30 days');

        $noerror = local_user_deleted_users_for_task($users);

        if ($noerror == false) {
            \core\task\manager::scheduled_task_failed($this);
        }
    }
}
