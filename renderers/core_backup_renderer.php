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

require_once("form.php");
require_once($CFG->dirroot . "/backup/util/ui/renderer.php");

class theme_bootstrap_renderers_core_backup_renderer extends core_backup_renderer {


    public function progress_bar(array $items) {
        foreach ($items as $step) {
            if ($step['class'] == 'backup_stage backup_stage_current') {
                $steps[] =  html::span(label::inverse($step['text']));
            } else {
                $steps[] =  html::span($step['text']);
            }
        }
        return html::ul('breadcrumb', '<li>' . implode('&nbsp;>&nbsp;</li><li>', $steps). '</li>');
        //TODO: there's better bootstrap wizard progress out there, but this'll do for now
    }
    protected static function _($text) {
        return get_string($text, 'backup');
    }
    protected function backup_detail_pair($label, $value) {
        return form::row($label, $value);
    }
    protected function backup_detail_input($label, $type, $name, $value, array $attributes=array(), $description=null) {
        return form::row(get_string($label, 'backup'), html::input($attributes+array('name'=>$name, 'type'=>$type, 'value'=>$value)),$description);
    }
    protected function backup_detail_radio($label, $name, $value, $checked=false, $description=null) {
        $attributes = array('name' => $name, 'value' => $value, 'type' => 'radio', 'checked' => $checked);
        return form::row(self::_($label), html::input($attributes), $description);
        //TODO add for/id to make clickable, shift logic into form class
    }
    protected function backup_detail_display($label, $value) {
        return form::row(self::_($label), form::uneditable(self::_($label.$value)));
    }
    protected function backup_detail_item($label, $value) {
        return form::row(self::_($label), form::uneditable($value));
    }
    protected function backup_detail_yes_no($label, $value) {
        return form::row(self::_($label), form::uneditable($value?label::yes():label::no()));
    }
    protected function backup_detail_extra_info($label, $value, $extra) {
        return form::row(self::_($label), form::uneditable($value), html::small($extra));
    }
    public function dependency_notification($message) {
        return bootstrap::alert_error($message);
    }
    protected function backup_details_list($details) {
        $out = $this->backup_detail_display('backuptype', $details->type);
        $out .= $this->backup_detail_display('backupformat', $details->format);
        $out .= $this->backup_detail_display('backupmode', $details->mode);
        $out .= $this->backup_detail_item('backupdate', userdate($details->backup_date));
        $out .= $this->backup_detail_extra_info('moodleversion', $details->moodle_release, $details->moodle_version);
        $out .= $this->backup_detail_extra_info('backupversion', $details->backup_release, $details->backup_version);
        $out .= $this->backup_detail_extra_info('originalwwwroot', $details->original_wwwroot, $details->original_site_identifier_hash);
        if (!empty($details->include_file_references_to_external_content)) {
            $message = '';
            if (backup_general_helper::backup_is_samesite($details)) {
                $message = label::yes() . ' ' . get_string('filereferencessamesite', 'backup');
            } else {
                $message = label::no() . ' ' . get_string('filereferencesnotsamesite', 'backup');
            }
            $out .= $this->backup_detail_item('includefilereferences', $message);
        }
        return $out;
    }
    protected function backup_settings($details) {
        $out = '';
        foreach ($details->root_settings as $label => $value) {
            if ($label == 'filename' or $label == 'user_files') {
                continue;
            }
            $out .= $this->backup_detail_yes_no('rootsetting'.str_replace('_', '', $label), $value);
        }
        return $out;
    }
    protected function backup_course_details($details) {
        return $this->backup_detail_item('coursetitle', $details->course->title) .
            $this->backup_detail_item('courseid', $details->course->courseid);
    }
    protected function backup_course_sections($details) {
        foreach ($details->sections as $key => $section) {
            $included = $key.'_included';
            if (!$section->settings[$included]) {
                continue;
            }
            $userinfo = $key.'_userinfo';
            if ($section->settings[$userinfo]) {
                $userinfo = label::yes();
            } else {
                $userinfo = label::no();
            }
            $form_section = $this->backup_detail_item('includeuserinfo', $userinfo);
            $table = null;
            foreach ($details->activities as $activitykey => $activity) {
                if ($activity->sectionid != $section->sectionid) {
                    continue;
                }
                if (empty($table)) {
                    $table = new html_table();
                    $table->head = array('Module', 'Title', 'Userinfo');
                    //TODO: the above aren't translateable, see MDL-37211
                    $table->colclasses = array('modulename', 'moduletitle', 'userinfoincluded');
                    $table->align = array('left', 'left', 'center');
                    $table->attributes = array('class'=>'table table-striped');
                    $table->data = array();
                }
                $name = get_string('pluginname', $activity->modulename);
                $icon = new pix_icon('icon', $name, $activity->modulename);
                $table->data[] = array(
                    $this->output->render($icon).'&nbsp;'.$name,
                    $activity->title,
                    ($activity->settings[$activitykey.'_userinfo'])?label::yes():label::no(),
                );
            }
            if (!empty($table)) {
                $form_section .= form::row(get_string('sectionactivities', 'backup'), html_writer::table($table));
            }
            $out[] = form::section(get_string('backupcoursesection', 'backup', $section->title), $form_section);
        }
        return implode($out);
    }
    public function backup_details($details, $nextstageurl) {
        $form = form::section(get_string('backupdetails', 'backup'), $this->backup_details_list($details));
        $form .= form::section(get_string('backupsettings', 'backup'), $this->backup_settings($details));
        if ($details->type === 'course') {
            $form .= form::section(get_string('backupcoursedetails', 'backup'), $this->backup_course_details($details));
            $form .= $this->backup_course_sections($details);
        }

        $form .= form::actions(get_string('continue'));

        return form::moodle_url($nextstageurl, $form);
    }
    protected function restore_to_new_course($categories) {
        return form::section(get_string('restoretonewcourse', 'backup'),
            html::input_hidden('target', backup::TARGET_NEW_COURSE)
            . form::row(self::_('selectacategory'), $this->render($categories))
            . form::actions(get_string('continue'))
        );
    }
    protected function restore_to_current_course() {
        return form::section(get_string('restoretocurrentcourse', 'backup'),
            html::input_hidden('targetid', $currentcourse)
            . $this->backup_detail_radio('restoretocurrentcourseadding', 'target', backup::TARGET_CURRENT_ADDING, true)
            . $this->backup_detail_radio('restoretocurrentcoursedeleting', 'target', backup::TARGET_CURRENT_DELETING)
            . form::actions(get_string('continue'))
        );
    }
    protected function restore_to_existing_course($courses, $wholecourse) {
        $section = '';
        if ($wholecourse) {
            $section .= $this->backup_detail_radio('restoretoexistingcourseadding', 'target', backup::TARGET_EXISTING_ADDING, true)
                . $this->backup_detail_radio('restoretoexistingcoursedeleting', 'target', backup::TARGET_EXISTING_DELETING)
                . form::row(self::_('selectacourse'), $this->render($courses));
        } else {
            $courses->invalidate_results();
            $courses->set_include_currentcourse();
            $section .= html::input_hidden('target', backup::TARGET_EXISTING_ADDING)
                . $this->backup_detail_item('selectacourse', $this->render($courses));
        }
        $section .= form::actions(get_string('continue'));
        return form::section(get_string('restoretoexistingcourse', 'backup'), $section);
    }
    public function course_selector(moodle_url $nextstageurl, $wholecourse = true, restore_category_search $categories = null, restore_course_search $courses=null, $currentcourse = null) {
        global $CFG;
        require_once($CFG->dirroot.'/course/lib.php');

        $nextstageurl->param('sesskey', sesskey());

        $hasrestoreoption = false;
        $html = '';
        if ($wholecourse && !empty($categories) && ($categories->get_count() > 0 || $categories->get_search())) {
            $hasrestoreoption = true;
            $html .= form::moodle_url($nextstageurl, $this->restore_to_new_course($categories));
        }

        if ($wholecourse && !empty($currentcourse)) {
            $hasrestoreoption = true;
            $html .= form::moodle_url($nextstageurl, $this->restore_to_current_course());
        }

        if (!empty($courses) && ($courses->get_count() > 0 || $courses->get_search())) {
            $hasrestoreoption = true;
            $html .= form::moodle_url($nextstageurl, $this->restore_to_existing_course($courses, $wholecourse));
        }

        if (!$hasrestoreoption) {
            $html .= bootstrap::alert_error(get_string('norestoreoptions', 'backup'));
        }
        return $html;
    }
    public function render_backup_files_viewer(backup_files_viewer $viewer) {
        $files = $viewer->files;

        $table = new html_table();
        $table->attributes['class'] = 'table table-striped table-hover';
        $table->head = array(get_string('filename', 'backup'), get_string('time'), get_string('size'), get_string('download'), get_string('restore'));
        $table->data = array();

        foreach ($files as $file) {
            if ($file->is_directory()) {
                continue;
            }
            $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), null, $file->get_filepath(), $file->get_filename(), true);
            $params = array();
            $params['action'] = 'choosebackupfile';
            $params['filename'] = $file->get_filename();
            $params['filepath'] = $file->get_filepath();
            $params['component'] = $file->get_component();
            $params['filearea'] = $file->get_filearea();
            $params['filecontextid'] = $file->get_contextid();
            $params['contextid'] = $viewer->currentcontext->id;
            $params['itemid'] = $file->get_itemid();
            $restoreurl = new moodle_url('/backup/restorefile.php', $params);
            $table->data[] = array(
                $file->get_filename(),
                userdate($file->get_timemodified()),
                display_size($file->get_filesize()),
                html_writer::link($fileurl, get_string('download')),
                html_writer::link($restoreurl, get_string('restore')),
                );
        }

        $html = html_writer::table($table);
        $html .= html::submit(array('value' => get_string('managefiles', 'backup'), 'class' => 'btn-primary'));
        $url = new moodle_url('/backup/backupfilesedit.php', array('currentcontext'=>$viewer->currentcontext->id, 'contextid'=>$viewer->filecontext->id, 'filearea'=>$viewer->filearea, 'component'=>$viewer->component, 'returnurl'=>$this->page->url->out()));
        return form::moodle_url($url, $html);
    }
    public function render_restore_course_search(restore_course_search $component) {


        $table = new html_table();
        $table->attributes['class'] = 'table table-striped table-hover';
        $table->head = array('', get_string('shortnamecourse'), get_string('fullnamecourse'));
        $table->data = array();
        if ($component->get_count() !== 0) {
            foreach ($component->get_results() as $course) {
                $row = new html_table_row();
                $row->attributes['class'] = 'rcs-course';
                if (!$course->visible) {
                    $row->attributes['class'] .= ' muted';
                }
                $row->cells = array(
                    html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'targetid', 'value'=>$course->id)),
                    format_string($course->shortname, true, array('context' => get_context_instance(CONTEXT_COURSE, $course->id))),
                    format_string($course->fullname, true, array('context' => get_context_instance(CONTEXT_COURSE, $course->id)))
                );
                $table->data[] = $row;
            }
            if ($component->has_more_results()) {
                $cell = new html_table_cell(get_string('moreresults', 'backup'));
                $cell->colspan = 3;
                $row = new html_table_row(array($cell));
                $row->attributes['class'] = 'rcs-course warning';
                $table->data[] = $row;
            }
        } else {
            $cell = new html_table_cell(get_string('nomatchingcourses', 'backup'));
            $cell->colspan = 3;
            $row = new html_table_row(array($cell));
            $row->attributes['class'] = 'rcs-course error';
            $table->data[] = $row;
        }
        $output .= html_writer::table($table);

        $output .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>restore_course_search::$VAR_SEARCH, 'value'=>$component->get_search()));
        $output .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'searchcourses', 'value'=>get_string('search')));

        return $output;
    }
    public function render_import_course_search(import_course_search $component) {

        $output = '';
        if ($component->get_count() === 0) {
            $output .= bootstrap::alert_error(get_string('nomatchingcourses', 'backup'));

            $output .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>restore_course_search::$VAR_SEARCH, 'value'=>$component->get_search()));
            $output .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'searchcourses', 'value'=>get_string('search')));

            return $output;
        }

        $output .= html_writer::tag('div', get_string('totalcoursesearchresults', 'backup', $component->get_count()), array('class'=>'ics-totalresults'));

        $table = new html_table();
        $table->attributes['class'] = 'table table-striped table-hover';
        $table->head = array('', get_string('shortnamecourse'), get_string('fullnamecourse'));
        $table->data = array();
        foreach ($component->get_results() as $course) {
            $row = new html_table_row();
            $row->attributes['class'] = 'ics-course';
            if (!$course->visible) {
                $row->attributes['class'] .= ' muted';
            }
            $row->cells = array(
                html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'importid', 'value'=>$course->id)),
                format_string($course->shortname, true, array('context' => get_context_instance(CONTEXT_COURSE, $course->id))),
                format_string($course->fullname, true, array('context' => get_context_instance(CONTEXT_COURSE, $course->id)))
            );
            $table->data[] = $row;
        }
        $output .= html_writer::table($table);

        $output .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>restore_course_search::$VAR_SEARCH, 'value'=>$component->get_search()));
        $output .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'searchcourses', 'value'=>get_string('search')));

        $output .= html_writer::end_tag('div');
        return $output;
    }
    public function render_restore_category_search(restore_category_search $component) {

        $table = new html_table();
        $table->attributes['class'] = 'table table-striped table-hover';
        $table->head = array('', get_string('name'), get_string('description'));
        $table->data = array();

        if ($component->get_count() !== 0) {
            foreach ($component->get_results() as $category) {
                $row = new html_table_row();
                $row->attributes['class'] = 'rcs-course';
                if (!$category->visible) {
                    $row->attributes['class'] .= ' muted';
                }
                $row->cells = array(
                    html_writer::empty_tag('input', array('type'=>'radio', 'name'=>'targetid', 'value'=>$category->id)),
                    format_string($category->name, true, array('context' => get_context_instance(CONTEXT_COURSECAT, $category->id))),
                    format_text($category->description, $category->descriptionformat, array('overflowdiv'=>true))
                );
                $table->data[] = $row;
            }
            if ($component->has_more_results()) {
                $cell = new html_table_cell(get_string('moreresults', 'backup'));
                $cell->colspan = 3;
                $row = new html_table_row(array($cell));
                $row->attributes['class'] = 'rcs-course warning';
                $table->data[] = $row;
            }
        } else {
            $cell = new html_table_cell(get_string('nomatchingcourses', 'backup'));
            $cell->colspan = 3;
            $row = new html_table_row(array($cell));
            $row->attributes['class'] = 'rcs-course error';
            $table->data[] = $row;
        }
        $output .= html_writer::table($table);

        $output .= html_writer::empty_tag('input', array('type'=>'text', 'name'=>restore_category_search::$VAR_SEARCH, 'value'=>$component->get_search()));
        $output .= html_writer::empty_tag('input', array('type'=>'submit', 'name'=>'searchcourses', 'value'=>get_string('search')));
        return $output;
    }
}
