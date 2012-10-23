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

require('bootstrap_pager.php');

function get_string($string) {
    return $string;
}

class bootstrap_pager_test extends PHPUnit_Framework_TestCase {

        private $disabled_previous = '<li class=disabled><span>previous</span></li>';
        private $disabled_next = '<li class=disabled><span>next</span></li>';
        private $skipped_link = '<li class=disabled><span>skipped</span></li>';

    private function active($text) {
        return "<li class=active><span>$text</span></li>";
    }
    private function expected_link($text, $number=null) {
        if ($number === null) {
            $number = $text;
        }
        $var = $number - 1;
        $attributes['href'] = "example.com?&pagevar=$var";
        return html::li('', html::a($attributes, $text));
    }

    public function test_first_page_of_two() {

        $pager = new bootstrap_pager('example.com?', 1, 2);

        $links = $pager->for_pages(array(1, 2));

        $actual = $links[0];
        $expected = $this->disabled_previous;
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->active(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[2];
        $expected = $this->expected_link(2);
        $this->assertEquals($expected, $actual);

        $actual = $links[3];
        $expected = $this->expected_link('next', 2);
        $this->assertEquals($expected, $actual);
    }
    public function test_last_page_of_two() {
        $pager = new bootstrap_pager('example.com?', 2, 2);

        $links = $pager->for_pages(array(1, 2));

        $actual = $links[0];
        $expected = $this->expected_link('previous', 1);
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->expected_link(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[2];
        $expected = $this->active(2);
        $this->assertEquals($expected, $actual);

        $actual = $links[3];
        $expected = $this->disabled_next;
        $this->assertEquals($expected, $actual);
    }
    public function test_first_page_of_nine() {

        $pager = new bootstrap_pager('example.com?', 1, 9);

        $links = $pager->for_pages(array(1, 2, 3, 4, 5, 6, 7, 8, 9));

        $actual = $links[0];
        $expected = $this->disabled_previous;
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->active(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[2];
        $expected = $this->expected_link(2);
        $this->assertEquals($expected, $actual);

        $actual = $links[8];
        $expected = $this->expected_link(8);
        $this->assertEquals($expected, $actual);

        $actual = $links[9];
        $expected = $this->expected_link(9);
        $this->assertEquals($expected, $actual);

        $actual = $links[10];
        $expected = $this->expected_link('next', 2);
        $this->assertEquals($expected, $actual);
    }
    public function test_last_page_of_nine() {
        $pager = new bootstrap_pager('example.com?', 9, 9);

        $links = $pager->for_pages(array(1, 2, 3, 4, 5, 6, 7, 8, 9));

        $actual = $links[0];
        $expected = $this->expected_link('previous', 8);
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->expected_link(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[8];
        $expected = $this->expected_link(8);
        $this->assertEquals($expected, $actual);

        $actual = $links[9];
        $expected = $this->active(9);
        $this->assertEquals($expected, $actual);

        $actual = $links[10];
        $expected = $this->disabled_next;
        $this->assertEquals($expected, $actual);
    }
    public function test_first_page_of_ten() {

        $pager = new bootstrap_pager('example.com?', 1, 10);

        $links = $pager->for_pages(array(1, 2, 3, 4, 5, 6, 7, 'skip', 10));

        $actual = $links[0];
        $expected = $this->disabled_previous;
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->active(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[2];
        $expected = $this->expected_link(2);
        $this->assertEquals($expected, $actual);

        $actual = $links[7];
        $expected = $this->expected_link(7);
        $this->assertEquals($expected, $actual);

        $actual = $links[8];
        $expected = $this->skipped_link;
        $this->assertEquals($expected, $actual);

        $actual = $links[9];
        $expected = $this->expected_link(10);
        $this->assertEquals($expected, $actual);

        $actual = $links[10];
        $expected = $this->expected_link('next', 2);
        $this->assertEquals($expected, $actual);
    }
    public function test_last_page_of_ten() {
        $pager = new bootstrap_pager('example.com?', 10, 10);

        $links = $pager->for_pages(array(1, 'skip', 4, 5, 6, 7, 8, 9, 10));

        $actual = $links[0];
        $expected = $this->expected_link('previous', 9);
        $this->assertEquals($expected, $actual);

        $actual = $links[1];
        $expected = $this->expected_link(1);
        $this->assertEquals($expected, $actual);

        $actual = $links[2];
        $expected = $this->skipped_link;
        $this->assertEquals($expected, $actual);

        $actual = $links[3];
        $expected = $this->expected_link(4);
        $this->assertEquals($expected, $actual);

        $actual = $links[9];
        $expected = $this->active(10);
        $this->assertEquals($expected, $actual);

        $actual = $links[10];
        $expected = $this->disabled_next;
        $this->assertEquals($expected, $actual);
    }
}

