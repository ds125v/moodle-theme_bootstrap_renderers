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

require_once('table.php');
require_once('progress.php');

if (isset($CFG)) {
    require_once($CFG->dirroot . "/mod/choice/renderer.php");
} else {
    class mod_choice_renderer {
        // Empty class for standalone unit testing.
    }
}

class theme_bootstrap_renderers_mod_choice_renderer extends mod_choice_renderer {

    public static function results($choices) {
        ksort($choices->options);
        $total_votes = $choices->numberofuser;
        foreach ($choices->options as $option) {
            $votes = 0;
            if (!empty($option->user)) {
                $votes = count($option->user);
            }
            $percent = 0;
            if ($total_votes > 0) {
                $percent = ((float)$votes/(float)$total_votes)*100.0;
            }
            if (is_object($option)) {
                $results[] = array('text'=>$option->text, 'votes'=>$votes, 'percent'=>$percent);
            }
        }
        return $results;

    }
    public static function results_as_rows($votes) {
        foreach ($votes as $result) {
            $row = array($result['text']);
            $row[] = $result['votes'];
            $row[] = format_float($result['percent'], 1) . '%';
            $row[] = progress::bar($result['percent']);
            $rows[] = $row;
        }
        return $rows;
    }
    public static function results_as_columns($results) {
        $header[] = get_string('choiceoptions', 'choice');
        $votes[] = get_string('numberofuser', 'choice');
        $percent[] = get_string('numberofuserinpercentage', 'choice');
        $graph[] = get_string('responsesresultgraphheader', 'choice');

        foreach ($results as $result) {
            $header[] = $result['text'];
            $votes[] = $result['votes'];
            $percent[] = format_float($result['percent'], 1) . '%';
            $graph[] = progress::level($result['percent']);
        }
        return array('header'=>$header, 'rows'=>array($votes, $percent, $graph));
    }
    public function display_publish_anonymous_vertical($choices) {
        $attributes['class'] = 'table table-striped table-condensed';
        $attributes['summary'] = get_string('responsesto', 'choice', format_string($choices->name));

        $results = self::results($choices);
        $html = html::h2(get_string("responses", "choice"));
        $output = self::results_as_columns($results);
        $html .= table::simple($attributes, $output['header'], $output['rows']);
        return $html;
    }
    public function display_publish_anonymous_horizontal($choices) {

        $attributes['class'] = 'table table-striped table-condensed';
        $attributes['summary'] = get_string('responsesto', 'choice', format_string($choices->name));

        $options_th = get_string('choiceoptions', 'choice');
        $votes_th = get_string('numberofuser', 'choice');
        $percent_th = get_string('numberofuserinpercentage', 'choice');
        $graph_th = get_string('responsesresultgraphheader', 'choice');

        $headers = array($options_th, $votes_th, $percent_th, $graph_th);
        $results = self::results($choices);
        $html = html::h2(get_string("responses", "choice"));
        $html .= table::simple($attributes, $headers, self::results_as_rows($results));
        return $html;
    }
}
