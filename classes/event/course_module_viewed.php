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
 * Event: a PPT Book course module was viewed.
 *
 * @package   mod_pptbook
 * @copyright 2025 Ralf Hagemeister
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_pptbook\event;

/**
 * Event class for viewing a PPT Book activity.
 *
 * @package   mod_pptbook
 * @category  event
 */
class course_module_viewed extends \core\event\course_module_viewed {
    /**
     * Init event data.
     */
    protected function init(): void {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'pptbook';
    }

    /**
     * Localised event name.
     */
    public static function get_name(): string {
        return get_string('eventcourse_module_viewed', 'mod_pptbook');
    }

    /**
     * Detailed description for logs.
     */
    public function get_description(): string {
        return "The user with id '{$this->userid}' viewed the PPT Book activity with the course module id '{
			$this->contextinstanceid
			}'.";
    }

    /**
     * Where this event relates to.
     */
    public function get_url(): \moodle_url {
        return new \moodle_url('/mod/pptbook/view.php', ['id' => $this->contextinstanceid]);
    }
}
