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

class theme_bootstrap_renderers_core_renderer extends core_renderer {
    // Trying to keep the order of definition the same as
    // the source file, lib/outputrenderers.php.

    public function doctype() {
        $this->contenttype = 'text/html; charset=utf-8';
        return "<!DOCTYPE html>\n";
    }
    public function htmlattributes() {
        $parts = explode(' ', trim(get_html_lang(true)));
        return $parts[0] . ' ' . $parts[1]; // Ditch xml:lang part.
    }

    /* public function standard_head_html() {} */
    // Lots of stuff going on here, should really be split up.

    // public function standard_footer_html() {}
    // Same as head, should be split.

    // public function main_content() {}
    // Could be a chance to wrap the main_content div.

    public function login_info() {
        // This could probably be tidied up
        // bit confusing at the moment
        //
        // also gets outputted in header and footer
        // by default, probably want to do entirely different
        // things in each place.

        global $USER, $CFG, $DB, $SESSION, $OUTPUT;

        if (during_initial_install()) {
            return '';
        }

        $loginpage = ((string)$this->page->url === get_login_url());
        $course = $this->page->course;

        if (session_is_loggedinas()) {
            $realuser = session_get_realuser();
            $fullname = fullname($realuser, true);
            $realuserinfo = "[<a href=\"$CFG->wwwroot/course/loginas.php?id=$course->id&amp;sesskey=".
                sesskey()."\" class=navbar-link>$fullname</a>]";
        } else {
            $realuserinfo = '';
        }

        $loginurl = get_login_url();

        if (empty($course->id)) {
            // During installation.
            return '';
        } else if (isloggedin()) {
            $context = get_context_instance(CONTEXT_COURSE, $course->id);

            $fullname = fullname($USER, true);
            $username = "<a href=\"$CFG->wwwroot/user/profile.php?id=$USER->id\" class=navbar-link>$fullname</a>";
            if (is_mnet_remote_user($USER) and $idprovider = $DB->get_record('mnet_host', array('id'=>$USER->mnethostid))) {
                $username .= " from <a href=\"{$idprovider->wwwroot}\" class=navbar-link>{$idprovider->name}</a>";
            }
            if (isguestuser()) {
                $loggedinas = $realuserinfo.get_string('loggedinasguest');
                if (!$loginpage) {
                    $loggedinas .= " (<a href=\"$loginurl\" class=navbar-link>".get_string('login').'</a>)';
                }
            } else if (is_role_switched($course->id)) { // Has switched roles.
                $rolename = '';
                if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                    $rolename = ': '.format_string($role->name);
                }
                $loggedinas = get_string('loggedinas', 'moodle', $username).$rolename.
                    " (<a href=\"$CFG->wwwroot/course/view.php?id=$course->id&amp;switchrole=0&amp;sesskey=".
                    sesskey()."\" class=navbar-link>".get_string('switchrolereturn').'</a>)';
            } else { // Normal user.
                $userpic = $OUTPUT->user_picture( $USER, array(
                    'size'=>26,
                    'link'=>false,
                    'class'=>'img-circle',
                ));
                $logout = $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey();
                $loggedinas = '<p class="navbar-text pull-right">' . $userpic . ' ' . $realuserinfo .
                    get_string('loggedinas', 'moodle', $username).' '.
                    '<a href="' . $logout . '" class=label>'.get_string('logout').'</a></p>';
            }
        } else {
            if ($loginpage) {
                $loggedinas = '<div class="navbar-text pull-right">' . get_string('loggedinnot', 'moodle');
            } else {
                $loggedinas = '<form action="' . $CFG->wwwroot . '/login/index.php?authldap_skipntlmsso=1" class="navbar-form pull-right" method=post>';
                $loggedinas .= '<input class="span2" name=username type="text" placeholder="' . get_string('username') . '">';
                $loggedinas .= '<input class="span2" name=password type="password" placeholder="'. get_string('password') . '">';
                $loggedinas .= '<button type="submit" class="btn">'.get_string('login').'</button></form>';
            }
        }

