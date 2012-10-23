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

class pager {
    private $padding;
    private $pages_to_show;
    private $last_page;
    private $current_page;

    public function __construct(
        $perpage,
        $total_items,
        $current_page,
        $padding = 4
    ) {
        $total_pages = ceil($total_items / $perpage);
        $this->last_page = $total_pages;
        $this->current_page = $current_page;
        $this->padding = $padding;
        $this->pages_to_show = ($padding * 2) + 1;
    }
    public function pages() {
        if ($this->last_page <= $this->pages_to_show) {
            return range(1, $this->last_page);
        } else {
            return $this->truncated_page_list();
        }
    }
    private function truncated_page_list() {
        if ($this->current_page_near_to_start()) {
            $end = array('skip', $this->last_page);
            $break_after = $this->pages_to_show - 2;
            $start = range(1, $break_after);
            return array_merge($start, $end);
        } else if ($this->current_page_near_to_end()) {
            $start = array(1, 'skip');
            $break_before = $this->last_page - $this->pages_to_show + 3;
            $end = range($break_before, $this->last_page);
            return array_merge($start, $end);
        } else {
            $start = array(1, 'skip');
            $end = array('skip', $this->last_page);
            $padding_either_side = $this->padding - 2;
            $middle = $this->range_centered_on($this->current_page, $padding_either_side);
            return array_merge($start, $middle, $end);
        }
    }
    private function current_page_near_to_start() {
        return $this->current_page <= $this->padding + 1;
    }
    private function current_page_near_to_end() {
        return $this->current_page >= $this->last_page - $this->padding;
    }
    private function range_centered_on($target, $padding) {
        return range($target - $padding, $target + $padding);
    }
}
