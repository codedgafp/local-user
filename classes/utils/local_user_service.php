<?php

namespace local_user\utils;

defined('MOODLE_INTERNAL') || die();

class local_user_service
{
    /**
     * @var local_user_database_interface
     */
    protected $dbi;

    public function __construct(?local_user_database_interface $dbi = null)
    {
        $this->dbi = $dbi ?? new local_user_database_interface;
    }

    /**
     * Get the last user_enrolment_deleted event timecreated and compare to today date.
     * If the user_enrolment_deleted timecreated is after the given time, the user can
     * be deleted.
     * 
     * @param int $userid
     * @param int $useridcreationdate
     * @param int $timeinsecond
     * @param int|null $lastnotifytime
     * @return bool
     */
    public function user_can_be_deleted_checked_by_time(int $userid, int $useridcreationdate, int $timeinsecond, ?int $lastnotifytime = null): bool
    {
        $datenow = time();

        $lastuserenrolmentdeleted = $this->dbi->last_user_enrolment_deleted_record($userid);

        if ($lastuserenrolmentdeleted !== false) {
            $lastuserenrolmentdeletedtime = intval($lastuserenrolmentdeleted->timecreated);
            $timediff = $datenow - $lastuserenrolmentdeletedtime;

            if ($lastnotifytime === null) {
                return $timediff >= $timeinsecond;
            }

            $lasttimediff = $lastnotifytime - $lastuserenrolmentdeletedtime;
            return $lasttimediff < $timeinsecond && $timediff >= $timeinsecond;
        }

        $datecreationdiff = $datenow - $useridcreationdate;

        if ($lastnotifytime === null) {
            return $datecreationdiff >= $timeinsecond;
        }

        $lastimedatecreationdiff = $lastnotifytime - $useridcreationdate;
        return $lastimedatecreationdiff < $timeinsecond && $datecreationdiff >= $timeinsecond;
    }
}
