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


require_once('classes.php');
require_once('attributes.php');

class html {


    private static $zurb = false;

    private static $to_zurb = array(
        'a' => array('btn'=>'button'),
        'button' => array('btn'=>'button'),
        'div' => array('well'=>'panel'),
        'input' => array('btn'=>'button'),
        'li' => array('active'=>'current', 'disabled'=>'unavailable'),
        'ul' => array('breadcrumb'=>'breadcrumbs'),
    );

    private static function bootstrap_to_zurb($tag, $classes) {
        return classes::replace($classes, self::$to_zurb[$tag]);
    }

    public static function tag($tagname, $attributes, $contents) {
        if (self::$zurb && isset($attributes['class'])) {
            $attributes['class'] = self::bootstrap_to_zurb($tagname, $attributes['class']);
        }
        if ($contents === null) {
            return "<$tagname" . attributes::all($attributes) . '>';
        } else {
            return "<$tagname" . attributes::all($attributes) . ">$contents</$tagname>";
        }
    }

    private static function guten_tag($texty, $tag, $attributes, $content = null) {
        if ($texty === 1) {
            return self::texty_tag($tag, $attributes, $content);
        } else {
            return self::classy_tag($tag, $attributes, $content);
        }
    }
    private static function classy_tag($tag, $attributes, $content = null) {
        if (is_string($attributes)) {
            if ($attributes === '') {
                $attributes = array();
            } else {
                $attributes = array('class'=>$attributes);
            }
        }
        return self::tag($tag, $attributes, $content);
    }
    private static function texty_tag($tag, $attributes, $content=null) {
        if ($content === null && !is_array($attributes)) {
            $content = $attributes;
            $attributes = array();
        } else if ($content === null && is_array($attributes)) {
            $content = '';
        }
        return self::classy_tag($tag, $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function a($attributes, $content) {
        if (is_object($attributes) && get_class($attributes) == 'moodle_url') {
            $attributes = (string)$attributes;
        }
        if (is_string($attributes)) {
            if ($attributes === '') {
                $attributes = array();
            } else {
                $attributes = array('href'=>$attributes);
            }
        }
        return self::tag('a', $attributes, $content);
    }
    public static function a_button($attributes, $content) {
        if (is_object($attributes) && get_class($attributes) == 'moodle_url') {
            $attributes = (string)$attributes;
        }
        if (is_string($attributes)) {
            if ($attributes === '') {
                $attributes = array();
            } else {
                $attributes = array('href'=>$attributes);
            }
        }
        $attributes = classes::add_to($attributes, 'btn');
        return self::tag('a', $attributes, $content);
    }
    public static function link($href, $content) {
        return self::classy_tag('a', array('href' => $href), $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function p($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'p', $attributes, $content);
    }

    public static function div($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'div', $attributes, $content);
    }
    public static function div_open($attributes) {
        return self::div($attributes, null);
    }
    public static function fieldset($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'fieldset', $attributes, $content);
    }
    public static function span($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'span', $attributes, $content);
    }
    public static function strong($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'strong', $attributes, $content);
    }
    public static function small($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'small', $attributes, $content);
    }
    public static function legend($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'legend', $attributes, $content);
    }
    public static function table($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'table', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function h1($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'h1', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function h2($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'h2', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function h3($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'h3', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function h4($attributes, $content=null) {
        return self::guten_tag(func_num_args(), 'h4', $attributes, $content);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function img($attributes) {
        return self::classy_tag('img', $attributes);
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
    public static function label($attributes, $content = null) {
        if (func_num_args() === 1) {
            return self::texty_tag('label', $attributes);
        }
        return self::classy_tag('label', $attributes, $content);
    }
    public static function checkbox($new_attributes, $label) {
        $attributes = array('type'=>'checkbox', 'value'=>1);
        if (!is_array($new_attributes)) {
            $attributes['id'] = (string)$new_attributes;
            $attributes['name'] = (string)$new_attributes;
        } else {
            $attributes = array_merge($attributes, $new_attributes);
        }
        return self::label(self::input($attributes) . " $label");
    }
    public static function input_hidden($name, $value) {
        $attributes['type'] = 'hidden';
        $attributes['name'] = $name;
        $attributes['value'] = $value;

        return self::input($attributes);
    }
    public static function hidden_inputs($params) {
        $output = array();
        foreach ($params as $name => $value) {
            $output[] = self::input_hidden($name, $value);
        }
        return implode($output);
    }
    public static function submit($attributes) {
        $attributes['type'] = 'submit';
        $attributes = classes::add_to($attributes, 'btn');
        return self::input($attributes);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function ul($attributes, $content) {
        return self::classy_tag('ul', $attributes, self::li_implode($content));
    }

    public static function ul_open($attributes) {
        return self::ul($attributes, null);
    }
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function li($attributes, $content=null) {
        if (func_num_args() === 1) {
            $content = $attributes;
            return self::texty_tag('li', $content);
        }
        return self::classy_tag('li', $attributes, $content);
    }
    public static function li_implode($items, $glue='</li><li>') {
        if (is_string($items) || $items === null) {
            return $items;
        }
        if (strpos($items[0], '<li') === 0) {
            return implode($items);
        } else {
            return '<li>'.implode($glue, $items).'</li>';
        }
    }
    public static function url($base, $params=null) {
        if ($params === null) {
            return $base;
        } else {

            return $base . '?' . self::params($params);
        }
    }
    public static function params($params) {
        foreach ($params as $name => $value) {
            $name = urlencode($name);
            $value = urlencode($value);
            $output[] = "$name=$value";
        }
        return implode('&', $output);
    }
}
