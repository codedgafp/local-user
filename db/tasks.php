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
 * Plugin scheduled tasks
 *
 * @package    local_user
 * @copyright  2024 Edunao SAS (contact@edunao.com)
 * @author     remi <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'local_user\task\never_logged_user_first_recall',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '1',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\never_logged_user_last_recall',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '2',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\never_logged_user_deleted',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '3',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\inactive_enrolment_external_user_deleted',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '7',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\inactive_enrolment_external_user_reminder_email',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '7',
        'day' => '3',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\suspended_user_deleted',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '4',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\not_logged_user_first_recall',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '5',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\not_logged_user_last_recall',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '6',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
    [
        'classname' => 'local_user\task\not_logged_user_deleted',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '7',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
];
