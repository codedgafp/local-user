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
 * Ad hoc task for creating a session
 *
 * @package    local_mentor_core
 * @copyright  2021 Edunao SAS (contact@edunao.com)
 * @author     adrien <adrien@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_user\task;

class create_users_csv extends \core\task\adhoc_task {
    public function execute()
    {
        global $CFG;

        require_once $CFG->dirroot . '/local/mentor_core/lib.php';

        $data = $this->get_custom_data(); 

        // Define all required custom data fields.
        $requiredfields = [
            'users',
            'userstoreactivate', 
            'entityid'
        ];

        // Check all required fields.
        foreach ($requiredfields as $requiredfield) {
            if (!isset($data->{$requiredfield})) {
                throw new \coding_exception('Field ' . $requiredfield . ' is missing in custom data');
            }
        }

        $reportData = local_mentor_core_create_users_csv(
            json_decode(json_encode($data->users), true), 
            json_decode(json_encode($data->userstoreactivate), true),
            $data->entityid
        );
        
        local_mentor_core_send_report($data->csvcontent , $reportData , $data->delimiter_name,$data->filename, $data->user_id);

        return true;
    }
}
