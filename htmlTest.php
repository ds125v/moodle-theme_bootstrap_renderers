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

    public static function add_classes() {

        return array(
            array("a", "b", "a b"),
            array(" starting space", "no space", "no space starting"),
            array("no space", "trailing space ", "no space trailing"),
            array("         starting spaces", "no spaces", "no spaces starting"),
            array("no spaces", "trailing spaces        ", "no spaces trailing"),
            array("inside      space", "no space", "inside no space"),
            array("e", "e", "e"),
            array("slash-dot", "under_score", "slash-dot under_score"),
            array("alpha delta", "charlie bravo", "alpha bravo charlie delta"),
            array("z    z  z z   z   z ", "    a    a a   a   a   ", "a z"),
            array("", "", ""),
            array("only-one", "", "only-one"),
            array("the other one is empty", "", "empty is one other the"),
            array("some overlap", "partial overlap", "overlap partial some"),
        );
    }

    /**
     * @dataProvider add_classes
     */
    public function test_add_classes_string($existing, $new, $expected) {

        $result = html::add_classes_string($existing, $new);

        $this->assertEquals($result, $expected);

        list($existing, $new) = array($new, $existing);

        $result = html::add_classes_string($existing, $new);

        $this->assertEquals($result, $expected);

    }

    /**
     * @dataProvider add_classes
     * @depends test_add_classes_string
     */
    public function test_add_classes($existing, $new, $expected) {

        $attributes['class'] = $existing;
        $result = html::add_classes($attributes, $new);

        $this->assertArrayHasKey('class', $attributes);
        $this->assertEquals($result['class'], $expected);

        list($existing, $new) = array($new, $existing);

        $attributes['class'] = $existing;
        $result = html::add_classes($attributes, $new);

        $this->assertArrayHasKey('class', $attributes);
        $this->assertEquals($result['class'], $expected);
    }
}
