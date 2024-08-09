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
 * Suspended user deleted task.
 *
 * @package    local_user
 * @copyright  2024 Edunao SAS (contact@edunao.com)
 * @author     remi <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class suspended_user_deleted extends \core\task\scheduled_task {
    /**
     * @var \local_mentor_core\database_interface
     */
    protected $dbi;

    public function __construct() {
        $this->dbi = \local_mentor_core\database_interface::get_instance();
    }

    /**
     * Task name.
     *
     * @return \lang_string|string
     * @throws \coding_exception
     */
    public function get_name() {
        // Shown in admin screens.
        return get_string('task_suspended_user_deleted', 'local_user');
    }

    public function execute() {
        // Time data.
        $now = time();
        $onyeartime = 365 * 86400;
        $oneyear = $now - $onyeartime - 1;

        // Get users.
        $users = $this->dbi->get_user_suspended_for_days_given($oneyear);

        $result = local_user_deleted_users_for_task($users);

        if (!$result) {
            // Set task to fail.
            \core\task\manager::scheduled_task_failed($this);
        }
    }
}
