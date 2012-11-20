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
 * HTML attribute utility functions
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class attributes {

    private static $booleans = array(
        'autoplay',
        'checked',
        'disabled',
        'readonly',
        'required',
        'selected',
    );

    public static function one($name, $value) {
        if (is_array($value)) {
            throw new coding_exception("Passed an array for the HTML attribute $name");
        }
        if (strpos($name, " ")!==false) {
            throw new coding_exception("Attribute names can't have spaces in them like \"$name\"");
        }
        if ($value === null) {
            return '';
        }
        if ($name === $value && in_array($name, self::$booleans)) {
            return $name;
        }
        $value = htmlspecialchars($value);
        if (strpbrk($value, "= '") !== false || $value === '') {
            $value = '"'.$value.'"';
        }
        return "$name=$value";
    }
    public static function href_then_alphabetical($left, $right) {
        if ($left === 'href') {
            return -1;
        } else if ($right === 'href') {
            return 1;
        } else {
            return strcmp($left, $right);
        }
    }

    public static function all($attributes) {
        $sort_function = array("attributes", "href_then_alphabetical");
        uksort($attributes, $sort_function);
        foreach ($attributes as $name => $value) {
            $output[] = self::one($name, $value);
        }
        if (isset($output)) {
            return ' ' . implode(' ', $output);
        } else {
            return '';
        }
    }

}
