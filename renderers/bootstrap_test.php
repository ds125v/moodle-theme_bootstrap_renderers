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


require_once('bootstrap.php');

class bootstrapTest extends PHPUnit_Framework_TestCase {

    public function test_initialism_ibm() {

        $expected = '<abbr class=initialism title="International Business Machines">IBM</abbr>';
        $actual = bootstrap::initialism('IBM', 'International Business Machines');

        $this->assertSame($expected, $actual);
    }
    public function test_initialism_moodle() {
        $expected = '<abbr class=initialism title="Martin\'s Object-Oriented Dynamic Learning Environment">MOODLE</abbr>';
        $actual = bootstrap::initialism('MOODLE', 'Martin\'s Object-Oriented Dynamic Learning Environment');

        $this->assertSame($expected, $actual);
    }
    public function test_initialism_i18n() {
        $expected = '<abbr class=initialism title=IÃ±tÃ«rnÃ¢tiÃ´nÃ lizÃ¦tiÃ¸n>i18n</abbr>';
        $actual = bootstrap::initialism('i18n', 'IÃ±tÃ«rnÃ¢tiÃ´nÃ lizÃ¦tiÃ¸n');

        $this->assertSame($expected, $actual);
    }
}
