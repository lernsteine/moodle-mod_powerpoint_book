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
 * Local library functions for the PPT Book activity.
 *
 * @package    mod_pptbook
 * @copyright  2025 Ralf Hagemeister
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns all PNG slide files stored in the module file area.
 *
 * @param context_module $context The activity context.
 * @return stored_file[] List of slide files (PNG only).
 * @package   mod_pptbook
 */
function pptbook_get_slide_files(context_module $context): array {
    $fs = get_file_storage();
    $files = $fs->get_area_files(
        $context->id,
        'mod_pptbook',
        'slides',
        0,
        'filename',
        false
    );

    $slides = [];
    foreach ($files as $f) {
        $name = strtolower($f->get_filename());
        if (substr($name, -4) === '.png') {
            $slides[] = $f;
        }
    }

    return $slides;
}

/**
 * Decodes and returns the captions map from a PPT Book record.
 *
 * @param stdClass $record The PPT Book database record containing 'captionsjson'.
 * @return array Captions indexed by filename or slide key.
 * @package mod_pptbook
 */
function pptbook_get_captions($record): array {
    if (!empty($record->captionsjson)) {
        $decoded = json_decode($record->captionsjson, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }

    return [];
}
