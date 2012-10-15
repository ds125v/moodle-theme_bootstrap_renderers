<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $name = 'theme_bootstrap_renderers/notes';
    $heading = get_string('notes-heading', 'theme_bootstrap_renderers');
    $information = get_string('notes-information', 'theme_bootstrap_renderers');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
}