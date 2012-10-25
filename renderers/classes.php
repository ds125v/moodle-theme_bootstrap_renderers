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
 * Tests for HTML utility functions
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class classes {
    public static function add($current, $new) {
        if (is_string($current)) {
            return self::add_classes_string($current, $new);
        }
        if (is_array($current)) {
            if (!isset($current['class'])) {
                $current['class'] = '';
            }
            $current['class'] =  self::add_classes_string($current['class'], $new);
            return $current;
        }
        throw new coding_exception('The $current param to classes::add must be either a string or array of attributes.');
    }

    private static function add_classes_string($current, $new) {
        $current = explode(' ', $current);
        $new = explode( ' ', $new);
        $merged = array_unique(array_merge($current, $new));
        sort($merged);

        return trim(implode(' ', $merged));
    }
    public static function replace($current, $new) {
        if (is_string($current)) {
            return self::replace_classes($current, $new);
        }
        if (is_array($current)) {
            if (!isset($current['class'])) {
                $current['class'] = '';
            }
            $current['class'] =  self::replace_classes($current['class'], $new);
            return $current;
        }
        throw new coding_exception('The $current param to classes::replace must be either a string or array of attributes.');
    }

    private static function replace_classes($current, $new) {
        $current_array = explode(' ', $current);
        $found = array();
        foreach ($new as $find => $replace) {
            if (in_array($find, $current_array)) {
                $found[] = $find;
                $to_add[] = $replace;
            }
        }
        if ($found) {
            return trim(implode(' ', array_merge(array_diff($current_array, $found), $to_add)));
        } else {
            return $current;
        }
    }
}
