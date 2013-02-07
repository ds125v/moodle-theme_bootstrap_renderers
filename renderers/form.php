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
 * Bootstrap forms utility functions.
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('html.php');

class form {
    public static function section($legend, $content) {
        return html::fieldset(html::legend($legend).$content);
    }
    public static function inline_search($action, $placeholder, $value, $submit_text) {
        $form_attributes['class'] = 'form-search';
        $form_attributes['method'] = 'get';
        $form_attributes['action'] = $action;

        $input_attributes['class'] = 'search-query';
        $input_attributes['type'] = 'text';
        $input_attributes['name'] = 'query';
        $input_attributes['placeholder'] = $placeholder;
        $input_attributes['value'] = $value;

        return html::form($form_attributes, html::input($input_attributes) . html::submit(array('value'=>$submit_text)));
    }
    public static function inline_search_append($action, $placeholder, $value, $submit_text) {
        $form_attributes['class'] = 'inline-form';
        $form_attributes['method'] = 'get';
        $form_attributes['action'] = $action;

        $input_attributes['type'] = 'text';
        $input_attributes['name'] = 'query';
        $input_attributes['placeholder'] = $placeholder;
        $input_attributes['value'] = $value;

        return html::form($form_attributes,
            html::div('input-group', html::input($input_attributes) . html::span('input-group-btn', bootstrap::button($submit_text))));
    }
    public static function uneditable($text) {
        return html::span('uneditable-input', $text);
    }
    public static function row($label, $control, $help=null) {
        if (!empty($help)) {
            $help = html::span('help-block', $help);
        } else {
            $help = '';
        }
        return html::div('control-group',
            html::label('control-label', $label) .
            html::div('controls', $control.$help)
        );
    }
    public static function actions($text) {
        return html::div('form-actions', html::submit(array('class' => 'btn-primary', 'value' => $text)));
    }
    public static function moodle_url($moodle_url, $contents, $method='post') {
        $attributes['class'] = 'form-horizontal';
        $attributes['method'] = $method;
        if ($method === 'post') {
            $attributes['action'] = $moodle_url->out_omit_querystring();
        } else {
            $attributes['action'] = $moodle_url->out_omit_querystring(true);
        }
        $hidden_inputs = html::hidden_inputs($moodle_url->params());
        return html::form($attributes, $hidden_inputs . $contents);
    }
    public static function checkbox($label, $value) {
        return "<div class=checkbox><label><input type=checkbox value=\"$value\"> $label</label></div>";
    }
    public static function radio($name, $id, $label, $value) {
        return "<div class=radio><label><input id=$id name=\"$name\" type=radio value=\"$value\"> $label</label></div>";
    }
}
