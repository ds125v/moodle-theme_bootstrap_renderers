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

    public function heading_with_help($text, $helpidentifier, $component = 'moodle', $icon = '', $iconalt = '') {
        $help = '';
        if ($helpidentifier) {
            $help = $this->help_icon($helpidentifier, $component);
        }

        return "<h2>$text $help</h2>";
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
}
