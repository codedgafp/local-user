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
 * Entity controller
 *
 * @package    local_user
 * @copyright  2020 Edunao SAS (contact@edunao.com)
 * @author     remi <remi.colet@edunao.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_user;

use local_mentor_core\entity_api;
use local_mentor_core\controller_base;

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/local/user/lib.php');
require_once($CFG->dirroot . '/local/mentor_core/api/entity.php');
require_once($CFG->dirroot . '/local/mentor_core/classes/controllers/controller_base.php');

// Require login.
require_login();

class entity_controller extends controller_base {

    /**
     * Execute action
     *
     * @return object
     */
    public function execute() {

        try {
            $action = $this->get_param('action');
            switch ($action) {
                case 'get_members' :
                    // Get count all members record by entity.
                    $data = new \stdClass();
                    $data->status = false;
                    $data->datefrom = false;
                    $data->dateto = false;
                    $data->search = false;
                    $data->order = false;
                    $data->entityid = $this->get_param('entityid', PARAM_INT);
                    $data->suspendedusers = $this->get_param('suspendedusers', PARAM_RAW);
                    $data->externalusers = $this->get_param('externalusers', PARAM_RAW);
                    $data->mainonly = $this->get_param('mainonly', PARAM_BOOL, false);
                    $data->length = $this->get_param('length', PARAM_INT, null);
                    $data->start = $this->get_param('start', PARAM_INT, null);
                    $data->search = $this->get_param('search', PARAM_RAW, null);
                    $data->data = $this->get_members($data);
                    // Count Members
                    $data->recordsFiltered = $this->get_members_count($data, true);
                    $data->recordsTotal = $this->get_members_count($data, false);
                    
                    return $data;
                default:
                    break;
            }

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Get entity members
     *
     * @param $data
     * @return array
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public static function get_members($data) {
        $entity = entity_api::get_entity(entityid: $data->entityid);
        $members = array_values($entity->get_members($data));
        
        $entities = [];

        // Get other data.
        foreach ($members as $key => $member) {

            if (!isset($entities[$member->mainentity])) {
                $entities[$member->mainentity] = entity_api::get_entity_by_name($member->mainentity, $data->mainonly);
            }

            $member->entityshortname
                = isset($entities[$member->mainentity]->shortname) ?
                $entities[$member->mainentity]->shortname :
                '';

            // Check if user has capability to update user profile.
            $members[$key]->hasconfigaccess = $member->can_edit_profile(); 
        }
        return $members;
    }

    public static function get_members_count($data, $enablefilters){
        $entity = entity_api::get_entity(entityid: $data->entityid);
        return $entity->get_members_count($data, $enablefilters);

    }
    
}
