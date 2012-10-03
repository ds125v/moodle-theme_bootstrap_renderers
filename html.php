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
 * HTML utility functions
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class html {
    // HTML utility functions.

    private static function tag($tagname, $attributes, $contents) {
        return self::start_tag($tagname, $attributes) . $contents . self::end_tag($tagname);
    }

    private static function start_tag($tagname, array $attributes = null) {
        return "<$tagname" . self::attributes($attributes) . '>';
    }

    private static function end_tag($tagname) {
        return "</$tagname>";
    }

    private static function empty_tag($tagname, array $attributes = null) {
        return self::start_tag($tagname, $attributes);
    }

    private static function attribute($name, $value) {
        if (is_array($value)) {
            debugging("Passed an array for the HTML attribute $name", DEBUG_DEVELOPER);
        }
        if ($value === null) {
            return '';
        }
        if ($value instanceof moodle_url) {
            $value = $value->out();
        }

        $value = htmlspecialchars($value);
        if (strpos($value, " ")) {
            $value = '"'.$value.'"';
        }
        return " $name=$value";
    }

    private static function attributes($attributes) {
        $output = array();
        foreach ($attributes as $name => $value) {
            $output[] = self::attribute($name, $value);
        }
        return implode($output);
    }

    private static function classy_tag($tag, $attributes, $content = null) {
        if (is_string($attributes)) {
            if ($attributes === '') {
                $attributes = array();
            } else {
                $attributes = array('class'=>$attributes);
            }
        }
        if ($content === null) {
            return self::empty_tag($tag, $attributes);
        }
        return self::tag($tag, $attributes, $content);
    }

    public static function a($attributes, $content) {
        return self::classy_tag('a', $attributes, $content);
    }

    public static function div($attributes, $content) {
        return self::classy_tag('div', $attributes, $content);
    }

    public static function span($attributes, $content) {
        return self::classy_tag('span', $attributes, $content);
    }

    public static function p($attributes, $content) {
        return self::classy_tag('p', $attributes, $content);
    }

    public static function abbr($attributes, $content) {
        return self::classy_tag('abbr', $attributes, $content);
    }

    public static function form($attributes, $content) {
        return self::classy_tag('form', $attributes, $content);
    }
    public static function submit($attributes) {
        $attributes['type'] = 'submit';
        return self::classy_tag('input', $attributes, null);
    }

    public static function ul($attributes, $content) {
        return self::classy_tag('ul', $attributes, $content);
    }
    public static function li($attributes, $content) {
        return self::classy_tag('li', $attributes, $content);
    }
    public static function li_implode($items, $glue='</li><li>') {
        return '<li>'.implode($glue, $items).'</li>';
    }

    public static function hidden_inputs($params) {
        foreach ($params as $name => $value) {
            $output[] = self::input_hidden($name, $value);
        }
        return implode($output);
    }

    public static function input_hidden($name, $value) {
        $attributes['type'] = 'hidden';
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return self::classy_tag('input', $attributes, null);
    }

    public static function add_classes($current, $new) {
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
        throw new coding_exception('The $current param to html::add_classes must be either a string or array of attributes.');
    }

    public static function add_classes_string($current, $new) {
        $current = explode(' ', $current);
        $new = explode( ' ', $new);
        $merged = array_unique(array_merge($current, $new));
        sort($merged);

        return implode(' ', $merged);
    }

}
