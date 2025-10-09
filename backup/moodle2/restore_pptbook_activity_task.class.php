<?php
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/mod/pptbook/backup/moodle2/restore_pptbook_stepslib.php');

class restore_pptbook_activity_task extends restore_activity_task {
    protected function define_my_settings() {}
    protected function define_my_steps() {
        $this->add_step(new restore_pptbook_activity_structure_step('pptbook_structure', 'pptbook.xml'));
    }
    public static function define_decode_contents() { return []; }
    public static function define_decode_rules() { return []; }
}
