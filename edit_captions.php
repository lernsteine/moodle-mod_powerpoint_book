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

require('../../config.php');
require_once(__DIR__ . '/locallib.php');
require_once(__DIR__ . '/edit_captions_form.php');

$cmid = optional_param('cmid', 0, PARAM_INT);
$id   = optional_param('id', 0, PARAM_INT);
// Fallback
if (!$cmid && $id) {
    $cmid = $id;
}
if (!$cmid) {
    print_error('invalidcoursemodule');
}

$cm = get_coursemodule_from_id('pptbook', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$pptbook = $DB->get_record('pptbook', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/pptbook:manage', $context);

$PAGE->set_url('/mod/pptbook/edit_captions.php', ['cmid' => $cm->id, 'id' => $cm->id]);
$PAGE->set_title(get_string('editcaptions', 'mod_pptbook'));
$PAGE->set_heading(format_string($course->fullname));

$slides = pptbook_get_slide_files($context);
usort($slides, function ($a, $b) {
    return strnatcasecmp($a->get_filename(), $b->get_filename());
});

$existing = pptbook_get_captions($pptbook);
$filesmap = [];
foreach ($slides as $f) {
    $fn = $f->get_filename();
    $filesmap[$fn] = $existing[$fn] ?? '';
}

// Action-URL includes cmid & id.
$action = new moodle_url('/mod/pptbook/edit_captions.php', ['cmid' => $cm->id, 'id' => $cm->id]);
$mform  = new mod_pptbook_edit_captions_form($action, ['files' => $filesmap, 'cmid' => $cm->id]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/pptbook/view.php', ['id' => $cm->id]));
} else if ($data = $mform->get_data()) {
    require_sesskey();

    // CM & Instanz.
    $postcmid = optional_param('cmid', 0, PARAM_INT);
    $postid   = optional_param('id', 0, PARAM_INT);
    if (!$postcmid && $postid) {
        $postcmid = $postid;
    }

    $postcm = get_coursemodule_from_id('pptbook', $postcmid ?: $cm->id, 0, false, MUST_EXIST);
    $postpptbook = $DB->get_record('pptbook', ['id' => $postcm->instance], '*', MUST_EXIST);

    $captions = $data->caption ?? [];
    $json = json_encode($captions, JSON_UNESCAPED_UNICODE);

    // Field based Update to prvide problems.
    $DB->set_field('pptbook', 'captionsjson', $json, ['id' => $postpptbook->id]);
    $DB->set_field('pptbook', 'timemodified', time(), ['id' => $postpptbook->id]);

    redirect(new moodle_url('/mod/pptbook/view.php', ['id' => $postcm->id]), get_string('captionssaved', 'mod_pptbook'), 2);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editcaptions', 'mod_pptbook'));
$mform->display();
echo $OUTPUT->footer();
