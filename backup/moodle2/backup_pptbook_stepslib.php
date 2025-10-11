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

/**
 * Restore steps for the PPT Book activity.
 *
 * @package   mod_pptbook
 * @category  backup
 * @copyright 2025 Ralf
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Structure step to restore one PPT Book activity.
 */
class restore_pptbook_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define the structure of the restore paths.
     *
     * @return array
     */
    protected function define_structure(): array {
        $paths = [];
        $paths[] = new restore_path_element('pptbook', '/activity/pptbook');
        $paths[] = new restore_path_element('pptbook_item', '/activity/pptbook/items/item');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the main pptbook element.
     *
     * @param array $data
     * @return void
     */
    protected function process_pptbook($data): void {
        global $DB;

        $data = (object) $data;
        $data->course = $this->get_courseid();

        // Insert activity record.
        $newid = $DB->insert_record('pptbook', $data);

        // Apply instance id mapping.
        $this->apply_activity_instance($newid);
    }

    /**
     * Process a child item element.
     *
     * @param array $data
     * @return void
     */
    protected function process_pptbook_item($data): void {
        global $DB;

        $data = (object) $data;
        $data->pptbookid = $this->get_new_parentid('pptbook');

        $DB->insert_record('pptbook_item', $data);
    }

    /**
     * After the main restore, add related files.
     *
     * @return void
     */
    protected function after_execute(): void {
        // Add files for the 'slides' file area.
        $this->add_related_files('mod_pptbook', 'slides', null);
    }
}
