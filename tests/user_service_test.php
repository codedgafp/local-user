<?php

defined('MOODLE_INTERNAL') || die();

use local_user\utils\local_user_database_interface;
use local_user\utils\local_user_service;

class local_user_service_testcase extends advanced_testcase
{
    private int $threshold;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->threshold = 7 * 24 * 3600; // 604800
    }

    private function make_service(mixed $last_enrolment_record): local_user_service
    {
        $dbimock = $this->createMock(local_user_database_interface::class);
        $dbimock->method('last_user_enrolment_deleted_record')->willReturn($last_enrolment_record);
        return new local_user_service($dbimock);
    }

    // --- Aucun événement de désinscription : basé sur timecreated ---

    public function test_first_run_user_created_before_threshold_should_notify(): void
    {
        $now = time();
        $service = $this->make_service(false);

        $timecreated = $now - $this->threshold - 3600; // créé il y a 7j + 1h

        $result = $service->user_can_be_deleted_checked_by_time(1, $timecreated, $this->threshold, null);

        $this->assertTrue($result);
    }

    public function test_first_run_user_created_after_threshold_should_not_notify(): void
    {
        $now = time();
        $service = $this->make_service(false);

        $timecreated = $now - $this->threshold + 3600; // créé il y a 7j - 1h (trop récent)

        $result = $service->user_can_be_deleted_checked_by_time(1, $timecreated, $this->threshold, null);

        $this->assertFalse($result);
    }

    public function test_threshold_crossed_between_runs_should_notify(): void
    {
        $now = time();
        $service = $this->make_service(false);

        // Seuil franchi il y a 3600s (timecreated + threshold = now - 3600)
        $timecreated   = $now - $this->threshold - 3600;
        // Dernière exécution avant le franchissement du seuil
        $lastnotifytime = $now - 7200;

        $result = $service->user_can_be_deleted_checked_by_time(1, $timecreated, $this->threshold, $lastnotifytime);

        $this->assertTrue($result);
    }

    public function test_threshold_already_crossed_at_last_run_should_not_notify(): void
    {
        $now = time();
        $service = $this->make_service(false);

        // Seuil franchi il y a 7200s
        $timecreated    = $now - $this->threshold - 7200;
        // Dernière exécution après le franchissement du seuil
        $lastnotifytime = $now - 3600;

        $result = $service->user_can_be_deleted_checked_by_time(1, $timecreated, $this->threshold, $lastnotifytime);

        $this->assertFalse($result);
    }

    public function test_threshold_not_yet_crossed_should_not_notify(): void
    {
        $now = time();
        $service = $this->make_service(false);

        $timecreated    = $now - $this->threshold + 3600; // seuil pas encore atteint
        $lastnotifytime = $now - 7200;

        $result = $service->user_can_be_deleted_checked_by_time(1, $timecreated, $this->threshold, $lastnotifytime);

        $this->assertFalse($result);
    }

    // --- Avec événement de désinscription : basé sur la date de désinscription ---

    public function test_first_run_unenrolled_before_threshold_should_notify(): void
    {
        $now            = time();
        $unenrolledtime = $now - $this->threshold - 3600;
        $service        = $this->make_service((object)['timecreated' => $unenrolledtime]);

        $result = $service->user_can_be_deleted_checked_by_time(1, $now, $this->threshold, null);

        $this->assertTrue($result);
    }

    public function test_first_run_unenrolled_after_threshold_should_not_notify(): void
    {
        $now            = time();
        $unenrolledtime = $now - $this->threshold + 3600;
        $service        = $this->make_service((object)['timecreated' => $unenrolledtime]);

        $result = $service->user_can_be_deleted_checked_by_time(1, $now, $this->threshold, null);

        $this->assertFalse($result);
    }

    public function test_unenrolment_threshold_crossed_between_runs_should_notify(): void
    {
        $now            = time();
        $unenrolledtime = $now - $this->threshold - 3600; // seuil franchi il y a 3600s
        $lastnotifytime = $now - 7200;                    // avant le franchissement
        $service        = $this->make_service((object)['timecreated' => $unenrolledtime]);

        $result = $service->user_can_be_deleted_checked_by_time(1, $now, $this->threshold, $lastnotifytime);

        $this->assertTrue($result);
    }

    public function test_unenrolment_threshold_already_crossed_at_last_run_should_not_notify(): void
    {
        $now            = time();
        $unenrolledtime = $now - $this->threshold - 7200; // seuil franchi il y a 7200s
        $lastnotifytime = $now - 3600;                    // après le franchissement
        $service        = $this->make_service((object)['timecreated' => $unenrolledtime]);

        $result = $service->user_can_be_deleted_checked_by_time(1, $now, $this->threshold, $lastnotifytime);

        $this->assertFalse($result);
    }
}
