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
 * Default view for generate0
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once('forms.php');

// cek apakah user sedang login
require_login();

// cek parameter course id pada URL
$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

// shortlink object
$shortlink = new \local_shortlink\Shortlink($courseid);
$shortlink_config = get_config('local_shortlink');

// set page context
$context = context_course::instance($courseid);
$url = new moodle_url('/local/shortlink/index.php', array('id' => $courseid));
$courseUrl = new moodle_url('/course/view.php', array('id' => $courseid));


// apply page context
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Generate Short Link");
$PAGE->set_heading("Generate Short Link for $course->fullname");
$PAGE->requires->js('/local/shortlink/js/shortlink.js');


// generate shortlink form
$mform = new simplehtml_form($url);

// cek jika form disubmit
if($formData = $mform->get_data()){
    try {
        $shortlink->createShortLink($formData->short_link, $USER->id, time());
        \core\notification::success("Generate shortlink success");
    } catch (\dml_exception $e) {
        \core\notification::error("Generate shortlink gagal. <br>Error Message: $e");
    }

    redirect($url);
}

// set default data shortlink dari course shortname
$mform->set_data(
    array('short_link' => preg_replace('/[^a-zA-Z0-9]/', '', strtolower($course->shortname)))
);

// render output
echo $OUTPUT->header();
$mform->display();

?>
<h3>Generated Link</h3>
<table class="generaltable flexible boxaligncenter text-center">
    <tr>
        <th>Link</th>
        <th>Click Count</th>
    </tr>
    <?php
        foreach ($shortlink->getShortLinks($USER->id) as $link) {
            echo "<tr>
                <td>
                    <div class='row px-2'>
                        <input id='#link$link->id' class='form-control col' type='text' value='$shortlink_config->urlroot/$link->link' readonly>
                        <button onclick='copyUrl(\"#link$link->id\")' class='btn btn-primary col' style='max-width: 72px'>COPY</button>
                    </div>
                </td>
                <td class='text-center'>$link->total_click</td>
            </tr>";
        }
    ?>
</table>

<?php
echo $OUTPUT->footer();
