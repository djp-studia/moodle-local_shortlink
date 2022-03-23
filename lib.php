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
 * Add shortlink to course navigation
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Fungsi untuk menambahkan navigasi create course shortlink
 */
function local_shortlink_extend_navigation_course($navigation, $course, $context) {
    $url = new moodle_url('/local/shortlink/index.php', array('id' => $course->id));
    $icon = 'i/publish';
    $label = 'Create Shortlink';

    $navigation->add($label, $url, navigation_node::TYPE_SETTING, null, null, new pix_icon($icon, ''));
}
