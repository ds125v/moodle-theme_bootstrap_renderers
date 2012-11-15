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


require_once('html.php');

class htmlTest extends PHPUnit_Framework_TestCase {

    public static function submits() {

        return array(
            array(array( 'value'=>'hello'),
            '<input class=btn type=submit value=hello>'),
            array(array( 'value'=>'hello', 'disabled'=>'disabled'),
            '<input class=btn disabled type=submit value=hello>'),
            array(array(
                'value'=>'hello',
                'disabled'=>'disabled',
                'title'=>'this is the title',
            ),
            '<input class=btn disabled title="this is the title" type=submit value=hello>'),
        );
    }

    /**
     * @dataProvider submits
     */
    public function test_submit($attributes, $expected) {

        $actual = html::submit($attributes);

        $this->assertSame($expected, $actual);

    }
    public function test_empty_hidden_inputs() {

        $actual = html::hidden_inputs(array());
        $expected = '';

        $this->assertSame($expected, $actual);
    }
    public function test_span_given_a_single_string_arg_uses_it_as_content() {

        $actual = html::span("some text");
        $expected = '<span>some text</span>';

        $this->assertSame($expected, $actual);
    }
    public function test_span_with_two_string_args() {

        $actual = html::span('some classes', 'some text');
        $expected = '<span class="some classes">some text</span>';

        $this->assertSame($expected, $actual);
    }
    public function test_span_with_empty_second_arg() {

        $actual = html::span('some classes', '');
        $expected = '<span class="some classes"></span>';

        $this->assertSame($expected, $actual);
    }
    public function test_span_with_attributes_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::span($attributes);
        $expected = '<span class=test data=0101></span>';

        $this->assertSame($expected, $actual);
    }
    public function test_span_with_attributes_and_null_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::span($attributes, null);
        $expected = '<span class=test data=0101>';

        $this->assertSame($expected, $actual);
    }
    public function test_span_with_attributes_and_empty_string_args() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::span($attributes, '');
        $expected = '<span class=test data=0101></span>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_one_string_arg() {

        $actual = html::p("some text");
        $expected = '<p>some text</p>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_two_string_args() {

        $actual = html::p('some classes', 'some text');
        $expected = '<p class="some classes">some text</p>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_empty_second_arg() {

        $actual = html::p('some classes', '');
        $expected = '<p class="some classes"></p>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_attributes_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::p($attributes);
        $expected = '<p class=test data=0101></p>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_attributes_and_null_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::p($attributes, null);
        $expected = '<p class=test data=0101>';

        $this->assertSame($expected, $actual);
    }
    public function test_p_with_attributes_and_empty_string_args() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::p($attributes, '');
        $expected = '<p class=test data=0101></p>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_one_string_arg() {

        $actual = html::li("some text");
        $expected = '<li>some text</li>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_two_string_args() {

        $actual = html::li('some classes', 'some text');
        $expected = '<li class="some classes">some text</li>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_empty_second_arg() {

        $actual = html::li('some classes', '');
        $expected = '<li class="some classes"></li>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_attributes_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::li($attributes);
        $expected = '<li class=test data=0101></li>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_attributes_and_null_arg() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::li($attributes, null);
        $expected = '<li class=test data=0101>';

        $this->assertSame($expected, $actual);
    }
    public function test_li_with_attributes_and_empty_string_args() {

        $attributes = array('class' => 'test', 'data'=>'0101');
        $actual = html::li($attributes, '');
        $expected = '<li class=test data=0101></li>';

        $this->assertSame($expected, $actual);
    }
    public function test_simple_url() {

        $actual = html::url('http://www.example.com');
        $expected = 'http://www.example.com';

        $this->assertSame($expected, $actual);
    }
    public function test_url_with_single_param() {

        $param = array('one'=>'fish');
        $actual = html::url('http://www.example.com', $param);
        $expected = 'http://www.example.com?one=fish';

        $this->assertSame($expected, $actual);
    }
    public function test_url_with_two_params() {

        $params = array('one'=>'money', 'two'=>'show');
        $actual = html::url('http://www.example.com', $params);
        $expected = 'http://www.example.com?one=money&two=show';

        $this->assertSame($expected, $actual);
    }
}
