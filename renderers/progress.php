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
 * A class for creating progress bars
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('classes.php');

class progress {

    public static function bar($percent, $type = '') {
        if ($type != '') {
            $type = ' progress-' . $type;
        }
        //return "<div class=\"progress$type\"><div class=bar style=\"width:$percent%\"></div></div>";
        return html::div("progress$type", html::div(array( 'class' => 'bar', 'style' => "width:$percent%")));
    }
    public static function level($percent) {
        return "<div class=progress style=\"width:20px;height:300px\"><div class=bar style=\"width:20px;height:$percent%\"></div></div>";
    }

}
