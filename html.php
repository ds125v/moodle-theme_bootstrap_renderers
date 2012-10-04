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
    private static $boolean_attributes = array(
        'disabled',
        'checked',
        'readonly',
        'required',
        'autoplay',
        'selected',
    );

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

    public static function attribute($name, $value) {
        if (is_array($value)) {
            debugging("Passed an array for the HTML attribute $name", DEBUG_DEVELOPER);
        }
        if (strpos($name, " ")!==false) {
            debugging("Attribute names can't have spaces in them like \"$name\"", DEBUG_DEVELOPER);
        }
        if ($value instanceof moodle_url) {
            $value = $value->out();
        }
        if ($name === $value && in_array($name, self::$boolean_attributes)) {
            return $name;
        }
        $value = htmlspecialchars($value);
        if (strpbrk($value, "= '")!==false) {
            $value = '"'.$value.'"';
        }
        return "$name=$value";
    }

    public static function attributes($attributes) {
        $output = array();
        foreach ($attributes as $name => $value) {
            $output[] = self::attribute($name, $value);
        }
        return ' ' . implode(' ', $output);
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
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function a($attributes, $content) {
        return self::classy_tag('a', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function p($attributes, $content) {
        return self::classy_tag('p', $attributes, $content);
    }

    public static function div($attributes, $content) {
        return self::classy_tag('div', $attributes, $content);
    }

    public static function span($attributes, $content) {
        return self::classy_tag('span', $attributes, $content);
    }

    public static function abbr($attributes, $content) {
        return self::classy_tag('abbr', $attributes, $content);
    }

    public static function form($attributes, $content) {
        return self::classy_tag('form', $attributes, $content);
    }
    public static function input($attributes, $content = null) {
        return self::classy_tag('input', $attributes, $content);
    }
    public static function submit($attributes) {
        $attributes['type'] = 'submit';
        return self::input($attributes);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function ul($attributes, $content) {
        return self::classy_tag('ul', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
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

        return self::input($attributes);
    }


}
