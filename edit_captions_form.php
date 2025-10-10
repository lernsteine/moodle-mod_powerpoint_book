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
 * [Short description of the file]
 *
 * @package    mod_pptbook
 * @copyright  2025 Ralf Hagemeister <ralf.hagemeister@lernsteine.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class mod_pptbook.
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class mod_pptbook_edit_captions_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $files = $this->_customdata['files'] ?? [];
        foreach ($files as $filename => $existing) {
            $mform->addElement('textarea', 'caption[' . $filename . ']', s($filename), ['rows' => 2, 'cols' => 80]);
            $mform->setType('caption[' . $filename . ']', PARAM_RAW);
            $mform->setDefault('caption[' . $filename . ']', $existing);
        }

        // Hidden cmid + id (beide = CM-ID).
        $cmid = $this->_customdata['cmid'] ?? 0;
        $mform->addElement('hidden', 'cmid', $cmid);
        $mform->setType('cmid', PARAM_INT);
        $mform->addElement('hidden', 'id', $cmid);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
