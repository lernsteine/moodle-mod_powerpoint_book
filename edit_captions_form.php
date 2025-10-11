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
 * Form to edit slide captions for the PPT Book activity.
 *
 * @package   mod_pptbook
 * @category  form
 * @copyright 2025 Ralf Hagemeister
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Captions editing form for mod_pptbook.
 *
 * Expects in $this->_customdata:
 *  - 'files' (array<string,string>): filename => existing caption.
 *  - 'cmid' (int): course module id.
 */
class mod_pptbook_edit_captions_form extends moodleform {
    /**
     * Define the form elements.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $files = $this->_customdata['files'] ?? [];
        foreach ($files as $filename => $existing) {
            $elementname = 'caption[' . $filename . ']';
            $mform->addElement('textarea', $elementname, s($filename), ['rows' => 2, 'cols' => 80]);
            $mform->setType($elementname, PARAM_RAW);
            $mform->setDefault($elementname, $existing);
        }

        // Hidden cmid + id (both equal to course module ID).
        $cmid = (int)($this->_customdata['cmid'] ?? 0);
        $mform->addElement('hidden', 'cmid', $cmid);
        $mform->setType('cmid', PARAM_INT);

        $mform->addElement('hidden', 'id', $cmid);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
