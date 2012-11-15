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

class bootsnippTest extends PHPUnit_Framework_TestCase {

    public function test_guest_user() {
        $guest['name'] = 'Login';
        $guest['link'] = 'http://www.example.com/login/index.php';
        $logout['name'] = 'Logout';
        $logout['link'] = 'http://www.example.com/login/logout.php?sesskey=abcdefghij';
        $actual = bootsnipp::guest_user('Guest User', $guest, $logout);

        $this->assertSelectEquals('ul.pull-right li a', 'Guest User', 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li' , 3,  $actual);
        $this->assertSelectCount('ul.pull-right li ul li a' , 2,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li a i.icon-user', 1,  $actual);
        $this->assertSelectEquals('ul.pull-right li ul li a', 'Login', 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li.divider' , 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li a i.icon-off', 1,  $actual);
        $this->assertSelectEquals('ul.pull-right li ul li a', 'Logout', 1,  $actual);
    }
    public function test_signed_in() {
        $user['name'] = 'Test Name';
        $user['link'] = 'http://www.example.com/profile/index.php';
        $logout['name'] = 'Logout';
        $logout['link'] = 'http://www.example.com/login/logout.php?sesskey=abcdefghij';

        $actual = bootsnipp::signed_in($user, null, null, null, null, $logout);

        $this->assertSelectEquals('ul.pull-right li a', 'Test Name', 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li' , 3,  $actual);
        $this->assertSelectCount('ul.pull-right li ul li a' , 2,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li a i.icon-user', 1,  $actual);
        $this->assertSelectEquals('ul.pull-right li ul li a', 'Profile', 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li.divider' , 1,  $actual);

        $this->assertSelectCount('ul.pull-right li ul li a i.icon-off', 1,  $actual);
        $this->assertSelectEquals('ul.pull-right li ul li a', 'Logout', 1,  $actual);
    }

}
