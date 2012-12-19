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
 * HTML utility functions for creating labels, by which we mean
 * Bootstrap style short text markers, rather than HTML form labels
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('bootstrap.php');

class label {
    public static function make($type, $text) {
        if ($type != '') {
            $type = ' label-' . $type;
        }
        // Bootstrap label classes can be added to other things
        // but are usually spans (or a tags for clickable links).
        return "<span class=\"label$type\">$text</span>";
    }
    public static function success($text) {
        return self::make('success', $text);
    }
    public static function warning($text) {
        return self::make('warning', $text);
    }
    public static function important($text) {
        return self::make('important', $text);
    }
    public static function info($text) {
        return self::make('info', $text);
    }
    public static function inverse($text) {
        return self::make('inverse', $text);
    }
    public static function yes() {
        return self::success(bootstrap::icon('ok') .' '. get_string('yes'));
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function no() {
        return self::important(bootstrap::icon('remove') . ' ' . get_string('no'));
    }
}
