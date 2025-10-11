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
 * Restore task for mod_pptbook.
 *
 * @package   mod_pptbook
 * @category  backup
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/mod/pptbook/backup/moodle2/restore_pptbook_stepslib.php');

/**
 * Defines the restore task for the PPT Book activity.
 */
class restore_pptbook_activity_task extends restore_activity_task {
    /**
     * Define optional restore settings (none for this activity).
     */
    protected function define_my_settings() {
        // No custom settings.
    }

    /**
     * Register restore steps.
     */
    protected function define_my_steps() {
        $this->add_step(new restore_pptbook_activity_structure_step('pptbook_structure', 'pptbook.xml'));
    }

    /**
     * Define content to decode (none).
     *
     * @return array
     */
    public static function define_decode_contents() {
        return [];
    }

    /**
     * Define link decode rules (none).
     *
     * @return array
     */
    public static function define_decode_rules() {
        return [];
    }
}
