<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local user lib
 *
 * @package    local_user
 * @copyright  2020 Edunao SAS (contact@edunao.com)
 * @author     Rémi Colet <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/format/edadmin/classes/output/interface_renderer.php');

/**
 * Send reminder e-mails to users who have never logged in for a given number of months.
 *
 * @param int $nbmount timestamp
 * @param int $limitnbmount timestamp
 * @param string $subjectmailstringname key string
 * @param string $contentmailstringname key string
 * @param string $recallname recall name
 * @return bool
 * @throws coding_exception
 * @throws dml_exception
 */
function local_user_send_recall_to_never_logged_user(
    $nbmount,
    $limitnbmount,
    $subjectmailstringname,
    $contentmailstringname,
    $recallname
) {
    global $CFG;

    // Time data.
    $nbday = $nbmount * 30;
    $nbdaytosecond = 86400 * $nbday;
    $limitnbday = $limitnbmount * 30;
    $limitnbdaytosecond = 86400 * $limitnbday;
    $now = time();
    $lasttime = $now - $nbdaytosecond;
    $limitlasttime = $now - $limitnbdaytosecond;

    // Url data.
    $siteurl = $CFG->wwwroot;
    $forgotpasswordurl = $CFG->wwwroot . '/login/forgot_password.php';
    $faqurl = $CFG->wwwroot . '/local/staticpage/view.php?page=faq';

    // Get users.
    $dbi = \local_mentor_core\database_interface::get_instance();
    $users = $dbi->get_never_logged_user_for_giver_day($lasttime, $limitlasttime);
    $recallusers = $dbi->get_recall_users($recallname);

    // Init error log.
    $errorlog = [];
    $usersrecalldata = [];

    foreach ($users as $user) {
        if (isset($recallusers[$user->id])) {
            continue;
        }

        try {
            // Set data mail.
            $supportuser = \core_user::get_support_user();
            $subject = get_string($subjectmailstringname, 'local_user');
            $content = get_string($contentmailstringname, 'local_user', [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'siteurl' => $siteurl,
                'forgotpasswordurl' => $forgotpasswordurl,
                'faqurl' => $faqurl,
            ]);
            $contenthtml = text_to_html($content, false, false, true);

            // Send email to user.
            email_to_user(
                $user,
                $supportuser,
                $subject,
                $content,
                $contenthtml
            );

            $usersrecalldata[] = $user->id;
        } catch (\Exception $e) {
            $errorlog[] = [
                'userid' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ];
        }
    }

    $dbi->insert_recall_users($usersrecalldata, $recallname);

    $haserror = local_user_display_error_task($errorlog);

    return !$haserror;
}

/**
 * Send reminder e-mails to users who have not logged in for a given number of months.
 *
 * @param int $nbday timestamp
 * @param int $limitnbday timestamp
 * @param string $subjectmailstringname key string
 * @param string $contentmailstringname key string
 * @param string $recallname recall name
 * @return bool
 * @throws coding_exception
 * @throws dml_exception
 */
function local_user_send_recall_to_not_logged_user_for_giver_day(
    $nbday,
    $limitnbday,
    $subjectmailstringname,
    $contentmailstringname,
    $recallname
) {
    global $CFG;

    // Time data.
    $nbdaytosecond = 86400 * $nbday;
    $limitnbdaytosecond = 86400 * $limitnbday;
    $now = time();
    $lasttime = $now - $nbdaytosecond;
    $limitlasttime = $now - $limitnbdaytosecond;

    // Url data.
    $catalogurl = $CFG->wwwroot . '/offre';
    $siteurl = $CFG->wwwroot;
    $forgotpasswordurl = $CFG->wwwroot . '/login/forgot_password.php';
    $faqurl = $CFG->wwwroot . '/local/staticpage/view.php?page=faq';

    // Get users.
    $dbi = \local_mentor_core\database_interface::get_instance();
    $users = $dbi->get_not_logged_user_for_giver_day($lasttime, $limitlasttime);
    $recallusers = $dbi->get_recall_users($recallname);

    // Init error log.
    $errorlog = [];
    $usersrecalldata = [];

    foreach ($users as $user) {
        if (isset($recallusers[$user->id])) {
            continue;
        }

        try {
            // Set data mail.
            $supportuser = \core_user::get_support_user();
            $subject = get_string($subjectmailstringname, 'local_user');
            $content = get_string($contentmailstringname, 'local_user', [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'catalogurl' => $catalogurl,
                'siteurl' => $siteurl,
                'forgotpasswordurl' => $forgotpasswordurl,
                'faqurl' => $faqurl,
            ]);
            $contenthtml = text_to_html($content, false, false, true);

            // Send email to user.
            email_to_user(
                $user,
                $supportuser,
                $subject,
                $content,
                $contenthtml
            );

            $usersrecalldata[] = $user->id;
        } catch (\Exception $e) {
            $errorlog[] = [
                'userid' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ];
        }
    }

    $dbi->insert_recall_users($usersrecalldata, $recallname);

    $haserror = local_user_display_error_task($errorlog);

    return !$haserror;
}

/**
 * Display error if this exists and return true,
 * Else return false.
 *
 * @param array $errors
 * @return bool
 * @throws coding_exception
 */
function local_user_display_error_task($errors) {
    if (empty($errors)) {
        return false;
    }

    mtrace(get_string('task_error_header', 'local_user'));
    foreach ($errors as $error) {
        mtrace(get_string('task_error_line', 'local_user', $error));
    }

    return true;
}

/**
 * Remove user list and display mtrace for task log.
 *
 * @param stdClass[] $users
 * @return bool
 * @throws coding_exception
 */
function local_user_deleted_users_for_task($users) {
    global $CFG;

    // Init error log.
    $errorlog = [];

    foreach ($users as $user) {
        // No delete guest user.
        if ($CFG->siteguest === $user->id || $user->username === 'guest' || isguestuser($user)) {
            continue;
        }

        if ($user->auth === 'manual' && is_siteadmin($user)) {
            continue;
        }

        // Delete user.
        try {
            if (delete_user($user)) {
                mtrace(get_string('task_user_deleted_log', 'local_user', [
                    'userid' => $user->id,
                    'email' => $user->email,
                    'date' => date('Y-m-d H:i:s', time()),
                ]));
            }
        } catch (\Exception $e) {
            $errorlog[] = [
                'userid' => $user->id,
                'email' => $user->email,
                'message' => $e->getMessage(),
            ];
        }
    }

    $haserror = local_user_display_error_task($errorlog);

    return !$haserror;
}
