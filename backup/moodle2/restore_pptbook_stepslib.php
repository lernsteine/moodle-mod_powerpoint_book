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
