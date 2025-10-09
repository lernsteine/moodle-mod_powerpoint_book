<?php
require('../../config.php');
require_once(__DIR__.'/locallib.php');

$id = required_param('id', PARAM_INT);
$page = optional_param('page', 1, PARAM_INT);
$perpage = 4;

$cm = get_coursemodule_from_id('pptbook', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$pptbook = $DB->get_record('pptbook', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/pptbook:view', $context);

$PAGE->set_url('/mod/pptbook/view.php', ['id' => $id, 'page' => $page]);
$PAGE->set_title(format_string($pptbook->name));      // bleibt als Browser-Titel ok
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->requires->css(new moodle_url('/mod/pptbook/styles.css'));
$PAGE->requires->js_call_amd('mod_pptbook/lightbox', 'init');

// Activity-Header-Inhalte (Titel/Intro) vorsorglich deaktivieren.
if (isset($PAGE->activityheader) && method_exists($PAGE->activityheader, 'set_attrs')) {
    $PAGE->activityheader->set_attrs([
        'title' => '',
        'subtitle' => '',
        'description' => '',
        'hasintro' => false,
        'hidecompletion' => true
    ]);
}

echo $OUTPUT->header();
// KEIN echo $OUTPUT->heading(...);
// KEINE Intro-Box hier

$slides = pptbook_get_slide_files($context);
$total = count($slides);
if ($total === 0) {
    echo $OUTPUT->notification(get_string('noimages', 'mod_pptbook'), 'warning');
    echo $OUTPUT->footer();
    exit;
}

// Sort by natural filename order.
usort($slides, function($a, $b) { return strnatcasecmp($a->get_filename(), $b->get_filename()); });

$pages = max(1, (int)ceil($total / $perpage));
$page = max(1, min($page, $pages));
$start = ($page - 1) * $perpage;
$current = array_slice($slides, $start, $perpage);

$captions = pptbook_get_captions($pptbook);

$items = [];
foreach ($current as $f) {
    $filename = $f->get_filename();
    $url = moodle_url::make_pluginfile_url($f->get_contextid(), $f->get_component(), $f->get_filearea(),
        $f->get_itemid(), $f->get_filepath(), $filename, false);
    $items[] = (object)[
        'filename' => $filename,
        'imgurl' => (string)$url,
        'fullurl' => (string)$url,
        'caption' => $captions[$filename] ?? ''
    ];
}

$manageurl = null;
if (has_capability('mod/pptbook:manage', $context)) {
    $manageurl = new moodle_url('/mod/pptbook/edit_captions.php', ['cmid'=>$cm->id, 'id'=>$cm->id, 'page'=>$page]);
}

$templatecontext = (object)[
    'items'   => $items,
    'page'    => $page,
    'pages'   => $pages,
    'hasprev' => $page > 1,
    'hasnext' => $page < $pages,
    'preurl'  => (new moodle_url('/mod/pptbook/view.php', ['id' => $cm->id, 'page' => $page - 1]))->out(false),
    'nexturl' => (new moodle_url('/mod/pptbook/view.php', ['id' => $cm->id, 'page' => $page + 1]))->out(false),
    'manageurl' => $manageurl ? $manageurl : null,
    'manage' => !empty($manageurl)
];

echo $OUTPUT->render_from_template('mod_pptbook/page', $templatecontext);
echo $OUTPUT->footer();
