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
 * Database upgrades for the user local.
 *
 * @package   local_user
 * @copyright  2023 Edunao SAS (contact@edunao.com)
 * @author     Adrien Jamot <adrien.jamot@edunao.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 */

/**
 * Upgrade the local_user database.
 *
 * @param int $oldversion The version number of the plugin that was installed.
 * @return boolean
 * @throws ddl_exception
 * @throws ddl_table_missing_exception
 * @throws dml_exception
 */
function xmldb_local_user_upgrade($oldversion) {
    global $DB, $CFG;

    require_once($CFG->libdir . '/db/upgradelib.php'); // Core Upgrade-related functions.

    $dbman = $DB->get_manager();

    if ($oldversion < 2023030300) {
        mtrace('START : "Ministère de la Transition écologique & de la Cohésion des territoires"' .
            ' to "Ministère de la Transition écologique et de la Cohésion des territoires"');
        $oldtext = 'Ministère de la Transition écologique & de la Cohésion des territoires';
        $newtext = 'Ministère de la Transition écologique et de la Cohésion des territoires';
        $DB->execute('
            UPDATE {user_info_data}
            SET data = REPLACE(data, :oldtext, :newtext)
            ', ['oldtext' => $oldtext, 'newtext' => $newtext]
        );
        mtrace('END');
    }

    if ($oldversion < 2024011600) {
        // Define table 'user_recall' to be created.
        $table = new xmldb_table('user_recall');

        // Adding fields to table user_recall.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('recallname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table user_recall.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_mdl_user', XMLDB_KEY_FOREIGN, ['userid'], 'user', 'id');

        // Adding index to table.
        $table->add_index('user-recallname', XMLDB_INDEX_UNIQUE, ['userid', 'recallname']);

        // Conditionally launch create table for regions.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

    }

    return true;
}
