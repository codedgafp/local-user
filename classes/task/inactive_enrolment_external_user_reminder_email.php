<?php

namespace local_user\task;

require_once($CFG->dirroot . '/local/user/lib.php');

use Exception;
use local_user\utils\local_user_database_interface;
use local_user\utils\local_user_service;

defined('MOODLE_INTERNAL') || die();

/**
 * Deletion of external user accounts that have not been enrolled in a course for more than 30 days
 */
class inactive_enrolment_external_user_reminder_email extends \core\task\scheduled_task
{
    /**
     * @var local_user_database_interface
     */
    protected $dbi;

    /**
     * @var local_user_service
     */
    protected $localuserservice;

    public function __construct()
    {
        $this->dbi = new local_user_database_interface;
        $this->localuserservice = new local_user_service;
    }

    public function get_name(): string
    {
        return get_string('task_inactive_enrolment_external_user_reminder_email', 'local_user');
    }

    public function execute(): void
    {
        global $DB, $CFG;

        $inactiveusers = $this->dbi->inactive_enrolment_external_users(['cohort'], $CFG->time_before_delete);

        if (empty($inactiveusers))
            return;

        $supportuser = \core_user::get_support_user();

        $emailsubject = get_string('inactive_enrolment_external_user_reminder_email:subject', 'local_user');

        $timebeforenotification = $CFG->time_before_notification ; // seven days in second

        foreach ($inactiveusers as $user) {
            if ($this->localuserservice->user_last_enrolment_deleted_time_diff($user->id, $timebeforenotification))
                continue;

            $emailmessagetextcontent = [
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
            ];
            $emailmessagetext = get_string('inactive_enrolment_external_user_reminder_email:messagetext', 'local_user', $emailmessagetextcontent);
            $emailmessagehtml = text_to_html($emailmessagetext, true, true);

            try {
                email_to_user($user, $supportuser, $emailsubject, $emailmessagetext, $emailmessagehtml);
            } catch (Exception $e) {
                throw new Exception('Error sending mail : ' . $e->getMessage());
            }
        }
    }
}