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
 * Backup steps for mod_pptbook.
 *
 * Defines the activity structure for backup.
 *
 * @package    mod_pptbook
 * @category   backup
 * @copyright  2025 Ralf Hagemeister <ralf.hagemeister@lernsteine>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the backup structure step for the PPT Book activity.
 */
class backup_pptbook_activity_structure_step extends backup_activity_structure_step {
    /**
     * Defines the backup structure of the activity.
     *
     * @return backup_nested_element The prepared activity structure.
     */
    protected function define_structure() {

        $pptbook = new backup_nested_element(
            'pptbook',
            ['id'],
            [
                'course',
                'name',
                'intro',
                'introformat',
                'captionsjson',
                'timecreated',
                'timemodified',
            ]
        );

        $items = new backup_nested_element('items');

        $item = new backup_nested_element(
            'item',
            ['id'],
            [
                'filename',
                'title',
                'caption',
                'sortorder',
                'timecreated',
                'timemodified',
            ]
        );

        // Build the tree.
        $pptbook->add_child($items);
        $items->add_child($item);

        // Define sources.
        $pptbook->set_source_table('pptbook', ['id' => backup::VAR_ACTIVITYID]);
        $item->set_source_table('pptbook_item', ['pptbookid' => backup::VAR_PARENTID]);

        // Define file annotations.
        $pptbook->annotate_files('mod_pptbook', 'slides', null);

        return $this->prepare_activity_structure($pptbook);
    }
}
