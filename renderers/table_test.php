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
 * Tests for  HTML utility functions for creating tables. The basic concept here
 * is that you just want to create a 2D array of conten and then output
 * it. Rather than micro-manage the display via HTML attributes and classes
 * we should a) give up control and let the browser do it, since we have little
 * control over the length of UI text, due to translation issues, or content,
 * due to it being user data dependant or display size since it varies by device
 * and user preferences.
 *
 * For those cases when we absolutely must control display then we can either apply
 * that to the content, rather than the table cell itself, or use advanced CSS to 
 * target e.g. every other row, or the last table cell in each row 
 * Bootstrap style short text markers, rather than HTML form labels
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('table.php');

class tableTest extends PHPUnit_Framework_TestCase {

    public function test_table_row_with_array_of_data() {

        $cells = array('one', 'two');
        $actual = table::tr($cells);
        $expected = '<tr><td>one</td><td>two</td></tr>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_row_with_array_of_td_tags() {

        $cells = array('<td class=first>one</td>', '<td class=second>two</td>');
        $actual = table::tr($cells);
        $expected = '<tr><td class=first>one</td><td class=second>two</td></tr>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_row_with_string_of_td_tags() {

        $cells = '<td class=first>one</td><td class=second>two</td>';
        $actual = table::tr($cells);
        $expected = '<tr><td class=first>one</td><td class=second>two</td></tr>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_header_row_with_array_of_data() {

        $cells = array('one', 'two');
        $actual = table::thead($cells);
        $expected = '<thead><tr><th>one</th><th>two</th></tr></thead>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_header_row_with_array_of_th_tags() {

        $cells = array('<th class=first>one</th>', '<th class=second>two</th>');
        $actual = table::thead($cells);
        $expected = '<thead><tr><th class=first>one</th><th class=second>two</th></tr></thead>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_row_with_string_of_th_tags() {

        $cells = '<th class=first>one</th><th class=second>two</th>';
        $actual = table::thead($cells);
        $expected = '<thead><tr><th class=first>one</th><th class=second>two</th></tr></thead>';

        $this->assertSame($expected, $actual);
    }
    public function test_tbody_with_array_of_one_row() {

        $rows = array(array('one', 2));
        $actual = table::tbody($rows);
        $expected = '<tbody><tr><td>one</td><td>2</td></tr></tbody>';

        $this->assertSame($expected, $actual);
    }
    public function test_tbody_with_array_of_row_strings() {

        $rows = array('<tr><td>one</td><td>2</td></tr>', '<tr><td>three</td><td>4</td></tr>');
        $actual = table::tbody($rows);
        $expected = '<tbody><tr><td>one</td><td>2</td></tr><tr><td>three</td><td>4</td></tr></tbody>';

        $this->assertSame($expected, $actual);
    }
    public function test_tbody_with_array_of_two_rows() {

        $rows = array(array('one', 2), array('three', 4));
        $actual = table::tbody($rows);
        $expected = '<tbody><tr><td>one</td><td>2</td></tr><tr><td>three</td><td>4</td></tr></tbody>';

        $this->assertSame($expected, $actual);
    }
    public function test_table_row_with_string_of_rows() {

        $rows = '<tr><td class=first>one</td><td class=second>two</td></tr>';
        $actual = table::tbody($rows);
        $expected = '<tbody><tr><td class=first>one</td><td class=second>two</td></tr></tbody>';

        $this->assertSame($expected, $actual);
    }
}
