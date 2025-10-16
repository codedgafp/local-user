<?php

defined('MOODLE_INTERNAL') || die();

use local_user\utils\local_user_database_interface;
use local_user\helper\testhelper;

class local_user_task_testcase extends advanced_testcase
{
    /**
     * @var moodle_database
     */
    protected moodle_database $db;

    protected local_user_database_interface $dbi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetAfterTest(true);

        global $DB;

        $this->db = $DB;
        $this->dbi = new local_user_database_interface;
    }

    public function test_inactive_enrolment_external_user_deleted_task()
    {
        self::setAdminUser();

        global $CFG;

        $CFG->allowemailaddresses = 'moodle-test.com';

        $entity = testhelper::create_entity($this);
        $domain = (object)[
            'course_categories_id' => $entity->id,
            'domain_name' => 'moodle-test.com',
            'created_at' => time(),
            'disabled_at' => null
        ];
        $this->db->insert_record('course_categories_domains', $domain, false);

        $session = self::getDataGenerator()->create_course();

        $user1 = $this->getDataGenerator()->create_user(['email' => 'user1@moodle-test.com']);
        $user2 = $this->getDataGenerator()->create_user(['email' => 'user2@moodle-test.com']);
        $user3 = $this->getDataGenerator()->create_user(['email' => 'user3@moodle-test.com']);
        $user4 = $this->getDataGenerator()->create_user(['email' => 'user4@moodle-test.com']);
        $user5 = $this->getDataGenerator()->create_user(['email' => 'user5@moodle.mail']);
        $user6 = $this->getDataGenerator()->create_user(['email' => 'user6@moodle.mail']);
        $user7 = $this->getDataGenerator()->create_user(['email' => 'user7@moodle.mail']);
        $user8 = $this->getDataGenerator()->create_user(['email' => 'user8@moodle.mail']);

        $userstoupdate = [$user2, $user4, $user6, $user8];
        foreach ($userstoupdate as $userupdate) {
            $newcreationtime = strtotime('-5 months');

            $updateuser = new \stdClass;
            $updateuser->id = $userupdate->id;
            $updateuser->userid = $userupdate->id;
            $updateuser->timecreated = $newcreationtime;
            $this->db->update_record('user', $updateuser);
        }

        $userstoenrol = [$user3, $user4, $user7, $user8];
        foreach ($userstoenrol as $userenrol) {
            self::getDataGenerator()->enrol_user($userenrol->id, $session->id, 'participant');
        }

        $externaleuserrole = $this->db->get_record('role', ['shortname' => 'utilisateurexterne']);
        $result = $this->dbi->inactive_enrolment_external_users_query("NOT IN ('cohort')", [], $externaleuserrole->id, '30 days');

        self::assertCount(1, $result);

        $resultuser = $result[array_key_first($result)];
        self::assertEquals($user6->id, $resultuser->id);
    }
}
