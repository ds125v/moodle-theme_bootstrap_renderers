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
 * Tests for Bootsnipps
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('bootsnipp.php');

class bootsnipp_test extends PHPUnit_Framework_TestCase {

    public function test_guest_user() {
        $guest['name'] = 'Login';
        $guest['link'] = 'http://www.example.com/login/index.php';
        $logout['name'] = 'Logout';
        $logout['link'] = 'http://www.example.com/login/logout.php?sesskey=abcdefghij';
        $actual = bootsnipp::guest_user('Guest User', $guest, $logout);
        $this->assertContains('icon-off', $actual);
        $this->assertContains('icon-user', $actual);
    }


}
