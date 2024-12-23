<?php
namespace local_user\task;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/mentor_core/lib.php');

class importcsv_users_task extends \core\task\adhoc_task {
    
    /**
     * Execute the task
     */
    public function execute() {
        global $CFG;
        mtrace('Starting user import...');
        
        // Get custom data set when creating the task
        $data = $this->get_custom_data();

        $userstoreactivate = $data->userstoreactivate;
        $addtoentity = $data->addtoentity;
        $areexternals = $data->areexternals;
        $users = $data->users;
        $entityid = $data->entityid;

        if (empty($users) || empty($entityid)) {
            mtrace('Missing required data for import');
            return;
        }
        
        // Decode users before import
        $users = json_decode(json_encode($users), true);
        // Import users.
        try {
            local_mentor_core_create_users_csv($users, $userstoreactivate, $entityid, $addtoentity, $areexternals);
        } catch (\Exception $e) {
            mtrace('Error during import: ' . $e->getMessage());
        }
    }
}