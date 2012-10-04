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

require_once('classes.php');

class classesTest extends PHPUnit_Framework_TestCase {

    public static function add() {

        return array(
            array("order", "alphabetical", "alphabetical order"),
            array("alpha delta", "charlie bravo", "alpha bravo charlie delta"),
            array("z", "a", "a z"),
            array(" starting space", "no space", "no space starting"),
            array("no space", "trailing space ", "no space trailing"),
            array("         starting spaces", "no spaces", "no spaces starting"),
            array("no spaces", "trailing spaces        ", "no spaces trailing"),
            array("inside      space", "no space", "inside no space"),
            array("one", "one", "one"),
            array("slash-dot", "under_score", "slash-dot under_score"),
            array("z    z  z z   z   z ", "    a    a a   a   a   ", "a z"),
            array("", "", ""),
            array("only-one", "", "only-one"),
            array("other is empty", "", "empty is other"),
            array("some overlap", "partial overlap", "overlap partial some"),
        );
    }
    /**
     * @dataProvider add
     */
    public function test_add($existing, $new, $expected) {

        $actual = classes::add($existing, $new);

        $this->assertSame($expected, $actual);

        list($existing, $new) = array($new, $existing);

        $actual = classes::add($existing, $new);

        $this->assertSame($expected, $actual);

    }
    /**
     * @dataProvider add
     * @depends test_add
     */
    public function test_add_to_array($existing, $new, $expected) {

        $attributes['class'] = $existing;
        $actual = classes::add($attributes, $new);

        $this->assertArrayHasKey('class', $attributes);
        $this->assertSame($expected, $actual['class']);

        list($existing, $new) = array($new, $existing);

        $attributes['class'] = $existing;
        $actual = classes::add($attributes, $new);

        $this->assertArrayHasKey('class', $attributes);
        $this->assertSame($expected, $actual['class']);
    }
}
