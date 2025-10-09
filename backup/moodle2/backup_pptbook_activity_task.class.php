<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/mod/pptbook/backup/moodle2/backup_pptbook_stepslib.php');

class backup_pptbook_activity_task extends backup_activity_task {
    protected function define_my_settings() {}
    protected function define_my_steps() {
        $this->add_step(new backup_pptbook_activity_structure_step('pptbook_structure', 'pptbook.xml'));
    }
    static public function encode_content_links($content) { return $content; }
}
