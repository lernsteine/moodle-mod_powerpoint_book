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
 * Backup task for the PPT Book activity.
 *
 * @package   mod_pptbook
 * @category  backup
 * @copyright 2025 Ralf
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/pptbook/backup/moodle2/backup_pptbook_stepslib.php');

/**
 * Defines the backup task for the PPT Book activity.
 */
class backup_pptbook_activity_task extends backup_activity_task {
    /**
     * Activity has no custom backup settings.
     */
    protected function define_my_settings() {
        // No custom settings.
    }

    /**
     * Register the backup steps.
     */
    protected function define_my_steps() {
        $this->add_step(new backup_pptbook_activity_structure_step('pptbook_structure', 'pptbook.xml'));
    }

    /**
     * Encode content links (no-op for this plugin).
     *
     * @param string $content
     * @return string
     */
    public static function encode_content_links($content) {
        return $content;
    }
}
