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

require_once($CFG->dirroot . "/course/format/topics/renderer.php");

class theme_bootstrap_renderers_format_topics_renderer extends format_topics_renderer {

    protected function start_section_list() {
        return '<ul class="list-unstyled">';
    }

    protected function end_section_list() {
        return '</ul>';
    }
    protected function page_title() {
        return get_string('topicoutline');
    }

    /**
     * Generate the edit controls of a section
     *
     * @param stdClass $course The course entry from DB
     * @param stdClass $section The course_section entry from DB
     * @param bool $onsectionpage true if being printed on a section page
     * @return array of links with edit controls
     */
    protected function section_edit_controls($course, $section, $onsectionpage = false) {
        global $PAGE;

        if (!$PAGE->user_is_editing()) {
            return array();
        }

        $coursecontext = context_course::instance($course->id);

        if ($onsectionpage) {
            $url = course_get_url($course, $section->section);
        } else {
            $url = course_get_url($course);
        }
        $url->param('sesskey', sesskey());

        $controls = array();
        if (has_capability('moodle/course:setcurrentsection', $coursecontext)) {
            if ($course->marker == $section->section) {  // Show the "light globe" on/off.
                $url->param('marker', 0);
                $controls[] = html_writer::link($url,
                                    html::img(array('src' => $this->output->pix_url('i/marked'),
                                        'class' => 'icon ', 'alt' => get_string('markedthistopic'))),
                                    array('title' => get_string('markedthistopic'), 'class' => 'editing_highlight'));
            } else {
                $url->param('marker', $section->section);
                $controls[] = html_writer::link($url,
                                html::img(array('src' => $this->output->pix_url('i/marker'),
                                    'class' => 'icon', 'alt' => get_string('markthistopic'))),
                                array('title' => get_string('markthistopic'), 'class' => 'editing_highlight'));
            }
        }
        return array_merge($controls, parent::section_edit_controls($course, $section, $onsectionpage));
    }
    protected function section_right_content($section, $course, $onsectionpage) {
        if ($section->section != 0) {
            $controls = $this->section_edit_controls($course, $section, $onsectionpage);
            if (!empty($controls)) {
                return implode($controls);
            }
        }
        return '';
    }

    protected function section_left_content($section, $course, $onsectionpage) {
        if ($section->section != 0) {
            if (course_get_format($course)->is_section_current($section)) {
                return get_accesshide(get_string('currentsection', 'format_'.$course->format));
            }
        }
        return '';
    }
    protected function section_summary($section, $course, $mods) {
        $classattr = 'section-summary';
        $linkclasses = '';

        // If section is hidden then display grey section link
        if (!$section->visible) {
            $classattr .= ' muted';
            $linkclasses .= ' muted';
        } else if (course_get_format($course)->is_section_current($section)) {
            $classattr .= ' current';
        }

        $o = '';

        $title = get_section_name($course, $section);
        if ($section->uservisible) {
            $title = html_writer::tag('a', $title,
                    array('href' => course_get_url($course, $section->section), 'class' => $linkclasses));
        }
        $o .= html::h1($title);

        $o.= html::div('summarytext', $this->format_summary_text($section));
        $o.= $this->section_activity_summary($section, $course, null);

        $context = context_course::instance($course->id);
        $o .= $this->section_availability_message($section,
                has_capability('moodle/course:viewhiddensections', $context));

        return html::li(array('id' => 'section-'.$section->section, 'class' => $classattr), html::div('content', $o));
    }

    private function section_activity_summary($section, $course, $mods) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section:
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->modname == 'label') {
                // Labels are special (not interesting for students)!
                continue;
            }

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE) {
                        $complete++;
                    }
                }
            }
        }

        if (empty($sectionmods)) {
            // No sections
            return '';
        }

        // Output section activities summary:
        foreach ($sectionmods as $mod) {
            $mods[] = html::li($mod['name'].': '.$mod['count']);
        }
        $activity_list = html::ul('section-summary-activities', $mods);

        if ($total > 0) { // Output section completion data.
            $a = new stdClass;
            $a->complete = $complete;
            $a->total = $total;
            $completion = html::p('section-completion', get_string('progresstotal', 'completion', $a));
            return $activity_list . $completion;
        }
        return $activity_list;
    }

    protected function section_availability_message($section, $canviewhidden) {
        global $CFG;
        if (!$section->uservisible) {
            return html::div('availabilityinfo', $section->availableinfo);
        } else if ($canviewhidden && !empty($CFG->enableavailability) && $section->visible) {
            $ci = new condition_info_section($section);
            $fullinfo = $ci->get_full_information();
            if ($fullinfo) {
                $message = get_string(
                        ($section->showavailability ? 'userrestriction_visible' : 'userrestriction_hidden'),
                        'condition', $fullinfo);
                return html::div('availabilityinfo', $message);
            }
        } else {
            return ''; // Not sure if this is required or can be collapsed to simple if/else.
        }
    }
    protected function section_hidden($sectionno) {
        return html::li(array('id' => 'section-'.$sectionno, 'class' => 'muted'),
                html::div('content', get_string('notavailable')));
    }
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        if (!$sectioninfo->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or !empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
            echo $this->start_section_list();
            echo $this->section_header($thissection, $course, true, $displaysection);
            print_section($course, $thissection, null, null, true, "100%", false, $displaysection);
            if ($PAGE->user_is_editing()) {
                print_section_add_menus($course, 0, null, false, false, $displaysection);
            }
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html::div_open('single-section');

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = html::ul('pager', html::li('previous', $sectionnavlinks['previous']) . html::li('next', $sectionnavlinks['next']));
        $sectiontitle .= html::h1(get_section_name($course, $displaysection));
        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        print_section($course, $thissection, null, null, true, '100%', false, $displaysection);
        if ($PAGE->user_is_editing()) {
            print_section_add_menus($course, $displaysection, null, false, false, $displaysection);
        }
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $courselink = html_writer::link(course_get_url($course), get_string('returntomaincoursepage'));
        $sectionbottomnav = html::ul('pager', html::li('previous', $sectionnavlinks['previous']) . html::li('next', $sectionnavlinks['next']));
        $sectionbottomnav .= html::p($courselink);
        echo $sectionbottomnav;

        echo '</div>'; // .single-section
    }
}

