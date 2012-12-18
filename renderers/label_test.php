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
 * Tests for HTML utility functions creating labels, by which we mean
 * Bootstrap style short text markers, rather than HTML form labels
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('label.php');

class labelTest extends PHPUnit_Framework_TestCase {

    public function test_label_with_yes_and_tick() {

        $expected = '<span class="label label-success">_yes <i class="icon-ok icon-white"></i></span>';
        $actual = label::yes();

        $this->assertSame($expected, $actual);
    }
    public function test_label_with_no_and_x() {

        $expected = '<span class="label label-important">_no <i class="icon-remove icon-white"></i></span>';
        $actual = label::no();

        $this->assertSame($expected, $actual);
    }

}
