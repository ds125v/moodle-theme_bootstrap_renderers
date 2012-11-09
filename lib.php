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
    return less_compiler($css, $theme);
}
function less_compiler($css, $theme) {
    $less_marker = 'This file will be entirely replaced with the output of less compilation.';
    if (strpos($css, $less_marker) === false) {
        return $css;
    };
    global $CFG;

    $swatch = $theme->settings->subtheme;
    $responsive = $theme->settings->responsive;
    $awesome = $theme->settings->awesome;
    // TODO: add setting for padding between breadcrumb and fixed navbar.
    $extra_padding = 0;
    $padding = 0;
    $icon_color = 'inherit';
    $icon_opacity = 1;

    if ($swatch == 'random') {
        $colors = array('inherit', 'red', 'yellow', 'pink', 'purple', 'orange', 'blue', 'green');
        $color_key = array_rand($colors);
        $icon_color = $colors[$color_key];

        $opacities = array( 0.2, 0.4, 0.6, 0.8, 1);
        $opacity_key = array_rand($opacities);
        $icon_opacity = $colors[$opacity_key];

        $swatches = array('amelia', 'cerulean', 'journal', 'readable', 'simplex', 'slate', 'spacelab', 'spruce', 'superhero', 'united');
        $swatch = $swatches[array_rand($swatches)];

        $responsive = rand(0, 1);
        $awesome = rand(0, 1);
        $extra_padding = rand(0, 1);
        if ($extra_padding == 1) {
            $padding = 20;
        }
    }
    $cache_name = md5(serialize(array($swatch, $responsive, $awesome, $extra_padding, $icon_color, $icon_opacity)));
    $current_theme = current_theme();
    $cachedir = "$CFG->cachedir/theme/$current_theme";
    $cachefile = "$cachedir/$cache_name.css";

    if (file_exists($cachefile)) {
       return file_get_contents($cachefile);
    }

    $themedir = $theme->dir;
    $themewww = $current_theme;
    if (isset($CFG->themewww)) {
        $themewww = "$CFG->themewww/$current_theme";
    }

    $less = new lessc;
    $less->setVariables(array(
        'swatch' => "'$swatch'",
        'navbarMargin' => $padding,
        'php_fontAwesomePath' => "'$themewww/font'",
        'iconColor' => $icon_color,
        'iconOpacity' => $icon_opacity,
        'php_iconSpritePath' => "'$themewww/pix/glyphicons-halflings.png'",
        'php_iconWhiteSpritePath' => "'$themewww/pix/glyphicons-halflings-white.png'",
        'php_horizontalComponentOffset' => '200px',
    ));

    if ($awesome) {
        $import_dirs[] = "$themedir/style/font-awesome";
    } else {
        $import_dirs[] = "$themedir/style/glyphicons";
    }
    $import_dirs[] = "$themedir/style";
    $less->setImportDir($import_dirs);

    $less_input = '';
    $less_input .= '@import "bootstrap/less/bootstrap.less";';
    $less_input .= '@media (min-width: 981px) {body.navbar-fixed-top-padding {padding-top: @navbarHeight + @navbarMargin}};';
    if ($responsive) {
        $less_input .= '@import "bootstrap/less/responsive.less";';
    }
    if ($swatch != 'bootstrap') {
        $less_input .= '@import "@{swatch}/variables.less";';
        $less_input .= '@import "@{swatch}/bootswatch.less";';
        $less_input .= '@import "bootstrap/less/utilities.less";';
    }

    $less_input .= '@import "moodle/moodle.less";';
    $css = $less->compile($less_input);

    $search[] = 'fonts/';
    $replace[] = "$themewww/font/";
    $css = str_replace($search, $replace, $css);
    file_put_contents($cachefile, $css);
    return $css;
}
