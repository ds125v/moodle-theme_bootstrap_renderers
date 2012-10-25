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


require('pager.php');

class pager_test extends PHPUnit_Framework_TestCase {

    public function test_first_of_two_pages() {
        $first = new pager(100, 200, 1);
        $actual = $first->pages();
        $expected = array(1, 2);
        $this->assertEquals($expected, $actual);
    }
    public function test_second_of_two_pages() {
        $first = new pager(100, 200, 2);
        $actual = $first->pages();
        $expected = array(1, 2);
        $this->assertEquals($expected, $actual);
    }
    public function test_first_of_nine_pages() {
        $first = new pager(100, 900, 1);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $this->assertEquals($expected, $actual);
    }
    public function test_fifth_of_nine_pages() {
        $first = new pager(100, 900, 5);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $this->assertEquals($expected, $actual);
    }
    public function test_ninth_of_nine_pages() {
        $first = new pager(100, 900, 9);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        $this->assertEquals($expected, $actual);
    }
    public function test_first_of_ten_pages() {
        $first = new pager(100, 1000, 1);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 'skip', 10);
        $this->assertEquals($expected, $actual);
    }
    public function test_tenth_of_ten_pages() {
        $first = new pager(100, 1000, 10);
        $actual = $first->pages();
        $expected = array(1, 'skip', 4, 5, 6, 7, 8, 9, 10);
        $this->assertEquals($expected, $actual);
    }
    public function test_first_of_eleven_pages() {
        $first = new pager(100, 1100, 1);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 'skip', 11);
        $this->assertEquals($expected, $actual);
    }
    public function test_fifth_of_eleven_pages() {
        $first = new pager(100, 1100, 5);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 'skip', 11);
        $this->assertEquals($expected, $actual);
    }
    public function test_sixth_of_eleven_pages() {
        $first = new pager(100, 1100, 6);
        $actual = $first->pages();
        $expected = array(1, 'skip', 4, 5, 6, 7, 8, 'skip', 11);
        $this->assertEquals($expected, $actual);
    }
    public function test_seventh_of_eleven_pages() {
        $first = new pager(100, 1100, 7);
        $actual = $first->pages();
        $expected = array(1, 'skip', 5, 6, 7, 8, 9, 10, 11);
        $this->assertEquals($expected, $actual);
    }
    public function test_eleventh_of_eleven_pages() {
        $first = new pager(100, 1100, 11);
        $actual = $first->pages();
        $expected = array(1, 'skip', 5, 6, 7, 8, 9, 10, 11);
        $this->assertEquals($expected, $actual);
    }
    public function test_first_of_twelve_pages() {
        $first = new pager(100, 1200, 1);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 'skip', 12);
        $this->assertEquals($expected, $actual);
    }
    public function test_fifth_of_twelve_pages() {
        $first = new pager(100, 1200, 5);
        $actual = $first->pages();
        $expected = array(1, 2, 3, 4, 5, 6, 7, 'skip', 12);
        $this->assertEquals($expected, $actual);
    }
    public function test_sixth_of_twelve_pages() {
        $first = new pager(100, 1200, 6);
        $actual = $first->pages();
        $expected = array(1, 'skip', 4, 5, 6, 7, 8, 'skip', 12);
        $this->assertEquals($expected, $actual);
    }
    public function test_seventh_of_twelve_pages() {
        $first = new pager(100, 1200, 7);
        $actual = $first->pages();
        $expected = array(1, 'skip', 5, 6, 7, 8, 9, 'skip', 12);
        $this->assertEquals($expected, $actual);
    }
    public function test_twelth_of_twelve_pages() {
        $first = new pager(100, 1200, 12);
        $actual = $first->pages();
        $expected = array(1, 'skip', 6, 7, 8, 9, 10, 11, 12);
        $this->assertEquals($expected, $actual);
    }
}

