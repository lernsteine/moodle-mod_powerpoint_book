<?php
defined('MOODLE_INTERNAL') || die();
class restore_pptbook_activity_structure_step extends restore_activity_structure_step {
    protected function define_structure() {
        $paths = [];
        $paths[] = new restore_path_element('pptbook', '/activity/pptbook');
        $paths[] = new restore_path_element('pptbook_item', '/activity/pptbook/items/item');
        return $this->prepare_activity_structure($paths);
    }
    protected function process_pptbook($data) {
        global $DB;
        $data = (object)$data;
        $data->course = $this->get_courseid();
        $newid = $DB->insert_record('pptbook', $data);
        $this->apply_activity_instance($newid);
    }
    protected function process_pptbook_item($data) {
        global $DB;
        $data = (object)$data;
        $data->pptbookid = $this->get_new_parentid('pptbook');
        $DB->insert_record('pptbook_item', $data);
    }
    protected function after_execute() {
        $this->add_related_files('mod_pptbook', 'slides', null);
    }
}
