<?php
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
