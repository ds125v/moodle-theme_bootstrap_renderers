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

    public function test_add_classes() {
        $existing = "a b c";
        $new = "d e f";
        $expected = "a b c d e f";
        $result = html::add_classes_string($existing, $new);
        $this->assertEquals($result, $expected);
        list($existing, $new) = array($new, $existing);
        $result = html::add_classes_string($existing, $new);
        $this->assertEquals($result, $expected);
    }
}
