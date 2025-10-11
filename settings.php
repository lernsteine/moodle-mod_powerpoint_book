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
 * Admin settings for the PPT Book activity.
 *
 * @package   mod_pptbook
 * @category  admin
 * @copyright 2025 Ralf Hagemeister
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create the settings page (empty for now; add options here if needed).
    $settings = new admin_settingpage('modsettingpptbook', get_string('pluginname', 'mod_pptbook'));

    // Example for future options:
    // $settings->add(new admin_setting_configcheckbox(
    //     'mod_pptbook/someflag',
    //     get_string('someflag', 'mod_pptbook'),
    //     get_string('someflag_desc', 'mod_pptbook'),
    //     0
    // ));

    $ADMIN->add('modsettings', $settings);
}
