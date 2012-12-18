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
        $count = 0;
        foreach ($navigation->children as &$child) {
            $child->preceedwithhr = ($count!==0);
            $count++;
        }
        $navs = $this->navigation_node($navigation, array('class' => 'block_tree list nav nav-list'));
        if ($navs === '') {
            return '';
        } else {
            $attributes = array('class' => 'block_tree_box', 'id' => $navigation->id); 
            return html::li($attributes, $navs . bootstrap::list_divider());
        }
    }
    protected function navigation_node(navigation_node $node, $attrs=array('class'=>'nav nav-list')) {
        $items = $node->children;

        // exit if empty, we don't want an empty ul element
        if ($items->count()==0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        foreach ($items as $item) {
            if (!$item->display) {
                continue;
            }

            $isbranch = ($item->children->count()>0  || $item->nodetype==navigation_node::NODETYPE_BRANCH);
            $hasicon = (!$isbranch && $item->icon instanceof renderable);

            if ($isbranch) {
                $item->hideicon = true;
            }
            $content = $this->output->render($item);

            // this applies to the li item which contains all child lists too
            $liclasses = array($item->get_css_type());
            $liexpandable = array();
            if (!$item->forceopen || (!$item->forceopen && $item->collapse) || ($item->children->count()==0  && $item->nodetype==navigation_node::NODETYPE_BRANCH)) {
                $liclasses[] = 'collapsed';
            }
            if ($isbranch) {
                $liclasses[] = 'contains_branch';
                $liexpandable = array('aria-expanded' => in_array('collapsed', $liclasses) ? "false" : "true");
            } else if ($hasicon) {
                $liclasses[] = 'item_with_icon';
            }
            if ($item->isactive === true) {
                $liclasses[] = 'current_branch';
                $liclasses[] = 'active';
            }
            $liattr = array('class' => join(' ',$liclasses)) + $liexpandable;
            // class attribute on the div item which only contains the item content
            $divclasses = array('tree_item');
            if ($isbranch) {
                $divclasses[] = 'branch';
            } else {
                $divclasses[] = 'leaf';
            }
            if (!empty($item->classes) && count($item->classes)>0) {
                $divclasses[] = join(' ', $item->classes);
            }
            $divattr = array('class'=>join(' ', $divclasses));
            if (!empty($item->id)) {
                $divattr['id'] = $item->id;
            }
            $content = $this->rewrite_tree_node ($content, $divclasses);
            $content .= $this->navigation_node($item);
            $content = html_writer::tag('li', $content, $liattr);
            if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
                $content = bootstrap::list_divider() . $content;
            }
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::tag('ul', implode("\n", $lis), $attrs);
        } else {
            return '';
        }
    }
    private function rewrite_tree_node($node_html, $new_classes) {
        $opening_tag = strstr($node_html, ' ', true);
        $pattern = '/class=\"([^"]+)/';
        if (preg_match($pattern, $node_html, $matches)) {
            $existing_classes = $matches[1];
            $new_classes = classes::add_to($new_classes, $existing_classes);
        }

        $node_html = substr($node_html, strlen($opening_tag));
        $node_html = substr($node_html, 0, -1 * strlen($opening_tag));
        return '<a class="'. implode(' ', $new_classes) .'"' . $node_html . 'a>';
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
        return form::inline_search_append($formtarget, 'Search Settings', s($searchvalue), s(get_string('search')));

    }

}

