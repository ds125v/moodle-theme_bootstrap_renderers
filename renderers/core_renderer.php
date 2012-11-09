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
require_once('bootsnipp.php');
require_once('bootstrap.php');
require_once('bootstrap_pager.php');
require_once('classes.php');
require_once('pager.php');

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

    public function login_info() {
        global $USER, $CFG, $DB, $SESSION;
		$mnet = null;
		$loginfailures = null;
		$real = null;
        if (during_initial_install()) {
            return '';
        }

        $course = $this->page->course;
        if (empty($course->id)) {
            // $course->id is not defined during installation
            return '';
        }

        if (session_is_loggedinas()) {
            $real_user = session_get_realuser();
            $real['name'] = fullname($real_user, true);
            $real['link'] = html::url("$CFG->wwwroot/course/loginas.php", array('id'=>$course->id, 'sesskey'=>sesskey()));
        } else {
            $real_info = null;
        }
        if (!isloggedin()) {
            return bootsnipp::sign_up_sign_in(new moodle_url('/login/index.php'));
        }

        $logout['link'] = html::url("$CFG->wwwroot/login/logout.php", array('sesskey'=>sesskey()));
        $logout['name'] = get_string('logout');

        $context = get_context_instance(CONTEXT_COURSE, $course->id);
        $user['name'] = fullname($USER, true);
        $user['link'] = html::url("$CFG->wwwroot/user/profile.php", array('id'=>$USER->id));

        if (is_mnet_remote_user($USER) and $idprovider = $DB->get_record('mnet_host', array('id'=>$USER->mnethostid))) {
            $mnet['link'] = $idprovider->wwwroot;
            $mnet['name'] = $idprovider->name;
        } else {
            $mnet_info = null;
        }

        if (isguestuser()) {
            $guest['link'] = get_login_url();
            $guest['name'] = get_string('login');
            return bootsnipp::guest_user($user['name'], $guest, $logout);
        }
        if (is_role_switched($course->id)) {
            if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                $user['name'] .= ': ' . format_string($role->name);
            }
            $role_switch['link'] = "$CFG->wwwroot/course/view.php?id=$course->id&switchrole=0&sesskey=" .sesskey();
            $role_switch['name'] = get_string('switchrolereturn');
        } else {
            $role_switch = null;
        }
        if (isset($SESSION->justloggedin)) {
            unset($SESSION->justloggedin);
            if (!empty($CFG->displayloginfailures) && !isguestuser()) {
                if (file_exists("$CFG->dirroot/report/log/index.php")
                    and has_capability('report/log:view', get_context_instance(CONTEXT_SYSTEM))) {
                    if ($count = count_login_failures($CFG->displayloginfailures, $USER->username, $USER->lastlogin)) {
                        $loginfailures['link'] = "$CFG->wwwroot/report/log/index.php?chooselog=1&id=1&modid=site_errors";
                        if (empty($count->accounts)) {
                            $loginfailures['name'] = get_string('failedloginattempts', '', $count);
                        } else {
                            $loginfailures['name'] = get_string('failedloginattemptsall', '', $count);
                        }
                    }
                }
            }
        }
        return bootsnipp::signed_in($user, $loginfailures, $mnet, $real, $role_switch, $logout);
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

    public function block_controls($controls) {
        if (empty($controls)) {
            return '';
        }
        $controlshtml = array();
        foreach ($controls as $control) {
            $controlshtml[] = html::a(array('href'=>$control['url'], 'title'=>$control['caption']), bootstrap::replace_moodle_icon($control['icon']));
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
        $output = "<p>$message</p>$output $cancel</form>";
        return bootstrap::alert_block($output);
    }
    private function make_button($button, $text, $method='get') {
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

        if (bootstrap::replace_moodle_icon($icon->pix) !== false) {
            return bootstrap::replace_moodle_icon($icon->pix);
            // Currently throws away any attributes attached to
            // the icon, like alt, which could be rendered
            // using .hide-text image replacement technique.

            // Also doesn't look at the $icon->component, so all mod
            // icons for example look the same as pix == 'icon'.
        } else {
            return parent::render_pix_icon($icon);
        }
    }

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

        $output = html_writer::empty_tag('icon', array('class'=>'icon-question-sign'));

        // add the link text if given
        if (!empty($helpicon->linktext)) {
            // the spacing has to be done through CSS
            $output .= $helpicon->linktext;
        }

        // now create the link around it - we need https on loginhttps pages
        $url = new moodle_url($CFG->httpswwwroot.'/help.php', array('component' => $helpicon->component, 'identifier' => $helpicon->identifier, 'lang'=>current_language()));

        // note: this title is displayed only if JS is disabled, otherwise the link will have the new ajax tooltip
        $title = get_string('helpprefix2', '', trim($title, ". \t"));

        $attributes = array('href'=>$url, 'title'=>$title, 'aria-haspopup' => 'true');
        $id = html_writer::random_id('helpicon');
        $attributes['id'] = $id;
        $output = html_writer::tag('a', $output, $attributes);

        $this->page->requires->js_init_call('M.util.help_icon.add', array(array('id'=>$id, 'url'=>$url->out(false))));

        // and finally span
        return html_writer::tag('span', $output, array('class' => 'helplink'));
    }
	
    public function spacer(array $attributes = null, $br = false) {
        return bootstrap::icon_spacer();
        // Don't bother outputting br's or attributes.
    }

    public function error_text($message) {
        if (empty($message)) {
            return '';
        }
        return bootstrap::alert_error($message);
    }

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


    protected function render_paging_bar(paging_bar $pagingbar) {
        $pagingbar = clone($pagingbar);
        $pagingbar->prepare($this, $this->page, $this->target);

        $show_pagingbar = ($pagingbar->perpage > 0
                        && $pagingbar->totalcount > $pagingbar->perpage);
        if (!$show_pagingbar) {
            return '';
        }
        $current_page = $pagingbar->page + 1; // Counting from one, not zero.
        $pager = new pager($pagingbar->perpage, $pagingbar->totalcount, $current_page);
        $pages = $pager->pages();
        $pagination = new bootstrap_pager(html_entity_decode($pagingbar->baseurl), $current_page, $pager->last_page);
        return bootstrap::pagination($pagination->for_pages($pages));
    }

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
    public function custom_menu($custommenuitems = '') {
        global $CFG;
        if (empty($custommenuitems)) {
            if (empty($CFG->custommenuitems)) {
                return '';
            } else {
                $custommenuitems = $CFG->custommenuitems;
            }
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    protected function render_custom_menu(custom_menu $menu) {
        foreach ($menu->get_children() as $item) {
            $items[] = $this->render_custom_menu_item($item);
        }
        if (isset($items)) {
            return html::ul('nav', $items);
        } else {
            return '';
        }
    }

    protected function render_custom_menu_item(custom_menu_item $menunode, $submenu=null) {
        if ('list_divider' === $menunode->get_text()) {
            return bootstrap::list_divider();
        }
        if (!$menunode->has_children()) {
            return $this->render_custom_menu_leaf($menunode);
        }
        foreach ($menunode->get_children() as $child) {
            $items[] = $this->render_custom_menu_item($child, true);
        }
        if ($submenu === true) {
            return html::li(bootstrap::dropdown_submenu($menunode->get_text(), $items));
        } else {
            return html::li(bootstrap::dropdown_menu($menunode->get_text(), $items));
        }
    }
    private function render_custom_menu_leaf(custom_menu_item $menunode) {
        $icon = $menunode->get_title();
        if (strpos($icon, 'icon-') === 0) {
            $icon = substr($icon, 5);
        } else {
            $icon = '';
        }
        return bootstrap::li_icon_link($menunode->get_url(), $icon, $menunode->get_text());
    }
}
