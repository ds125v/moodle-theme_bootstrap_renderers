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
 * Settings page
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $themes = array(
        'bootstrap' => 'Bootstrap',
        /*'random' => 'Random',
        'amelia' => 'Amelia',
        'bootstrap' => 'Bootstrap',
        'cerulean' => 'Cerulean',
        'cosmo' => 'Cosmo',
        'cyborg' => 'Cyborg',
        'guppy' => 'Guppy',
        'journal' => 'Journal',
        'readable' => 'Readable',
        'simplex' => 'Simplex',
        'slate' => 'Slate',
        'spacelab' => 'Spacelab',
        'spruce' => 'Spruce',
        'superhero' => 'Superhero',
        'united' => 'United',
        */
    );

    // Sub Theme Dropdown.
    $name = 'theme_bootstrap_renderers/subtheme';
    $title = get_string('subtheme', 'theme_bootstrap_renderers');
    $description = get_string('subthemedesc', 'theme_bootstrap_renderers');
    $default = 'bootstrap';
    $choices = $themes;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
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

}
