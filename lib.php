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

require("less/lessc.inc.php");

function processor($css, $theme) {
    $less_marker = 'This file will be entirely replaced with the output of less compilation.';
    if (strpos($css, $less_marker) !== false) {
        $css = str_replace($less_marker, less_compiler($theme), $css);
    }
    return $css;
}
function less_compiler($theme, $override=array()) {
    global $CFG;

    $swatch = $theme->settings->subtheme;
    $responsive = $theme->settings->responsive;

    $current_theme = current_theme();

    $themedir = $theme->dir;
    $themewww = $current_theme;
    if (isset($CFG->themewww)) {
        $themewww = "$CFG->themewww/$current_theme";
    }

    $import_dirs[] = "$themedir/style";

    $less_input = less_input($swatch);

    $less_variables = array();
    $output = compile($less_input, $less_variables, $import_dirs);

    $search[] = '../fonts/';
    $replace[] = "$themewww/pix/font/";
    $output = str_replace($search, $replace, $output);
    return $output;
}
function less_input($swatch) {
    $less_input = '@import "bootstrap/less/bootstrap.less";';
    if ($swatch != 'bootstrap') {
        $less_input .= '@import "@{swatch}/variables.less";';
        $less_input .= '@import "@{swatch}/bootswatch.less";';
        $less_input .= '@import "bootstrap/less/utilities.less";';
    }
    $less_input .= '@import "moodle/moodle.less";';
    return $less_input;
}
function compile($less_input, $less_variables, $import_dirs) {
    $less = new lessc;
    $less->setVariables($less_variables);
    $less->setImportDir($import_dirs);
    return $less->compile($less_input);
}
