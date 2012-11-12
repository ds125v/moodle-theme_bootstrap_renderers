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
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('progress.php');

class progressTest extends PHPUnit_Framework_TestCase {
    public function test_full_progress_bar() {

        $expected = '<div class=progress><div class=bar style="width: 100%"></div></div>';
        $actual = progress::bar(100);

        $this->assertSame($expected, $actual);
    }
    public function test_empty_progress_bar() {

        $expected = '<div class=progress><div class=bar style="width: 0%"></div></div>';
        $actual = progress::bar(0);

        $this->assertSame($expected, $actual);
    }

}
