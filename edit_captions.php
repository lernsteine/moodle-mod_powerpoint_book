<?php
require('../../config.php');
require_once(__DIR__.'/locallib.php');
require_once(__DIR__.'/edit_captions_form.php');

$cmid = optional_param('cmid', 0, PARAM_INT);
$id   = optional_param('id', 0, PARAM_INT);
// Fallback: Wenn nur id gesetzt ist, verwende sie als cmid (wir erwarten hier CM-ID, nicht Instanz-ID!)
if (!$cmid && $id) { $cmid = $id; }
if (!$cmid) {
    print_error('invalidcoursemodule');
}

$cm = get_coursemodule_from_id('pptbook', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$pptbook = $DB->get_record('pptbook', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/pptbook:manage', $context);

$PAGE->set_url('/mod/pptbook/edit_captions.php', ['cmid'=>$cm->id, 'id'=>$cm->id]);
$PAGE->set_title(get_string('editcaptions', 'mod_pptbook'));
$PAGE->set_heading(format_string($course->fullname));

$slides = pptbook_get_slide_files($context);
usort($slides, function($a,$b){ return strnatcasecmp($a->get_filename(), $b->get_filename()); });

$existing = pptbook_get_captions($pptbook);
$filesmap = [];
foreach ($slides as $f) {
    $fn = $f->get_filename();
    $filesmap[$fn] = $existing[$fn] ?? '';
}

// Action-URL enthält cmid & id (identisch, beides CM-ID), um Router/Proxys robust zu bedienen.
$action = new moodle_url('/mod/pptbook/edit_captions.php', ['cmid'=>$cm->id, 'id'=>$cm->id]);
$mform  = new mod_pptbook_edit_captions_form($action, ['files'=>$filesmap, 'cmid'=>$cm->id]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/pptbook/view.php', ['id'=>$cm->id]));
} else if ($data = $mform->get_data()) {
    require_sesskey();

    // CM & Instanz aus POST neu herleiten (cmid hat Vorrang, id ist Spiegel)
    $postcmid = optional_param('cmid', 0, PARAM_INT);
    $postid   = optional_param('id', 0, PARAM_INT);
    if (!$postcmid && $postid) { $postcmid = $postid; }

    $postcm = get_coursemodule_from_id('pptbook', $postcmid ?: $cm->id, 0, false, MUST_EXIST);
    $postpptbook = $DB->get_record('pptbook', ['id' => $postcm->instance], '*', MUST_EXIST);

    $captions = $data->caption ?? [];
    $json = json_encode($captions, JSON_UNESCAPED_UNICODE);

    // Feldbasiertes Update vermeidet Probleme mit vollständigen Record-Objekten
    $DB->set_field('pptbook', 'captionsjson', $json, ['id' => $postpptbook->id]);
    $DB->set_field('pptbook', 'timemodified', time(), ['id' => $postpptbook->id]);

    redirect(new moodle_url('/mod/pptbook/view.php', ['id'=>$postcm->id]), get_string('captionssaved', 'mod_pptbook'), 2);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editcaptions', 'mod_pptbook'));
$mform->display();
echo $OUTPUT->footer();