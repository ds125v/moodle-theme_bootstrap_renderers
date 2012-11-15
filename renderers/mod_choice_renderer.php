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

require_once($CFG->dirroot . "/mod/choice/renderer.php");

require_once('progress.php');

class theme_bootstrap_renderers_mod_choice_renderer extends mod_choice_renderer {

    public function display_publish_anonymous_vertical($choices) {
        $CHOICE_COLUMN_HEIGHT = 300;

        $html = '';
        $table = new html_table();
        $table->cellpadding = 5;
        $table->cellspacing = 0;
        $table->attributes['class'] = 'table table-striped';
        $table->summary = get_string('responsesto', 'choice', format_string($choices->name));
        $table->data = array();

        $count = 0;
        ksort($choices->options);
        $columns = array();
        $rows = array();

        $headercelldefault = new html_table_cell();
        $headercelldefault->scope = 'row';
        $headercelldefault->header = true;
        $headercelldefault->attributes = array('class'=>'header data');

        // column header
        $tableheader = clone($headercelldefault);
        $tableheader->text = html_writer::tag('div', get_string('choiceoptions', 'choice'), array('class' => 'accesshide'));
        $rows['header'][] = $tableheader;

        // graph row header
        $graphheader = clone($headercelldefault);
        $graphheader->text = html_writer::tag('div', get_string('responsesresultgraphheader', 'choice'), array('class' => 'accesshide'));
        $rows['graph'][] = $graphheader;

        // user number row header
        $usernumberheader = clone($headercelldefault);
        $usernumberheader->text = get_string('numberofuser', 'choice');
        $rows['usernumber'][] = $usernumberheader;

        // user percentage row header
        $userpercentageheader = clone($headercelldefault);
        $userpercentageheader->text = get_string('numberofuserinpercentage', 'choice');
        $rows['userpercentage'][] = $userpercentageheader;

        $contentcelldefault = new html_table_cell();
        $contentcelldefault->attributes = array('class'=>'data');

        foreach ($choices->options as $optionid => $option) {
            // calculate display length
            $height = $percentageamount = $numberofuser = 0;
            $usernumber = $userpercentage = '';

            if (!empty($option->user)) {
               $numberofuser = count($option->user);
            }

            if($choices->numberofuser > 0) {
               $height = ($CHOICE_COLUMN_HEIGHT * ((float)$numberofuser / (float)$choices->numberofuser));
               $percentageamount = ((float)$numberofuser/(float)$choices->numberofuser)*100.0;
            }

            $displaygraph = html_writer::tag('img','', array('style'=>'height:'.$height.'px;width:49px;', 'alt'=>'', 'src'=>$this->output->pix_url('column', 'choice')));

            // header
            $headercell = clone($contentcelldefault);
            $headercell->text = $option->text;
            $rows['header'][] = $headercell;

            // Graph
            $graphcell = clone($contentcelldefault);
            $graphcell->attributes = array('class'=>'graph vertical data');
            $graphcell->text = $displaygraph;
            $rows['graph'][] = $graphcell;

            $usernumber .= html_writer::tag('div', ' '.$numberofuser.'', array('class'=>'numberofuser', 'title'=> get_string('numberofuser', 'choice')));
            $userpercentage .= html_writer::tag('div', format_float($percentageamount,1). '%', array('class'=>'percentage'));

            // number of user
            $usernumbercell = clone($contentcelldefault);
            $usernumbercell->text = $usernumber;
            $rows['usernumber'][] = $usernumbercell;

            // percentage of user
            $numbercell = clone($contentcelldefault);
            $numbercell->text = $userpercentage;
            $rows['userpercentage'][] = $numbercell;
        }

        $table->head = $rows['header'];
        $trgraph = new html_table_row($rows['graph']);
        $trusernumber = new html_table_row($rows['usernumber']);
        $truserpercentage = new html_table_row($rows['userpercentage']);
        $table->data = array($trgraph, $trusernumber, $truserpercentage);

        $header = html_writer::tag('h2',format_string(get_string("responses", "choice")));
        $html .= html::div('responseheader', $header);
        $html .= html_writer::tag('a', get_string('skipresultgraph', 'choice'), array('href'=>'#skipresultgraph', 'class'=>'skip-block'));
        $html .= html::div('response', html_writer::table($table));

        return $html;
    }
    public function display_publish_anonymous_horizontal($choices) {

        $attributes['class'] = 'table table-striped table-condensed';
        $attributes['summary'] = get_string('responsesto', 'choice', format_string($choices->name));

        $options_th = get_string('choiceoptions', 'choice');
        $votes_th = get_string('numberofuser', 'choice');
        $percent_th = get_string('numberofuserinpercentage', 'choice');
        $histogram_th = get_string('responsesresultgraphheader', 'choice');

        $headers = array($options_th, $votes_th, $percent_th, $histogram_th);

        ksort($choices->options);
        $total_votes = $choices->numberofuser;
        foreach ($choices->options as $optionid => $options) {
            $votes = 0;
            $percent = 0;

            if (!empty($options->user)) {
               $votes = count($options->user);
            }
            if($total_votes > 0) {
               $percent = ((float)$votes/(float)$total_votes)*100.0;
            }
            $displaypercent = format_float($percent,1). '%';
            $histogram = progress::bar($percent);

            $rows[] = array($options->text, $votes, $displaypercent, $histogram);
        }

        $html = html::h2(get_string("responses", "choice"));
        $html .= html::table($attributes, $headers, $rows);

        return $html;
    }
}
