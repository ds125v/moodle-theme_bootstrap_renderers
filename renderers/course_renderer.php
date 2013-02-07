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

require_once($CFG->dirroot . "/course/renderer.php");

class theme_bootstrap_renderers_core_course_renderer extends core_course_renderer {

    public function course_info_box(stdClass $course) {
        global $CFG;

        $context = context_course::instance($course->id);

        $summary = file_rewrite_pluginfile_urls($course->summary, 'pluginfile.php', $context->id, 'course', 'summary', null);
        $content = format_text($summary, $course->summaryformat, array('overflowdiv'=>true), $course->id);
        if (!empty($CFG->coursecontact)) {
            $coursecontactroles = explode(',', $CFG->coursecontact);
            foreach ($coursecontactroles as $roleid) {
                if ($users = get_role_users($roleid, $context, true)) {
                    foreach ($users as $teacher) {
                        $role = new stdClass();
                        $role->id = $teacher->roleid;
                        $role->name = $teacher->rolename;
                        $role->shortname = $teacher->roleshortname;
                        $role->coursealias = $teacher->rolecoursealias;
                        $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context));
                        $namesarray[] = role_get_name($role, $context).': <a href="'.$CFG->wwwroot.'/user/view.php?id='.
                            $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
                    }
                }
            }

            if (!empty($namesarray)) {
                $content .= html::ul('teachers list-unstyled', $namesarray);
            }
        }
        return html::div($content);
    }
    public function course_modchooser($modules, $course) {
        global $OUTPUT;
        $header = html::div_open('modal fade');
        $header .= html::div('modal-header',
            bootstrap::close_button('modal') .
            html::h3(get_string('addresourceoractivity', 'moodle')));

        $formcontent = html_writer::start_tag('form', array('action' => new moodle_url('/course/jumpto.php'),
                'id' => 'chooserform', 'method' => 'post'));
        $formcontent .= html_writer::start_tag('div', array('id' => 'typeformdiv'));
        $formcontent .= html_writer::tag('input', '', array('type' => 'hidden', 'id' => 'course',
                'name' => 'course', 'value' => $course->id));
        $formcontent .= html_writer::tag('input', '',
                array('type' => 'hidden', 'class' => 'jump', 'name' => 'jump', 'value' => ''));
        $formcontent .= html_writer::tag('input', '', array('type' => 'hidden', 'name' => 'sesskey',
                'value' => sesskey()));
        $formcontent .= html_writer::end_tag('div');

        // Put everything into one tag 'options'
        $formcontent .= html_writer::start_tag('div', array('class' => 'options'));
        $formcontent .= html_writer::tag('div', get_string('selectmoduletoviewhelp', 'moodle'),
                array('class' => 'instruction'));
        // Put all options into one tag 'alloptions' to allow us to handle scrolling
        $formcontent .= html_writer::start_tag('div', array('class' => 'alloptions'));

         // Activities
        $activities = array_filter($modules, function($mod) {
            return ($mod->archetype !== MOD_ARCHETYPE_RESOURCE && $mod->archetype !== MOD_ARCHETYPE_SYSTEM);
        });
        if (count($activities)) {
            $formcontent .= $this->course_modchooser_title('activities');
            $formcontent .= $this->course_modchooser_module_types($activities);
        }

        // Resources
        $resources = array_filter($modules, function($mod) {
            return ($mod->archetype === MOD_ARCHETYPE_RESOURCE);
        });
        if (count($resources)) {
            $formcontent .= $this->course_modchooser_title('resources');
            $formcontent .= $this->course_modchooser_module_types($resources);
        }

        $formcontent .= html_writer::end_tag('div'); // modoptions
        $formcontent .= html_writer::end_tag('div'); // types

        $formcontent .= html_writer::start_tag('div', array('class' => 'submitbuttons'));
        $formcontent .= html_writer::tag('input', '',
                array('type' => 'submit', 'name' => 'submitbutton', 'class' => 'submitbutton', 'value' => get_string('add')));
        $formcontent .= html_writer::tag('input', '',
                array('type' => 'submit', 'name' => 'addcancel', 'class' => 'addcancel', 'value' => get_string('cancel')));
        $formcontent .= html_writer::end_tag('div');
        $formcontent .= html_writer::end_tag('form');

        // Wrap the whole form in a div
        $formcontent = html_writer::tag('div', $formcontent, array('id' => 'chooseform'));

        // Put all of the content together
        $content = $formcontent;

        $content = html_writer::tag('div', $content, array('class' => 'choosercontainer'));
        return $header . html_writer::tag('div', $content, array('class' => 'chooserdialoguebody'));
    }
    protected function course_modchooser_module($module, $classes = array('option')) {
        $output = html_writer::start_tag('div', array('class' => implode(' ', $classes)));
        // TODO: put all text in the checkbox label?
        if (!isset($module->types)) {
            $output .= form::radio('jumplink', 'module_' . $module->name, '', $module->link);
        }

        if (isset($module->icon)) {
            $output .= html::span('modicon', $module->icon);
        } else {
            $output .= html::span('modicon'); // TODO: do we need this if empty?
        }

        $output .= html::span('typename', $module->title);

        if (!isset($module->help)) {
            $output .= html::span('typesummary', get_string('nohelpforactivityorresource'));
        } else {
            $options = new stdClass();
            $options->trusted = false;
            $options->noclean = false;
            $options->smiley = false;
            $options->filter = false;
            $options->para = true;
            $options->newlines = false;
            $options->overflowdiv = false;
            $module->help = format_text($module->help, FORMAT_MARKDOWN, $options);
            $output .= html::span('typesummary', $module->help);
        }
        $output .= '</div>';
        return $output;
    }
}
