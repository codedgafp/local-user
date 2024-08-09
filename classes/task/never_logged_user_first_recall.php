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


namespace local_user\task;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/user/lib.php');

/**
 * Never logged user first recall task.
 *
 * @package    local_user
 * @copyright  2024 Edunao SAS (contact@edunao.com)
 * @author     remi <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class never_logged_user_first_recall extends \core\task\scheduled_task {
    /**
     * Task name.
     *
     * @return \lang_string|string
     * @throws \coding_exception
     */
    public function get_name() {
        // Shown in admin screens.
        return get_string('task_never_logged_user_first_recall', 'local_user');
    }

    public function execute() {
        $result = local_user_send_recall_to_never_logged_user(
            4,
            5,
            'subject_mail_never_logged_user_first_recall',
            'content_mail_never_logged_user_first_recall',
            'never_logged_user_first_recall'
        );

        if (!$result) {
            // Set task to fail.
            \core\task\manager::scheduled_task_failed($this);
        }
    }
}
