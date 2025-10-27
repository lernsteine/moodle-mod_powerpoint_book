<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event: the list of PPT Book instances in a course was viewed.
 *
 * @package   mod_pptbook
 * @copyright 2025 Ralf Hagemeister
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class course_module_instance_list_viewed extends \core\event\course_module_instance_list_viewed {
    protected function init(): void {
        $this->data['crud']      = 'r'; // Read.
        $this->data['edulevel']  = self::LEVEL_OTHER;
        $this->data['objecttable'] = null;
    }

    public static function get_name(): string {
        return get_string('eventinstances_list_viewed', 'mod_pptbook');
    }
	
/**
 * Describes the Event for Logs.
 * @return string Describtion about the user who viewed the module.
 */
    public function get_description(): string {
        return "The user with id '{$this->userid}' viewed the list of PPT Book activities in the course with id '{
			$this->courseid
			}'.";
    }

/**
 * delivers the target URL für this event.
 *
 * @return \moodle_url URL of the activity (view.php).
 */
    public function get_url(): \moodle_url {
        return new \moodle_url('/mod/pptbook/index.php', ['id' => $this->courseid]);
    }
}
