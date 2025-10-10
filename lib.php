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

function pptbook_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return false;  // no course intro
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

function pptbook_add_instance($data, $mform) {
    global $DB;
    $data->timecreated = time();
    $data->timemodified = time();

    if (!empty($data->captionsjson) && is_array($data->captionsjson)) {
        $data->captionsjson = json_encode($data->captionsjson, JSON_UNESCAPED_UNICODE);
    } else if (empty($data->captionsjson)) {
        $data->captionsjson = null;
    }

    $id = $DB->insert_record('pptbook', $data);
    pptbook_save_slides_files($id, $data, $data->coursemodule ?? null);
    return $id;
}

function pptbook_update_instance($data, $mform) {
    global $DB;
    $data->id = $data->instance;
    $data->timemodified = time();

    if (!empty($data->captionsjson) && is_array($data->captionsjson)) {
        $data->captionsjson = json_encode($data->captionsjson, JSON_UNESCAPED_UNICODE);
    }

    $DB->update_record('pptbook', $data);
    pptbook_save_slides_files($data->id, $data, $data->coursemodule ?? null);
    return true;
}

function pptbook_delete_instance($id) {
    global $DB;
    if (!$pptbook = $DB->get_record('pptbook', ['id' => $id])) {
        return false;
    }
    $cm = get_coursemodule_from_instance('pptbook', $id);
    $context = context_module::instance($cm->id);
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'mod_pptbook', 'slides');

    $DB->delete_records('pptbook_item', ['pptbookid' => $pptbook->id]);
    $DB->delete_records('pptbook', ['id' => $pptbook->id]);
    return true;
}

function pptbook_save_slides_files($instanceid, $data, $cmid = null) {
    global $CFG;
    require_once($CFG->libdir . '/filelib.php');

    // Prefer explicit $cmid if given; otherwise use $data->coursemodule during add/update.
    if (empty($cmid)) {
        $cmid = $data->coursemodule ?? null;
    }
    if (empty($cmid)) {
        try {
            $cm = get_coursemodule_from_instance('pptbook', $instanceid, 0, false, IGNORE_MISSING);
            if ($cm) {
                $cmid = $cm->id;
            }
        } catch (\Exception $e) {
            $cmid = null;
        }
    }
    if (empty($cmid)) {
        // Defer saving files; file manager keeps draft files; update_instance will handle it.
        return;
    }
    $context = context_module::instance($cmid);
    file_save_draft_area_files(
        $data->slides_filemanager ?? 0,
        $context->id,
        'mod_pptbook',
        'slides',
        0,
        ['subdirs' => 0, 'maxfiles' => -1, 'accepted_types' => ['.png']]
    );
}


function pptbook_get_coursemodule_info($cm) {
    global $DB;
    if ($pptbook = $DB->get_record('pptbook', ['id' => $cm->instance], 'id, name')) {
        $result = new cached_cm_info();
        $result->name = $pptbook->name; // kannst du auch weglassen – siehe CSS unten
        // KEIN $result->content mehr
        return $result;
    }
    return null;
}

/**
 * File serving callback.
 */
function pptbook_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    require_login($course, true, $cm);
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }
    if ($filearea !== 'slides') {
        return false;
    }
    $fs = get_file_storage();
    $itemid = 0;
    $filepath = '/';
    $filename = array_pop($args);
    if (!$file = $fs->get_file($context->id, 'mod_pptbook', 'slides', $itemid, $filepath, $filename)) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
