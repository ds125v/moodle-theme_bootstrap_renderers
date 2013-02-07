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
 * renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('classes.php');

require_once($CFG->dirroot . "/blocks/course_overview/renderer.php");

class theme_bootstrap_renderers_block_course_overview_renderer extends block_course_overview_renderer {

    private static function up_arrow($courseid) {
        return self::arrow('up', $courseid);
    }
    private static function down_arrow($courseid) {
        return self::arrow('down', $courseid);
    }
    private static function arrow($direction, $courseid) {
        $move = -1;
        if ($direction === 'down') {
            $move = 1;
        }
        $url = new moodle_url('/blocks/course_overview/move.php',
            array('move' => $move, 'source' => $courseid, 'sesskey' => sesskey()));
        return html::a(array('href' => $url, 'title' => get_string("move$direction")), bootstrap::icon("arrow-$direction"));
    }
    public function course_overview($courses, $overviews) {
        $config = get_config('block_course_overview');

        $html = html::div_open(array('id' => 'course_list', 'class' => 'unstyled-list'));
        $courseordernumber = 0;
        $maxcourses = count($courses);
        foreach ($courses as $course) {
            $html .= html_writer::start_tag('div', array('class' => 'course_title'));
            if ($this->page->user_is_editing() and ajaxenabled()) {
                $html .= html::div('move', bootstrap::icon('move'));
            } else if ($this->page->user_is_editing() and !ajaxenabled()) {
                $moveicons = '';
                if ($courseordernumber > 0) {
                    $moveicons .= self::up_arrow($course->id);
                } else {
                    $moveicons .= html::spacer();
                }
                if ($courseordernumber <= $maxcourses-2) {
                    $moveicons .= self::down_arrow($course->id);
                } else {
                    $moveicons .= html::spacer();
                }
                $html .= html::div('moveicons', $moveicons);
            }

            if ($course->id > 0) {
                $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                $coursefullname = format_string($course->fullname, true, $course->id);
                $courselink = html::link($courseurl, $coursefullname);
                $html .= html::h2($courselink);
            } else {
                $wantsurl = new moodle_url('/course/view.php', array('id' => $course->remoteid));
                $courseurl = new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => $wantsurl));
                $hostname = format_string($course->hostname);
                $coursename = format_string($course->shortname, true) . " ($hostname)";
                $courselink = html::link($courseurl, $coursename);
                $html .= html::h2($courselink);
            }
            $html .= '</div>'; // End .course_title.

            if (!empty($config->showchildren) && ($course->id > 0)) {
                // List children here.
                if ($children = block_course_overview_get_child_shortnames($course->id)) {
                    $html .= html::span('coursechildren', $children);
                }
            }

            if (isset($overviews[$course->id])) {
                $html .= $this->activity_display($course->id, $overviews[$course->id]);
            }
            $courseordernumber++;
        }
        $html .= '</div>'; // End #course_list.

        return $html;
    }

    public function hidden_courses($total) {
        if ($total <= 0) {
            return '';
        }
        $string = 'hiddencoursecount';
        if ($total > 1) {
            $string = 'hiddencoursecountplural';
        }
        return bootstrap::alert_info(get_string($string, 'block_course_overview', $total));
    }
    public function welcome_area($msgcount) {
        global $USER;

        $notification = get_string('youhavenomessages', 'block_course_overview');
        if ($msgcount > 0) {
            $notification = get_string('youhavemessages', 'block_course_overview', $msgcount);
        }
        $url = new moodle_url('/message/index.php');
        $string = 'messages';
        if ($msgcount === 1) {
            $string = 'message';
        }
        $notification .= html::link($url, get_string($string, 'block_course_overview')) . '.';
        return bootstrap::alert_info(
            html::p(html::strong(get_string('welcome', 'block_course_overview', $USER->firstname)) . ' ' .  $notification)
        );
    }
}

