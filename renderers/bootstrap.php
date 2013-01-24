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
 * Bootstrap utility functions.
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('html.php');

class bootstrap {

    public static function icon($name) {
        if (isset($name) && $name != '') {
            return "<i class=glyphicon-$name></i>";
        } else {
            return '';
        }
    }
    public static function icon_white($name) {
        if (isset($name) && $name != '') {
            return "<i class=\"icon-$name icon-white\"></i>";
        } else {
            return '';
        }
    }
    public static function icon_help() {
        return self::icon('question-sign');
    }
    public static function icon_spacer() {
        return self::icon('spacer');
        // No actual spacer icon provided by bootstrap, but magically it still works.
    }


    public static function button($text) {
        return "<button class=btn type=button>$text</button>";
    }
    public static function badge($type, $text) {
        if ($type != '') {
            $type = ' badge-' . $type;
        }
        // Bootstrap badge classes can be added to other things
        // but are usually spans (or a tags for clickable links).
        return "<span class=\"badge$type\">$text</i>";
    }
    public static function badge_default($text) {
        return self::badge('', $text);
    }
    public static function badge_success($text) {
        return self::badge('success', $text);
    }
    public static function badge_warning($text) {
        return self::badge('warning', $text);
    }
    public static function badge_important($text) {
        return self::badge('important', $text);
    }
    public static function badge_info($text) {
        return self::badge('info', $text);
    }
    public static function badge_inverse($text) {
        return self::badge('inverse', $text);
    }

    public static function alert($type, $text, $close=false) {
        if ($type != '') {
            $type = ' alert-' . $type;
        }
        if ($close === true) {
            $button = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            $text = $button.$text;
        }
        return "<div class=\"alert$type\">$text</div>";
    }
    public static function alert_default($text, $close=false) {
        return self::alert('', $text, $close);
    }
    public static function alert_success($text, $close=false) {
        return self::alert('success', $text, $close);
    }
    public static function alert_error($text, $close=false) {
        return self::alert('error', $text, $close);
    }
    public static function alert_info($text, $close=false) {
        return self::alert('info', $text, $close);
    }
    public static function alert_block($text, $close=false) {
        return self::alert('block', $text, $close);
    }
    public static function alert_block_info($text, $close=false) {
        return self::alert('block alert-info', $text, $close);
    }

    public static function initialism($short, $full) {
        $attributes['class'] = 'initialism';
        $attributes['title'] = $full;
        return html::abbr($attributes, $short);
    }

    public static function ul_unstyled($items) {
        return html::ul('unstyled', $items);
    }
    public static function pagination($items) {
        return html::ul('pagination', $items);
    }
    public static function breadcrumb($items) {
        return html::ul('breadcrumb', $items);
    }

    public static function dropdown($text, $content) {
        return html::li('dropdown',
            html::a(array('class'=>'dropdown-toggle', 'href'=>'#', 'data-toggle'=>'dropdown'),
            "$text <b class=caret></b>") . $content);
    }
    public static function dropdown_menu($text, $content) {
                return self::dropdown($text, self::menu($content));
    }
    private static function menu($items) {
        $attributes = array('class'=>'dropdown-menu', 'role'=>'menu', 'aria-labelledby'=>'dropdownMenu');
        return html::ul($attributes, $items);
    }
    public static function dropdown_submenu($text, $content) {
        return html::li('dropdown-submenu', html::a('#', $text) . self::menu($content));
    }
    public static function list_divider() {
        return html::li(array('class'=>'divider'));
    }
    public static function icon_link($href, $icon, $text) {
        return html::a($href, self::icon($icon) . " $text");
    }
    public static function li_icon_link($href, $icon, $text) {
        return html::li(self::icon_link($href, $icon, $text));
    }
    /**
     * This is the only function in the class with knowledge of Moodle,
     * only because I've got nowhere else to put it.
     */
    public static function replace_moodle_icon($name) {
        $icons = array(
            'add' => 'plus',
            'book' => 'book',
            'chapter' => 'file',
            'docs' => 'question-sign',
            'generate' => 'gift',
            'i/backup' => 'download',
            'i/checkpermissions' => 'user',
            'i/edit' => 'pencil',
            'i/filter' => 'filter',
            'i/grades' => 'grades',
            'i/group' => 'user',
            'i/hide' => 'eye-open',
            'i/import' => 'upload',
            'i/info' => 'info',
            'i/move_2d' => 'move',
            'i/navigationitem' => 'chevron-right',
            'i/publish' => 'publish',
            'i/reload' => 'refresh',
            'i/report' => 'list-alt',
            'i/restore' => 'upload',
            'i/return' => 'repeat',
            'i/roles' => 'user',
            'i/settings' => 'cog',
            'i/show' => 'eye-close',
            'i/switchrole' => 'user',
            'i/user' => 'user',
            'i/users' => 'user',
            'spacer' => 'spacer',
            't/add' => 'plus',
            't/copy' => 'plus-sign',
            't/delete' => 'remove',
            't/down' => 'arrow-down',
            't/edit' => 'edit',
            't/editstring' => 'tag',
            't/hide' => 'eye-open',
            't/left' => 'arrow-left',
            't/move' => 'resize-vertical',
            't/right' => 'arrow-right',
            't/show' => 'eye-close',
            't/switch_minus' => 'minus-sign',
            't/switch_plus' => 'plus-sign',
            't/up' => 'arrow-up',
        );
        if (isset($icons[$name])) {
            return self::icon($icons[$name]);
        } else {
            return false;
        }
    }
}
