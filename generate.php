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

require("lib.php");

global $CFG;
$themedir = '.';
$themedir = '/var/www/themes/bootstrap_renderers';
$less_variables = array(
    'swatch' => "'guppy'",
    'navbarMargin' => '40px',
    'php_fontAwesomePath' => '"[[pix:theme|font]]"',
    'iconColor' => 'inherit',
    'iconOpacity' => '1',
    'php_iconSpritePath' => '"[[pix:theme|glyphicons-halflings]]"',
    'php_iconWhiteSpritePath' => '"[[pix:theme|glyphicons-halflings-white]]"',
    'php_horizontalComponentOffset' => '200px',
);
$import_dirs[] = "$themedir/style/glyphicons";
$import_dirs[] = "$themedir/style";
echo compile(less_input('guppy', true), $less_variables, $import_dirs);
