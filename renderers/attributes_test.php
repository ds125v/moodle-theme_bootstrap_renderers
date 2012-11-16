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
 * Tests for HTML attribute utility functions
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('stubs.php');
require_once('attributes.php');

class attribute_test extends PHPUnit_Framework_TestCase {

    public static function attributes() {
        return array(
            array('name', 'value', 'name=value'),
            array('name', 'two values', 'name="two values"'),
            array('equals', '=', 'equals="="'),
            array('double-quote', '"', 'double-quote=&quot;'),
            array('sums', '1+1=2', 'sums="1+1=2"'),
            array('lessthan', '<', 'lessthan=&lt;'),
            array('morethan', '>', 'morethan=&gt;'),
            array('name', 'O\'Reilly', 'name="O\'Reilly"'),
            array('big-apple', 'I <3 New York', 'big-apple="I &lt;3 New York"'),
            array('candy', 'M&Ms', 'candy=M&amp;Ms'),
            array('leading', ' space', 'leading=" space"'),
            array('disabled', 'disabled', 'disabled'),
        );
    }
    /**
     * @dataProvider attributes
     */
    public function test_attribute($name, $value, $expected) {

        $actual = attributes::one($name, $value);

        $this->assertSame($expected, $actual);

    }

    public function test_no_output_for_attribute_with_null_value() {

        $name = "not-output";
        $value = null;
        $actual = attributes::one($name, $value);

        $this->assertSame('', $actual);

    }
    /**
     * @expectedException coding_exception
     **/
    public function test_exception_if_attribute_name_has_spaces() {

        $name = "no spaces allowed in name";
        $value = "spaces in value are fine";
        attributes::one($name, $value);
    }
    /**
     * @expectedException coding_exception
     **/
    public function test_exception_if_value_is_an_array() {

        $name = 'no-array-values';
        $value = array('not', 'valid');
        attributes::one($name, $value);
    }

    public function test_output_for_attribute_empty_value() {

        $name = "output";
        $value = '';
        $actual = attributes::one($name, $value);

        $this->assertSame('output=""', $actual);

    }
}

