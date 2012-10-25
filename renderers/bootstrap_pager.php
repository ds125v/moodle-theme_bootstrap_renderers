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
 * A class for creating a short list of paging links. Pages are numbered
 * starting from one everywhere except the URL, where page=0 refers to
 * the first page.
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('classes.php');

class bootstrap_pager {
    private $base_url;
    private $current_page;
    private $total_pages;

    public function __construct($base_url, $current_page, $total_pages, $pagevar='page') {
        $this->base_url = $base_url;
        $this->current_page = $current_page;
        $this->total_pages = $total_pages;
        $this->pagevar = $pagevar;
    }

    public function for_pages($page_numbers) {
        $output[] = $this->previous();
        foreach ($page_numbers as $number) {
            $output[] = $this->for_page($number);
        }
        $output[] = $this->next();
        return $output;
    }
    private function for_page($page, $text=null) {
        if ($page === 0) {
            die('paging links are numbered starting from 1');
        }
        if ($page === 'skip') {
            return $this->skipped();
        }
        if ($page == $this->current_page) {
            return $this->current();
        }
        if ($text === null) {
            $text = $page;
        }
        $attributes['href'] = $this->url_for_page($page);
        return html::li(html::a($attributes, $text));
    }
    private function url_for_page($page) {
        return $this->base_url .'&'. $this->pagevar .'='. ($page-1);
    }

    private function current() {
        return html::li('active', html::span($this->current_page));
    }

    private function previous() {
        $text = get_string('previous');
        if ($this->current_page == 1) {
            return html::li('disabled', html::span($text));
        } else {
            $page = $this->current_page - 1;
            return $this->for_page($page, $text);
        }
    }

    private function next() {
        $text = get_string('next');
        if ($this->current_page == $this->total_pages) {
            return html::li('disabled', html::span($text));
        } else {
            $page = $this->current_page + 1;
            return $this->for_page($page, $text);
        }
    }
    private function skipped() {
        return html::li('disabled', html::span('...'));
    }
}
