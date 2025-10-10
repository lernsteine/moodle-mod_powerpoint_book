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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_pptbook_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name', 'mod_pptbook'), ['size' => 64]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements();

        $mform->addElement('filemanager', 'slides_filemanager', get_string('slides', 'mod_pptbook'), null, [
            'subdirs' => 0,
            'maxfiles' => -1,
            'accepted_types' => ['.png'],
        ]);
        $mform->addHelpButton('slides_filemanager', 'slides', 'mod_pptbook');

        $mform->addElement('hidden', 'captionsjson', '');
        $mform->setType('captionsjson', PARAM_RAW);

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    public function data_preprocessing(&$defaultvalues) {
        if ($this->current->instance) {
            $draftitemid = file_get_submitted_draft_itemid('slides_filemanager');
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_pptbook', 'slides', 0, ['subdirs' => 0, 'maxfiles' => -1]);
            $defaultvalues['slides_filemanager'] = $draftitemid;
        }
    }
}
