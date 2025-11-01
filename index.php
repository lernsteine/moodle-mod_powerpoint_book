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
$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_login($course);

$context = \context_course::instance($course->id);
$event = \mod_pptbook\event\course_module_instance_list_viewed::create([
    'context' => $context,
]);
$event->add_record_snapshot('course', $course);
$event->trigger();

$PAGE->set_url('/mod/pptbook/index.php', ['id' => $courseid]);
$PAGE->set_title(get_string('modulenameplural', 'mod_pptbook'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulenameplural', 'mod_pptbook'));

if (!$pptbooks = get_all_instances_in_course('pptbook', $course)) {
    notice(
        get_string('noinstances', 'mod_pptbook'),
        new moodle_url('/course/view.php', ['id' => $courseid])
    );
    exit;
}

$table = new html_table();
$table->head = [get_string('name'), get_string('introcol', 'mod_pptbook')];
foreach ($pptbooks as $m) {
    $link = html_writer::link(new moodle_url(
        '/mod/pptbook/view.php',
        ['id' => $m->coursemodule]
    ), format_string($m->name));
    $table->data[] = [$link, format_string($m->intro)];
}
echo html_writer::table($table);

echo $OUTPUT->footer();
