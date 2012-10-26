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
 * Theme lib php
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function processor($css, $theme) {

    global $CFG;

    $themes = array(
        'amelia' => '50',
        'bootstrap' => '40',
        'cerulean' => '50',
        'cyborg' => '40',
        'journal' => '60',
        'random' => '0',
        'readable' => '50',
        'simplex' => '40',
        'slate' => '40',
        'spacelab' => '40',
        'spruce' => '50',
        'superhero' => '70',
        'united' => '40',
    );

    $subtheme = $theme->settings->subtheme;
    $responsive = $theme->settings->responsive;
    $navbar_fixed = $theme->settings->fixed;
    $awesome = $theme->settings->awesome;
    $padding = $themes[$subtheme];

    if ($subtheme == 'random') {
        $subtheme = array_rand($themes);
        $padding = $themes[$subtheme];
        $responsive = rand(0, 1);
        $awesome = rand(0, 1);
        if (!empty($CFG->themedesignermode)) {
            $navbar_fixed = (floor($_SERVER['REQUEST_TIME'] / 100)) % 2;
            // TODO: adding setting for this.
            $extra_padding = rand(0, 1);
            if ($extra_padding == 1) {
                $padding += 20;
            }
        }
    }

    $themedir = $theme->dir;
    $themewww = current_theme();
    if (isset($CFG->themewww)) {
        $themewww = $CFG->themewww.'/'.current_theme();
    }

    $find[] = "[[bootstrap]]";
    $replace[] = file_get_contents("$themedir/style/$subtheme/bootstrap.css");

    $find[] = "[[fixed-nav-padding]]";
    // This needs to be between bootstrap and bootstrap-responsive.
    if ($navbar_fixed == 1) {
        $replace[] = "body {padding-top: {$padding}px;}";
    } else {
        $replace[] = '';
    }

    $find[] = "[[bootstrap-responsive]]";
    if ($responsive == 1) {
        $replace[] = file_get_contents("$themedir/style/bootstrap/bootstrap-responsive.css");
    } else {
        $replace[] = '';
    }

    $find[] = "[[font-awesome]]";
    if ($awesome == 1) {
        $fix = '[class*="icon-"] {background-image: none;}';
        $replace[] = file_get_contents("$themedir/style/font-awesome/font-awesome.css") . $fix;

        $find[] = "../font/fontawesome-webfont";
        $replace[] = "$themewww/font/fontawesome-webfont";
    } else {
        $replace[] = '';
    }

    $find[] = "../img/glyphicons-halflings";
    $replace[] = "$themewww/pix/glyphicons-halflings";

    return str_replace($find, $replace, $css);
}
