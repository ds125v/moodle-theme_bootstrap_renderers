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
 * A class for creating a short list of paging links.
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('classes.php');

class Pagination {
    private $baseurl;
    private $pagevar;
    private $current_page;
    private $perpage;
    private $padding;

    private function url($page_num) {
        return new moodle_url($this->baseurl, array($this->pagevar=>$page_num));
    }
    public function previous_link() {
        $previous = get_string('previous');

        if ($this->current_page === 0) {
            return  $this->disabled_link($previous);
        }
        return html::li('', html_writer::link($this->url($this->current_page-1)), $previous);
    }
    private function next_link() {
        $next = get_string('next');

        if ($this->current_page === $this->last_page) {
            return  $this->disabled_link($next);
        }
        return html::li ('', html_writer::link($this->url($this->current_page+1)), $next);
    }
    private function pagination_link($target) {
        $targetname = $target + 1;

        if ($this->target === $this->current_page) {
            return html::li('active', "<span>$targetname</span>");
        }
        return html::li('', html_writer::link($this->_url($target)), $targetname);
    }
    private function skipped_link() {
        return  $this->disabled_link('…');
    }
    private function disabled_link($text) {
        return html::li('disabled', "<span>$text</span>");
    }

    public function __construct(paging_bar $pagingbar) {
        $pagingbar = clone($pagingbar);
        $pagingbar->prepare($this, $this->page, $this->target);
        $this->perpage = $pagingbar->perpage;
        $this->total = $pagingbar->totalcount;
        $this->baseurl = $pagingbar->baseurl;
        $this->pagevar = $pagingbar->pagevar;
        $this->current_page = (int)$pagingbar->page;
        // Note: page 0 is displayed to users as page 1 and so on.
        $this->lastpage = floor(($this->total - 1) / $this->perpage);
        // Display a max of $padding*2 + 1 links.
        $this->padding = 4;
    }
    private function show_pagingbar() {
        return ($this->perpage > 0) && ($this->total > $this->perpage);
    }
    public function render() {

        if (!$this->show_pagingbar()) {
            return '';
        }

        $near_to_start = ($this->current_page - $this->padding) < 1;
        $near_to_end = ($this->current_page + $this->padding) > $this->lastpage;

        if (!$near_to_start && !$near_to_end) {
            $skip[1] = $this->current_page - $this->padding + 2;
            $skip[($this->current_page + $this->padding) - 1] = $this->lastpage;
        } else if ($near_to_end) {
            $skip[1] = $this->lastpage - (2*$this->padding) + 2;
        } else if ($near_to_start) {
            $skip[2*$this->padding-1] = $this->lastpage;
        }

        $links[] = $this->previous_link();
        for ($i = 0; $i <= $this->lastpage; $i++) {
            if (isset($skip[$i])) {
                $links[] = $this->skipped_link();
                $i = $skip[$i];
            }
            $links[] = $this->pagination_link($i);
        }
        $links[] = $this->next_link();
        return bootstrap::pagination(implode($links));
    }
}
