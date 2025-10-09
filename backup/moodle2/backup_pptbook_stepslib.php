<?php
defined('MOODLE_INTERNAL') || die();
class backup_pptbook_activity_structure_step extends backup_activity_structure_step {
    protected function define_structure() {
        $pptbook = new backup_nested_element('pptbook', ['id'], [
            'course', 'name', 'intro', 'introformat', 'captionsjson', 'timecreated', 'timemodified'
        ]);
        $items = new backup_nested_element('items');
        $item  = new backup_nested_element('item', ['id'], ['filename','title','caption','sortorder','timecreated','timemodified']);
        $pptbook->add_child($items);
        $items->add_child($item);
        $pptbook->set_source_table('pptbook', ['id' => backup::VAR_ACTIVITYID]);
        $item->set_source_table('pptbook_item', ['pptbookid' => backup::VAR_PARENTID]);
        $pptbook->annotate_files('mod_pptbook', 'slides', null);
        return $this->prepare_activity_structure($pptbook);
    }
}
