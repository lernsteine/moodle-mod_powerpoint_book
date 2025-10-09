<?php
defined('MOODLE_INTERNAL') || die();
function xmldb_pptbook_upgrade($oldversion) {
    if ($oldversion < 2025100802) {
        upgrade_mod_savepoint(true, 2025100802, 'pptbook');
    }
    return true;
}
