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
 * [Short description of the file]
 *
 * @package    mod_pptbook
 * @copyright  2025 Ralf Hagemeister <ralf.hagemeister@lernsteine.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class mod_pptbook.
 */

defined('MOODLE_INTERNAL') || die();

function pptbook_get_slide_files(context_module $context): array {
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_pptbook', 'slides', 0, 'filename', false);
    $slides = [];
    foreach ($files as $f) {
        $name = strtolower($f->get_filename());
        if (substr($name, -4) === '.png') {
            $slides[] = $f;
        }
    }
    return $slides;
}

function pptbook_get_captions($record): array {
    if (!empty($record->captionsjson)) {
        $decoded = json_decode($record->captionsjson, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    return [];
}
