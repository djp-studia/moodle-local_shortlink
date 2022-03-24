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
 * Redirect shortlink to actual course page
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

// get link parameter and link object
$linkParameter = required_param('link', PARAM_TEXT);
$shortlinkObj = new \local_shortlink\Shortlink();

try {
    // cek if link exists
    $link = $shortlinkObj->getShortlink($linkParameter);
} catch (Exception $e) {
    // if not, show error message
    $PAGE->set_heading("Link Error");
    \core\notification::error("Short Link yang Anda tuju tidak valid. Mohon periksa kembali.");
    echo $OUTPUT->header();
    echo "<a href='$CFG->wwwroot' class='btn btn-primary'>Back</a>";
    echo $OUTPUT->footer();
}

// add visited counter
$shortlinkObj->visited($link, $USER->id);

// redirect user to actual course
redirect(new moodle_url('/course/view.php', array("id" => $link->course)));
