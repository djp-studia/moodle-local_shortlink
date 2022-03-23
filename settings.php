<?php
// This file is part of Moodle - https://moodle.org/
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
 * Add shortlink administrative setting
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_shortlink_settings', new lang_string('pluginname', 'local_shortlink')));
    $settingspage = new admin_settingpage('managelocalshortlink', "Manage Short Link Settings");

    if ($ADMIN->fulltree) {
        $yesno = array(0 => get_string('no'), 1 => get_string('yes'));

        // set short link root url
        $settingspage->add(new admin_setting_configtext(
            'local_shortlink/urlroot',
            "Root URL",
            null,
            $CFG->wwwroot,
            PARAM_TEXT
        ));
    }

    $ADMIN->add('localplugins', $settingspage);
}