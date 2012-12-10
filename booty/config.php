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
 * Configuration for Moodle's standard theme.
 *
 * This theme is the default theme within Moodle 2.0, it builds upon the base theme
 * adding only CSS to create the simple look and feel Moodlers have come to recognise.
 *
 * For full information about creating Moodle themes, see:
 *  http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   moodlecore
 * @copyright 2010 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'booty';
$THEME->parents = array();
$THEME->sheets = array( 'booty');
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->javascripts_footer = array('jquery', 'bootstrap');
$THEME->doctype = 'html5';
$basic = array(
    'file' => 'general.php',
);
$normal = $basic + array(
    'regions' => array('side-pre', 'side-post'),
    'defaultregion' => 'side-post',
);
$one_column = $basic + array(
    'regions' => array('side-pre'),
    'defaultregion' => 'side-pre',
);
$plain = $basic + array(
    'options' => array(
        'noblocks'=>true,
        'nofooter'=>true,
        'nonavbar'=>false,
        'nocustommenu'=>true,
    ),
);

$THEME->layouts = array(
    'admin' => $one_column,
    'base' => $basic,
    'course' => $normal,
    'coursecategory' => $normal,
    'embedded' => $basic + array('options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true)),
    'frametop' => $basic + array('options' => array('nofooter'=>true)),
    'frontpage' => $normal,
    'incourse' => $normal,
    'login' => $basic,
    'maintenance' => $plain,
    'mydashboard' => $normal,
    'mypublic' => $normal,
    'popup' => $basic + array('options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologininfo'=>true)),
    'print' => $plain,
    'redirect' => $plain,
    'report' => $one_column,
    'standard' => $normal,
);
