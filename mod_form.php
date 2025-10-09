<?php
require_once($CFG->dirroot.'/course/moodleform_mod.php');

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
            'accepted_types' => ['.png']
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
            file_prepare_draft_area($draftitemid, $this->context->id, 'mod_pptbook', 'slides', 0, ['subdirs'=>0, 'maxfiles'=>-1]);
            $defaultvalues['slides_filemanager'] = $draftitemid;
        }
    }
}
