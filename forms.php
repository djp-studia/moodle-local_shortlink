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
 * Shortlink form object module
 *
 * @package     local_shortlink
 * @category    admin
 * @copyright   2022 Agung Pratama <prrtmgng@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined("MOODLE_INTERNAL") || die();

require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform {
    public function definition() {
        global $CFG;
       
        $mform = $this->_form;
        $mform->addElement('text', 'short_link', "Short Link", "Testing", "Testing");
        $mform->setType('short_link', PARAM_ALPHANUMEXT);
        $mform->addRule('short_link', "Hanya Angka dan Huruf", 'alphanumeric', null, 'client');
        $this->add_action_buttons(false);
    }
}