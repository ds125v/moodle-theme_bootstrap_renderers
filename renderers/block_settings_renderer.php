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

require_once($CFG->dirroot . "/blocks/settings/renderer.php");

class theme_bootstrap_renderers_block_settings_renderer extends block_settings_renderer {

    public function settings_tree(settings_navigation $navigation) {
        $navs[] = $this->navigation_node($navigation, array('class' => 'block_tree list'));
        $attributes = array('class' => 'block_tree_box', 'id' => $navigation->id); 
        return html::li($attributes, implode("<li class=divider></li>", $navs));
    }
    protected function disabled_navigation_node(navigation_node $node, $wrap = true) {
        $items = $node->children;

        if ($items->count()==0) {
            return '';
        }

        foreach ($items as $item) {
            if (!$item->display) {
                continue;
            }

            $isbranch = ($item->children->count()>0  || $item->nodetype==navigation_node::NODETYPE_BRANCH);

            if ($isbranch) {
                $item->hideicon = true;
            }
            $content = $this->output->render($item);

            $classes = 'tree_item';
            $expanded = 'true';
            if (!$item->forceopen || (!$item->forceopen && $item->collapse) || ($item->children->count()==0  && $item->nodetype==navigation_node::NODETYPE_BRANCH)) {
                $classes = classes::add_to($classes, 'collapsed');
                if ($isbranch) {
                    $expanded = "false";
                    $classes = classes::add_to($classes, 'branch');
                }
            }
            if ($item->isactive === true) {
                $classes = classes::add_to($classes, 'active');
            }
            $attributes = array('class' => $classes, 'aria-expanded'=> $expanded);
            $content .= $this->navigation_node($item);

            $lis[] = html::li($attributes, $content);
        }
        $output = implode($lis);
        if ($wrap) {
            return html::ul('nav nav-list block_tree', $output);
        }
        return $output;
    }

    public function search_form(moodle_url $formtarget, $searchvalue) {
        // TODO: internationalise the placeholder text.
        return bootstrap::inline_search_append($formtarget, 'Search Settings', s($searchvalue), s(get_string('search')));

    }

}

