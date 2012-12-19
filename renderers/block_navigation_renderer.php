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

require_once($CFG->dirroot . "/blocks/navigation/renderer.php");

class theme_bootstrap_renderers_block_navigation_renderer extends block_navigation_renderer {

    protected function navigation_node($items, $attrs=array('class'=>'nav nav-pills nav-stacked'), $expansionlimit=null, array $options = array(), $depth=1) {

        // exit if empty, we don't want an empty ul element
        if (count($items)==0) {
            return '';
        }

        // array of nested li elements
        $lis = array();
        foreach ($items as $item) {
            if (!$item->display && !$item->contains_active_node()) {
                continue;
            }
            $content = $item->get_content();
            $title = $item->get_title();

            $isexpandable = (empty($expansionlimit) || ($item->type > navigation_node::TYPE_ACTIVITY || $item->type < $expansionlimit) || ($item->contains_active_node() && $item->children->count() > 0));
            $isbranch = $isexpandable && ($item->children->count() > 0 || ($item->has_children() && (isloggedin() || $item->type <= navigation_node::TYPE_CATEGORY)));

            // Skip elements which have no content and no action - no point in showing them
            if (!$isexpandable && empty($item->action)) {
                continue;
            }

            $hasicon = ((!$isbranch || $item->type == navigation_node::TYPE_ACTIVITY || $item->type == navigation_node::TYPE_RESOURCE) && $item->icon instanceof renderable);

            if ($hasicon) {
                $icon = $this->output->render($item->icon);
            } else {
                $icon = '';
            }
            $content = $icon.$content; // use CSS for spacing of icons
            if ($item->helpbutton !== null) {
                $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class'=>'clearhelpbutton'));
            }

            if ($content === '') {
                continue;
            }

            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            if (is_string($item->action) || empty($item->action) || ($item->type === navigation_node::TYPE_CATEGORY && empty($options['linkcategories']))) {
                $attributes['tabindex'] = '0'; //add tab support to span but still maintain character stream sequence.
                $content = html_writer::tag('span', $content, $attributes);
            } else if ($item->action instanceof action_link) {
                //TODO: to be replaced with something else
                $link = $item->action;
                $link->text = $icon.$link->text;
                $link->attributes = array_merge($link->attributes, $attributes);
                $content = $this->output->render($link);
                $linkrendered = true;
            } else if ($item->action instanceof moodle_url) {
                $content = html_writer::link($item->action, $content, $attributes);
            }

            // this applies to the li item which contains all child lists too
            $liclasses = array($item->get_css_type(), 'depth_'.$depth);
            $liexpandable = array();
            if ($item->has_children() && (!$item->forceopen || $item->collapse)) {
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
            if ($hasicon) {
                $divclasses[] = 'hasicon';
            }
            if (!empty($item->classes) && count($item->classes)>0) {
                $divclasses[] = join(' ', $item->classes);
            }
            $divattr = array('class'=>join(' ', $divclasses));
            if (!empty($item->id)) {
                $divattr['id'] = $item->id;
            }
            $content = $this->rewrite_tree_node ($content, $divclasses);
            if ($isexpandable) {
                $content .= $this->navigation_node($item->children, array('class'=>'nav nav-pills nav-stacked'), $expansionlimit, $options, $depth+1);
            }
            if (!empty($item->preceedwithhr) && $item->preceedwithhr===true) {
                $content = bootstrap::list_divider() . $content;
            }
            $content = html_writer::tag('li', $content, $liattr);
            $lis[] = $content;
        }

        if (count($lis)) {
            return html_writer::tag('ul', implode("\n", $lis), $attrs);
        } else {
            return '';
        }
    }


    public function navigation_tree(global_navigation $navigation, $expansionlimit, array $options = array()) {
        $navigation->add_class('navigation_node');
        $content = $this->navigation_node(array($navigation), array('class'=>'block_tree list nav nav-pills nav-stacked'), $expansionlimit, $options);
        if (!isset($navigation->id) && !is_numeric($navigation->id) && !empty($content)) {
            $attributes = array('class' => 'block_tree_box', 'id' => $navigation->id); 
            $content = html::li($attributes, $content);
        }
        return $content;
    }
    private function rewrite_tree_node($node_html, $new_classes) {
        $opening_tag = strstr($node_html, ' ', true);
        $pattern = 'class="([^"]+)';
        if (preg_match($pattern, $node_html, $matches)) {
            $existing_classes = $matches[1];
            $new_classes = classes::add_to($new_classes, $existing_classes);
        }

        $node_html = substr($node_html, strlen($opening_tag));
        $node_html = substr($node_html, 0, -1 * strlen($opening_tag));
        return '<a class="'. implode(' ', $new_classes) .'"' . $node_html . 'a>';
    }

    public function search_form(moodle_url $formtarget, $searchvalue) {
        // TODO: internationalise the placeholder text.
        return bootstrap::inline_search_append($formtarget, 'Search Settings', s($searchvalue), s(get_string('search')));

    }

}

