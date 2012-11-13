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
 * HTML utility functions for creating tables. The basic concept here
 * is that you just want to create a 2D array of conten and then output
 * it. Rather than micro-manage the display via HTML attributes and classes
 * we should a) give up control and let the browser do it, since we have little
 * control over the length of UI text, due to translation issues, or content,
 * due to it being user data dependant or display size since it varies by device
 * and user preferences.
 *
 * For those cases when we absolutely must control display then we can either apply
 * that to the content, rather than the table cell itself, or use advanced CSS to 
 * target e.g. every other row, or the last table cell in each row 
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('html.php');

class table {

    public static function simple($attributes, $headers, $rows) {
        return html::table($attributes, self::thead($headers).self::tbody($rows));
    }

    public static function tbody($rows) {
        return '<tbody>'.self::tr_implode($rows).'</tbody>';
    }
    public static function thead($cells) {
        return '<thead><tr>'.self::th_implode($cells).'</tr></thead>';
    }
    public static function td_implode($cells) {
        if (is_string($cells) || $cells === null) {
            return $cells;
        }
        if (is_string($cells[0]) && strpos($cells[0], '<td') === 0) {
            return implode($cells);
        } else {
            return '<td>'.implode('</td><td>', $cells).'</td>';
        }
    }
    public static function th_implode($cells) {
        if (is_string($cells) || $cells === null) {
            return $cells;
        }
        if (strpos($cells[0], '<th') === 0) {
            return implode($cells);
        } else {
            return '<th>'.implode('</th><th>', $cells).'</th>';
        }
    }
    public static function tr_implode($rows) {
        if (!is_array($rows) || $rows === null) {
            return $rows;
        }
        if (is_string($rows[0]) && strpos($rows[0], '<tr') === 0) {
            return implode($rows);
        } else {
            foreach ($rows as $row) {
                $output[] = self::td_implode($row);
            }
            return '<tr>'.implode('</tr><tr>', $output).'</tr>';
        }
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function tr($attributes, $content=null) {
        if (func_num_args() === 1) {
            $content = $attributes;
            $attributes = array();
        }
        return html::tag('tr', $attributes, self::td_implode($content));
    }
}
