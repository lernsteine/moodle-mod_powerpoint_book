<?php
require('../../config.php');
$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$PAGE->set_url('/mod/pptbook/index.php', ['id' => $courseid]);
$PAGE->set_title(get_string('modulenameplural', 'mod_pptbook'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_pptbook'));

if (!$pptbooks = get_all_instances_in_course('pptbook', $course)) {
    notice(get_string('nonewmodules', '', get_string('modulename', 'pptbook')), new moodle_url('/course/view.php', ['id'=>$courseid]));
    exit;
}

$table = new html_table();
$table->head = [get_string('name'), get_string('intro')];
foreach ($pptbooks as $m) {
    $link = html_writer::link(new moodle_url('/mod/pptbook/view.php', ['id' => $m->coursemodule]), format_string($m->name));
    $table->data[] = [$link, format_string($m->intro)];
}
echo html_writer::table($table);

echo $OUTPUT->footer();
