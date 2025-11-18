<?php

namespace local_user\utils;

defined('MOODLE_INTERNAL') || die();

class local_user_service
{
    /**
     * @var local_user_database_interface
     */
    protected $dbi;

    public function __construct()
    {
        $this->dbi = new local_user_database_interface;
    }

    /**
     * Get the last user_enrolment_deleted event timecreated and compare to today date.
     * If the user_enrolment_deleted timecreated is before the today's date - given time, it return true,
     * else it return false.
     * 
     * @param int $userid
     * @param int $useridcreationdate
     * @param int $timeinsecond
     * @return bool
     */
    public function user_last_enrolment_deleted_time_diff(int $userid, int $useridcreationdate, int $timeinsecond): bool
    {
        $datenow = time();

        $lastuserenrolmentdeleted = $this->dbi->last_user_enrolment_deleted_record($userid);

        if ($lastuserenrolmentdeleted !== false) {
            $lastuserenrolmentdeletedtime = intval($lastuserenrolmentdeleted->timecreated);

            $timediff = $datenow - $lastuserenrolmentdeletedtime;

            if ($timediff > $timeinsecond)
                return true;
        }

        $datecreationdiff = $datenow - $useridcreationdate;

        if ($datecreationdiff > $timeinsecond)
            return true;

        return false;
    }
}