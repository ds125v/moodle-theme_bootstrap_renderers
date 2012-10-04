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
 * renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('classes.php');

class moodle {
    // Moodle utility functions. TODO: think of a better name.
    //
    static public $icons = array(
            'docs' => 'question-sign',
            'book' => 'book',
            'chapter' => 'file',
            'spacer' => 'spacer',
            'generate' => 'gift',
            'add' => 'plus',
            't/hide' => 'eye-open',
            'i/hide' => 'eye-open',
            't/show' => 'eye-close',
            'i/show' => 'eye-close',
            't/add' => 'plus',
            't/right' => 'arrow-right',
            't/left' => 'arrow-left',
            't/up' => 'arrow-up',
            't/down' => 'arrow-down',
            't/edit' => 'edit',
            't/editstring' => 'tag',
            't/delete' => 'remove',
            'i/edit' => 'pencil',
            't/copy' => 'copy', // Only in font awesome.
            'i/settings' => 'list-alt',
            'i/grades' => 'grades',
            'i/group' => 'user',
            't/switch_plus' => 'plus-sign',
            't/switch_minus' => 'minus-sign',
            'i/filter' => 'filter',
            't/move' => 'resize-vertical',
            'i/move_2d' => 'move',
            'i/backup' => 'cog',
            'i/restore' => 'cog',
            'i/return' => 'repeat',
            'i/reload' => 'refresh',
            'i/roles' => 'user',
            'i/user' => 'user',
            'i/users' => 'user',
            'i/publish' => 'publish',
            'i/navigationitem' => 'chevron-right' );


    public static function icon($name) {
        return bootstrap::icon(self::$icons[$name]);
    }
}

require_once('core_renderer.php');
require_once('core_admin_renderer.php');
require_once('blocks_navigation_renderer.php');
require_once('blocks_settings_renderer.php');

