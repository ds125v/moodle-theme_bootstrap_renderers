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

require_once('mod_choice_renderer.php');

class mod_choice_renderer_test extends PHPUnit_Framework_TestCase {

    public function test_first() {

        $option1 = new stdClass;
        $option1->text = 'one';
        $option1->user = array('user1');
        $option2 = new stdClass;
        $option2->text = 'none';
        $option2->user = array();

        $options = array($option1, $option2);
        $choices = new stdClass;
        $choices->options = $options;
        $choices->numberofuser = 1;
        $actual = theme_bootstrap_renderers_mod_choice_renderer::results($choices);

        $this->assertSame('one', $actual[0]['text']);
        $this->assertSame(1, $actual[0]['votes']);
        $this->assertSame(100.0, $actual[0]['percent']);
        $this->assertSame('none', $actual[1]['text']);
        $this->assertSame(0, $actual[1]['votes']);
        $this->assertSame(0.0, $actual[1]['percent']);
    }
}
