<?php

/**
 * Plugin events and observers
 *
 * @package local_user
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\role_assigned',
        'callback' => 'local_user_observer::update_program_enrol_user_role',
    ],
];