        if (isset($SESSION->justloggedin)) {
            unset($SESSION->justloggedin);
            if (!empty($CFG->displayloginfailures)) {
                if (!isguestuser()) {
                    if ($count = count_login_failures($CFG->displayloginfailures, $USER->username, $USER->lastlogin)) {
                        $loggedinas .= '&nbsp;<div class="loginfailures">';
                        if (empty($count->accounts)) {
                            $loggedinas .= get_string('failedloginattempts', '', $count);
                        } else {
                            $loggedinas .= get_string('failedloginattemptsall', '', $count);
                        }
                        if (file_exists("$CFG->dirroot/report/log/index.php") and
                            has_capability('report/log:view', get_context_instance(CONTEXT_SYSTEM))) {
                            $loggedinas .= ' (<a href="'.$CFG->wwwroot.'/report/log/index.php'.
                                '?chooselog=1&amp;id=1&amp;modid=site_errors" class=navbar-link>'.get_string('logs').'</a>)';
                        }
                        $loggedinas .= '</div>';
                    }
                }
            }
        }

        return $loggedinas;
    }

    public function home_link() {
        global $CFG, $SITE;
        $text = '';
        $linktext = 'Moodle';

        if ($this->page->pagetype == 'site-index') {
            $div_attributes['class'] = "sitelink";
            $text = 'Made with ';
            $a_attributes['href'] = 'http://moodle.org/';
            $a_attributes['class'] = 'label';
            $a_attributes['style'] = 'background-color: orange;';
        } else if (!empty($CFG->target_release) &&
                $CFG->target_release != $CFG->release) {
            // Special case for during install/upgrade.
            $div_attributes['class'] = "sitelink";
            $text = 'help with ';
            $a_attributes['href'] = 'http://docs.moodle.org/en/Administrator_documentation';
            $a_attributes['target'] = '_blank';
        } else if ($this->page->course->id == $SITE->id ||
                strpos($this->page->pagetype, 'course-view') === 0) {
            $div_attributes['class'] = "homelink";
            $linktext = get_string('home');
            $a_attributes['href'] = $CFG->wwwroot . '/';
        } else {
            $div_attributes['class'] = "homelink";
            $linktext = format_string($this->page->course->shortname, true, array('context' => $this->page->context));
            $a_attributes['href'] = $CFG->wwwroot . '/course/view.php?id=' . $this->page->course->id;
        }
        return html::div($div_attributes, $text . html::a($a_attributes, $linktext));
    }

    // public function redirect_message($encodedurl, $message, $delay, $debugdisableredirect) {
    // There's an error message that could be bootstrapped, but it's buried
    // under a lot of other stuff, low priority I think.

    public function lang_menu() {
        global $CFG;

        if (empty($CFG->langmenu)) {
            return '';
        }

        if ($this->page->course != SITEID and !empty($this->page->course->lang)) {
            return '';
        }

        $langs = get_string_manager()->get_list_of_translations();
        $langcount = count($langs);

        if ($langcount < 2) {
            return '';
        } else {
            $output = '';
            $currlang = current_language();
            $output .= html::li('divider', '');
            $output .= html::li('nav-header', 'STRING: TITLE(RENDERER)');
            foreach ($langs as $code => $title) {
                $href = new moodle_url($this->page->url, array('lang'=>$code));
                $lang = '';
                if ($code !== $currlang) {
                    $attributes['class'] = "langlink $code";
                    $attributes['title'] = $title;
                    $lang = html_writer::link($href, $title, $attributes);
                } else {
                    $lang = html::span("currlang $code", $title);
                }
                $output .= html::li('navbar-text', $lang);
            }
            return $output;
        }
    }

    public function block_controls($controls) {
        if (empty($controls)) {
            return '';
        }
        $controlshtml = array();
        foreach ($controls as $control) {
            $controlshtml[] = html::a(array('href'=>$control['url'], 'title'=>$control['caption']), moodle::icon($control['icon']));
        }
        return html::div('commands', implode(' ', $controlshtml));
    }

    public function block(block_contents $bc, $region) {
        // Trying to make each block a list, first item the header, second items controls,
        // then if content is a list just join on and close the ul in the footer
        // don't know if it'll work, Boostrap just expects simple lists.

        // Rename class invisible to dimmed.
        $bc->attributes['class'] = classes::replace($bc->attributes['class'], array('invisible'=>'muted'));

        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }
        $bc->add_class('well'); // Bootstrap style.
        // Bit strange of bootstrap to hard code the style below but that's what the example does.
        $bc->attributes['style'] = 'padding: 8px 0;';

        $skiptitle = strip_tags($bc->title);
        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle),
                array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
            $skipdest = html::span(array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'), '');
        }

        $output .= html::div($bc->attributes, $this->block_header($bc) . $this->block_content($bc));

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    protected function block_header(block_contents $bc) {
        $output = html::ul_open('nav nav-list');

        if ($bc->title) {
            $output .= html::li('nav-header', $bc->title);
        }

        if ($bc->controls) {
            $output .= html::li('',  $this->block_controls($bc->controls));
        }

        return $output;
    }

    protected function block_content(block_contents $bc) {
        // Probably only working for lists at the moment.
        $output = $bc->content;
        $output .= $this->block_footer($bc);

        return $bc->content . $this->block_footer($bc);
    }

    protected function block_footer(block_contents $bc) {
        $output = '';
        if ($bc->footer) {
            $output .= html::li('', $bc->footer);
        }
        return "$output</ul>";
    }

    public function list_block_contents($icons, $items) {
        // Currently just ditches icons rather than convert them.
        return html::li_implode($items);
    }

    public function action_icon($url, pix_icon $pixicon, component_action $action = null, array $attributes = null, $linktext=false) {
        if (!($url instanceof moodle_url)) {
            $url = new moodle_url($url);
        }
        $attributes = (array)$attributes;

        if (empty($attributes['class'])) {
            $attributes['class'] = 'action-icon';
        }

        $icon = $this->render($pixicon);

        $attributes['title'] = $pixicon->attributes['alt'];

        if ($linktext) {
            $text = $pixicon->attributes['alt'];
        } else {
            $text = '';
        }

        return $this->action_link($url, $text.$icon, $action, $attributes);
    }

    public function confirm($message, $continue, $cancel) {
        // This is used when upgrading (and possibly elsewhere) confusingly it's outputting
        // two different forms for a pair of continue/cancel buttons.
        // will try outputting a single form, with the continue button
        // submitting and the cancel button actually being a link
        //
        // On the upgrade screen at least, the cancel button doesn't seem to do anything.

        $continue = $this->make_button($continue, 'continue', 'post');
        $cancel = $this->make_button_link($cancel, 'cancel');

        $output = $this->render($continue);
        $output = strstr($output, '</form>', true); // Cut off final </form> tag.
        $output = "<p>$message</p><p>$output $cancel</form></p>";
        return bootstrap::alert_block($output);
    }
    private function make_button($button, $text, $method='post') {
        if ($button instanceof single_button) {
            return $button;
        } else if (is_string($button)) {
            return new single_button(new moodle_url($button), get_string($text), $method);
        } else if ($button instanceof moodle_url) {
            return new single_button($button, get_string($text), $method);
        } else {
            throw new coding_exception(
                'The $button param to make_button() must be either a URL (string/moodle_url) or a single_button instance.');
        }
    }
    private function make_button_link($button, $text) {
        $text = get_string($text);

        if ($button instanceof single_button) {
            $attributes['href'] = $button->url;
        } else if (is_string($button)) {
            $attributes['href'] = $button;
        } else if ($button instanceof moodle_url) {
            $attributes['href'] = $button;
        } else {
            throw new coding_exception(
                'The $button param to make_button_link() must be either a URL (string/moodle_url) or a single_button instance.');
        }
        $attributes['class'] = 'btn btn-warning';
        return html::a($attributes, $text);
    }

    protected function render_single_button(single_button $button) {
        // Just because it says "single_botton" doesn't mean it's going to be rendered on it's own
        // but it does mean it gets it's own unique form and a div round it.

        $attributes = array(
                'title'    => $button->tooltip,
                'class'    => classes::add($button->class, 'btn btn-primary'),
                'value'    => $button->label,
                'disabled' => $button->disabled ? 'disabled' : null,
            );

        // Should look at button->class and translate to Bootstrap
        // button types e.g. primary, info, success, warning, danger, inverse
        // and sizes like large, small, mini, block-level, or link
        // not sure how best to get a comprehensive list of button classes
        // in moodle, so for now appending their class to tooltip
        // maybe their id, type or value might work better?
        //
        // Found so far:
        // .singlebutton -> .btn?
        // .continuebutton -> .btn-primary?

        if ($button->actions) {
            $id = html_writer::random_id('single_button');
            $attributes['id'] = $id;
            foreach ($button->actions as $action) {
                $this->add_action_handler($action, $id);
            }
        }

        $output = html::submit($attributes);

        if ($button->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        $output .= html::hidden_inputs($button->url->params());

        if ($button->method === 'get') {
            $url = $button->url->out_omit_querystring(true);
        } else {
            $url = $button->url->out_omit_querystring();
        }
        if ($url === '') {
            $url = '#';
        }
        $attributes = array(
                'method' => $button->method,
                'action' => $url,
                'id'     => $button->formid);
        return html::form($attributes, $output);
    }

    protected function render_single_select(single_select $select) {
        $select = clone($select);
        if (empty($select->formid)) {
            $select->formid = html_writer::random_id('single_select_f');
        }

        $params = $select->url->params();
        if ($select->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        $output = html::hidden_inputs($params);

        if (empty($select->attributes['id'])) {
            $select->attributes['id'] = html_writer::random_id('single_select');
        }

        if ($select->disabled) {
            $select->attributes['disabled'] = 'disabled';
        }

        if ($select->tooltip) {
            $select->attributes['title'] = $select->tooltip;
        }

        if ($select->label) {
            $output .= html_writer::label($select->label, $select->attributes['id'], false, $select->labelattributes);
        }

        if ($select->helpicon instanceof help_icon) {
            $output .= $this->render($select->helpicon);
        } else if ($select->helpicon instanceof old_help_icon) {
            $output .= $this->render($select->helpicon);
        }
        $output .= html_writer::select($select->options, $select->name, $select->selected, $select->nothing, $select->attributes);

        $go = html_writer::empty_tag('input', array('class'=>'btn', 'type'=>'submit', 'value'=>get_string('go')));
        $output .= html_writer::tag('noscript', html_writer::tag('div', $go), array('style'=>'inline'));

        $nothing = empty($select->nothing) ? false : key($select->nothing);
        $this->page->requires->js_init_call('M.util.init_select_autosubmit', array($select->formid, $select->attributes['id'], $nothing));

        $output = html_writer::tag('div', $output);

        if ($select->method === 'get') {
            $url = $select->url->out_omit_querystring(true); // url without params, the anchor part allowed
        } else {
            $url = $select->url->out_omit_querystring();     // url without params, the anchor part not allowed
        }
        $form_attributes = array('method' => $select->method,
                                'class' => 'form-inline',
                                'action' => $url,
                                'id'     => $select->formid);
        $output = html::form($form_attributes, $output);

        return html::div($select->class, $output);
    }

    // protected function render_url_select(url_select $select) {
    // Probably needs a .form-inline for the 'go' button
    // but too scary to deal with right now.

    public function doc_link($path, $text = '') {
        $attributes['href'] = new moodle_url(get_docs_url($path));
        if ($text == '') {
            $linktext = bootstrap::icon_help();
        } else {
            $linktext = bootstrap::icon_help().' '.$text;
        }
        return html::a($attributes, $linktext);
    }

    protected function render_pix_icon(pix_icon $icon) {

        if (isset(moodle::$icons[$icon->pix])) {
            return moodle::icon($icon->pix);
            // Currently throws away any attributes attached to
            // the icon, like alt, which could be rendered
            // using .hide-text image replacement technique.

            // Also doesn't look at the $icon->component, so all mod
            // icons for example look the same as pix == 'icon'.
        } else {
            return parent::render_pix_icon($icon);
        }
    }

    // function render_rating(rating $rating) {
    // Theres some buttons and form labels in here that
    // could be restyled with .btn and .form-inline probably.

    public function heading_with_help($text, $helpidentifier, $component = 'moodle', $icon = '', $iconalt = '') {
        $help = '';
        if ($helpidentifier) {
            $help = $this->help_icon($helpidentifier, $component);
        }

        return "<h2>$text $help</h2>";
    }

    protected function render_help_icon(help_icon $helpicon) {
        global $CFG;

        $title = get_string($helpicon->identifier, $helpicon->component);

        if (empty($helpicon->linktext)) {
            $alt = get_string('helpprefix2', '', trim($title, ". \t"));
        } else {
            $alt = get_string('helpwiththis');
        }

        $output = bootstrap::icon_help();

        // add the link text if given
        if (!empty($helpicon->linktext)) {
            $output .= ' '.$helpicon->linktext;
        }

        // now create the link around it - we need https on loginhttps pages
        $url = new moodle_url($CFG->httpswwwroot.'/help.php', array('component' => $helpicon->component, 'identifier' => $helpicon->identifier, 'lang'=>current_language()));

        // note: this title is displayed only if JS is disabled, otherwise the link will have the new ajax tooltip
        $title = get_string('helpprefix2', '', trim($title, ". \t"));

        $attributes = array('href'=>$url, 'title'=>$title);
        $id = html_writer::random_id('helpicon');
        $attributes['id'] = $id;
        $output = html_writer::tag('a', $output, $attributes);

        $this->page->requires->js_init_call('M.util.help_icon.add', array(array('id'=>$id, 'url'=>$url->out(false))));

        return html::span('helplink', $output);
        // Final span probably unnecessary but leaving it in case the js needs it.
    }

    public function spacer(array $attributes = null, $br = false) {
        return bootstrap::icon_spacer();
        // Don't bother outputting br's or attributes.
    }

    // protected function render_user_picture(user_picture $userpicture) {
    // Could add a nice frame effect on the image.

    // public function render_file_picker(file_picker $fp) {
    // There's a button in here, but it appears to be display:none'd.

    public function error_text($message) {
        if (empty($message)) {
            return '';
        }
        return bootstrap::alert_error($message);
    }

    // public function fatal_error($message, $moreinfourl, $link, $backtrace, $debuginfo = null) {
    // There's some error notices that could be put in alerts here.

    public function notification($message, $classes = null) {
        // TODO rewrite recognized classnames to bootstrap alert equivalent
        // only two are mentioned in documentation, there may be more.

        $message = clean_text($message);

        if ($classes == 'notifyproblem') {
            return bootstrap::alert_error($message);
        }
        if ($classes == 'notifysuccess') {
            return bootstrap::alert_success($message);
        }
        return bootstrap::alert_default($message);
    }

    // public function continue_button($url) {
    // Not sure we need a class on this,
    // but doesn't seem worth rewriting just for that.

    // These should all really be in the pagingbar object to save
    // passing the paramaters about all over.
    private function previous_link($baseurl, $pagevar, $current_page) {
        $previous = get_string('previous');
        if ($current_page == 0) {
            return html::li('disabled', "<span>$previous</span>");
        }
        return html::li('', html_writer::link(new moodle_url($baseurl, array($pagevar=>$current_page-1)), $previous));
    }
    private function next_link($baseurl, $pagevar, $current_page, $last_page) {
        $next = get_string('next');
        if ($current_page == $last_page) {
            return html::li('disabled', "<span>$next</span>");
        }
        return html::li ('', html_writer::link(new moodle_url($baseurl, array($pagevar=>$current_page+1)), $next));
    }
    private function pagination_link($baseurl, $pagevar, $current_page, $target) {
        $targetname = $target + 1;
        if ($target == $current_page) {
            return html::li('active', "<span>$targetname</span>");
        }
        return html::li('', html_writer::link(new moodle_url($baseurl, array($pagevar=>$target)), $targetname));
    }

    private function skipped_link() {
        return html::li('disabled', '<span>…</span>');
    }

    protected function render_paging_bar(paging_bar $pagingbar) {
        // This is more complicated than it needs to be, see MDL-35367 for more.
        $pagingbar = clone($pagingbar);
        $pagingbar->prepare($this, $this->page, $this->target);

        $perpage = $pagingbar->perpage;
        $total = $pagingbar->totalcount;
        $show_pagingbar = ($perpage > 0 && $total > $perpage);
        if (!$show_pagingbar) {
            return '';
        }

        $baseurl = $pagingbar->baseurl;
        $pagevar = $pagingbar->pagevar;
        $current_page = (int)$pagingbar->page;

        // Note: page 0 is displayed to users as page 1 and so on.
        $lastpage = floor(($total - 1) / $perpage);

        // Display a max of $padding*2 + 1 links.
        $padding = 4;
        $near_to_start = ($current_page - $padding) < 1;
        $near_to_end = ($current_page + $padding) > $lastpage;

        if (!$near_to_start && !$near_to_end) {
            $skip[1] = $current_page - $padding + 2;
            $skip[($current_page + $padding) - 1] = $lastpage;
        } else if ($near_to_end) {
            $skip[1] = $lastpage - (2*$padding) + 2;
        } else if ($near_to_start) {
            $skip[2*$padding-1] = $lastpage;
        }

        $links[] = $this->previous_link($baseurl, $pagevar, $current_page);
        for ($i = 0; $i <= $lastpage; $i++) {
            if (isset($skip[$i])) {
                $links[] = $this->skipped_link();
                $i = $skip[$i];
            }
            $links[] = $this->pagination_link($baseurl, $pagevar, $current_page, $i);
        }
        $links[] = $this->next_link($baseurl, $pagevar, $current_page, $lastpage);
        return bootstrap::pagination(implode($links));
    }

    // public function skip_link_target($id = null) {
    // I think this should usually point to an id on the actual
    // content rather than an extra span stuck in before it, but
    // that's not really Bootstrap related.

    // public function heading($text, $level = 2, $classes = 'main', $id = null) {
    // Might be nice to allow Bootstrap-style sub-headings using <small>
    // or maybe that works anyway if you put the tags in the header text?

    // public function box($contents, $classes = 'generalbox', $id = null) {
    // 99% of these could probably be replaced with a classless div
    // maybe only output classes and ids if specified?

    // public function container($contents, $classes = null, $id = null) {
    // Not sure of semantic difference between container, box and div.


    // public function tree_block_contents($items, $attrs = array()) {
    // Looks important, but a lot going on.

    public function navbar() {
        // Bit of a nameclash, Bootstrap calls the navbar the breadcrumb and
        // also have a sperate thing called navbar that sticks to the top of the page.

        $items = $this->page->navbar->get_items();
        foreach ($items as $item) {
            $item->hideicon = true;
            $links[] = $this->render($item);
        }
        return bootstrap::breadcrumb($links);
    }

    protected function render_custom_menu(custom_menu $menu) {
        if (!$menu->has_children()) {
            return '';
        }
        $content  = '<div class="navbar navbar-fixed-top">' .
        '<div class=navbar-inner>' .
        '<div class=container>' .
        '<ul class=nav>';

        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item);
        }
        $content .= '</ul></div></div><div>';
        return $content;
    }

    protected function render_custom_menu_item(custom_menu_item $menunode) {
        static $submenucount = 0;

        if ($menunode->has_children()) {
            $content = '<li class=dropdown>';
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }

            // $content .= html_writer::link($url, $menunode->get_text(), array('title'=>,));
            $content .= '<a href="'.$url.'" class=dropdown-toggle data-toggle=dropdown>';
            $content .= $menunode->get_title();
            $content .= '<b class=caret></b></a>';
            $content .= '<ul class=dropdown-menu>';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem.

            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title'=>$menunode->get_title()));
        }
        $content .= '</li>';
        return $content;
    }

}
