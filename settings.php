<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $themes = array(
        'random' => 'Random',
        'amelia' => 'Amelia',
        'bootstrap' => 'Bootstrap',
        'cerulean' => 'Cerulean',
        'cyborg' => 'Cyborg',
        'journal' => 'Journal',
        'readable' => 'Readable',
        'simplex' => 'Simplex',
        'slate' => 'Slate',
        'spacelab' => 'Spacelab',
        'spruce' => 'Spruce',
        'superhero' => 'Superhero',
        'united' => 'United',
    );

    // Sub Theme Dropdown.
    $name = 'theme_bootstrap_renderers/subtheme';
    $title = get_string('subtheme', 'theme_bootstrap_renderers');
    $description = get_string('subthemedesc', 'theme_bootstrap_renderers');
    $default = 'random';
    $choices = $themes;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Fluid Layout.
    $name = 'theme_bootstrap_renderers/fluid';
    $title = get_string('fluid', 'theme_bootstrap_renderers');
    $description = get_string('fluiddesc', 'theme_bootstrap_renderers');
    $default = '1';
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    // Responsive Layout.
    $name = 'theme_bootstrap_renderers/responsive';
    $title = get_string('responsive', 'theme_bootstrap_renderers');
    $description = get_string('responsivedesc', 'theme_bootstrap_renderers');
    $default = '1';
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    // Fixed Navbar.
    $name = 'theme_bootstrap_renderers/fixed';
    $title = get_string('fixed', 'theme_bootstrap_renderers');
    $description = get_string('fixeddesc', 'theme_bootstrap_renderers');
    $default = '0';
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    // Invert Navigation Bar.
    $name = 'theme_bootstrap_renderers/navbarinvert';
    $title = get_string('navbarinvert', 'theme_bootstrap_renderers');
    $description = get_string('navbarinvertdesc', 'theme_bootstrap_renderers');
    $default = '0';
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    // Enable Awesome Font.
    $name = 'theme_bootstrap_renderers/awesome';
    $title = get_string('awesomefont', 'theme_bootstrap_renderers');
    $description = get_string('awesomefontdesc', 'theme_bootstrap_renderers');
    $default = '1';
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

}
