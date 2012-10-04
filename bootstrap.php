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
 * HTML utility functions for Bootstrap
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');

class bootstrap {
    // Bootstrap utility functions.

    public static function ul_implode_li($attributes, $items, $glue='</li><li>') {
        return html::ul($attributes, html::li_implode($items, $glue));
    }
    public static function ul_implode($attributes, $items) {
        return html::ul($attributes, implode($items));
    }
    public static function icon($name) {
        return "<i class=icon-$name></i>";
    }
    public static function icon_help() {
        return self::icon('question-sign');
    }
    public static function icon_spacer() {
        return self::icon('spacer');
        // No actual spacer icon provided by bootstrap, but magically it still works.
    }

    public static function label($type, $text) {
        if ($type != '') {
            $type = ' label-' . $type;
        }
        // Bootstrap label classes can be added to other things
        // but are usually spans (or a tags for clickable links).
        return "<span class=\"label$type\">$text</i>";
    }
    public static function label_default($text) {
        return self::label('', $text);
    }
    public static function label_success($text) {
        return self::label('success', $text);
    }
    public static function label_warning($text) {
        return self::label('warning', $text);
    }
    public static function label_important($text) {
        return self::label('important', $text);
    }
    public static function label_info($text) {
        return self::label('info', $text);
    }
    public static function label_inverse($text) {
        return self::label('inverse', $text);
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

    public static function alert($type, $text) {
        if ($type != '') {
            $type = ' alert-' . $type;
        }
        return "<div class=\"alert$type\">$text</div>";
    }
    public static function alert_default($text) {
        return self::alert('', $text);
    }
    public static function alert_success($text) {
        return self::alert('success', $text);
    }
    public static function alert_error($text) {
        return self::alert('error', $text);
    }
    public static function alert_info($text) {
        return self::alert('info', $text);
    }
    public static function alert_block($text) {
        return self::alert('block', $text);
    }
    public static function alert_block_info($text) {
        return self::alert('block alert-info', $text);
    }

    public static function initialism($short, $full) {
        $attributes['class'] = 'initialism';
        $attributes['title'] = $full;
        return html::abbr($attributes, $short);
    }

    public static function ul_unstyled($items) {
        return html::ul('unstyled', $items);
    }
    // TODO: some ideas for bits not done yet.
    //
    // Bootstrap inline search form ($name, $method, $action, $placeholder, $button_text)
}
