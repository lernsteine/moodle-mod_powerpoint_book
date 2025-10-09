<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');

class mod_pptbook_edit_captions_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $files = $this->_customdata['files'] ?? [];
        foreach ($files as $filename => $existing) {
            $mform->addElement('textarea', 'caption['.$filename.']', s($filename), ['rows'=>2, 'cols'=>80]);
            $mform->setType('caption['.$filename.']', PARAM_RAW);
            $mform->setDefault('caption['.$filename.']', $existing);
        }

        // Hidden cmid + id (beide = CM-ID) – robust gegenüber unterschiedlichen Router-/Form-Verhalten
        $cmid = $this->_customdata['cmid'] ?? 0;
        $mform->addElement('hidden', 'cmid', $cmid);
        $mform->setType('cmid', PARAM_INT);
        $mform->addElement('hidden', 'id', $cmid);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}