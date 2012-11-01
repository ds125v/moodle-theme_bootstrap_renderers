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
 * layout with HTML consistent with that expected by Bootstrap
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));

$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));
$haslogininfo =  (isguestuser() or isloggedin());

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}
$html5shiv = new moodle_url('/theme/bootstrap_renderers/html5shiv.js');
$html5shiv = "<script src='$html5shiv'></script>";

$favicon_url = $OUTPUT->pix_url('favicon', 'theme');


$navbar_fixed = $PAGE->theme->settings->fixed;
$navbar_inverse = $PAGE->theme->settings->navbarinvert;
$fluid = $PAGE->theme->settings->fluid;

if ($PAGE->theme->settings->subtheme === 'random') {
    $navbar_inverse = rand(0, 1);
    $fluid = rand(0, 1);
}

if ($navbar_fixed == 1) {
    $navbar_fixed = 'navbar-fixed-top';
    $bodyclasses[] = $navbar_fixed.'-padding';
} else {
    $navbar_fixed = '';
}
if ($navbar_inverse == 1) {
    $navbar_inverse = 'navbar-inverse';
} else {
    $navbar_inverse = '';
}
if ($fluid == 1) {
    $fluid = '-fluid';
} else {
    $fluid = '';
}

$header = '';
if ($hasheading || $hasnavbar) {
    $header .= "<header id=page-header>";
    if ($hasheading) {
        if (!empty($PAGE->layout_options['langmenu'])) {
            $header .= $OUTPUT->lang_menu();
        }
        $header .= $PAGE->headingmenu;
    }
    if ($hasnavbar) {
        $navbar = $OUTPUT->navbar();
        $button = $PAGE->button;
        $navbar = "<div>$navbar<div class=pull-right>$button</div></div>";
        $header .= $navbar;
    }
    $header .= "</header>";
}
/* end of settings */

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $PAGE->heading ?></title>
    <link rel="shortcut icon" href="<?php echo $favicon_url ?>">
    <!--[if lt IE 9]><?php echo $html5shiv ?><![endif]-->
    <?php echo $OUTPUT->standard_head_html(); ?>
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">

<div class=container<?php echo $fluid ?>>

<div class="navbar <?php echo $navbar_inverse . ' ' . $navbar_fixed ?>">
    <div class=navbar-inner>
        <div class=container<?php echo $fluid ?>>
            <a class=brand href="<?php echo new moodle_url("/")?>"><?php echo $PAGE->heading?></a>
            <div class=nav-collapse>
                <ul class=nav>
                    <li class=active><a href="<?php echo new moodle_url("/")?>">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <div class="navbar-text pull-right">
                  <?php echo $OUTPUT->login_info() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $header; ?>

<div class=container<?php echo $fluid ?>>
<div id="page-content" class=row<?php echo $fluid ?>>

<?php if ($hassidepre) : ?>
    <?php if ($hassidepre AND $hassidepost) : ?>
        <aside id=region-pre class=span3>
    <?php else : ?>
        <aside id=region-pre class=span4>
    <?php endif; ?>
    <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
        </aside>
<?php endif; ?>

<?php if ($hassidepre AND $hassidepost) : ?>
    <article id=region-content class=span6>
<?php elseif ($hassidepre OR $hassidepost) : ?>
    <article id=region-content class=span8>
<?php else : ?>
    <article id=region-content class=span12>
<?php endif; ?>
        <?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
    </article>

<?php if ($hassidepost) : ?>
    <?php if ($hassidepre AND $hassidepost) : ?>
        <aside id=region-post class=span3>
    <?php else : ?>
        <aside id=region-post class=span4>
    <?php endif; ?>
    <?php echo $OUTPUT->blocks_for_region('side-post') ?>
        </aside>
<?php endif; ?>
</div>

<?php if ($hasfooter) { ?>
    <footer id=page-footer role=contentinfo>
    <nav role=navigation>
    <p><?php echo $OUTPUT->login_info()?></p>
    <p><?php echo page_doc_link(get_string('moodledocslink'))?></p>
    <?php
        echo $OUTPUT->home_link();
        echo $OUTPUT->standard_footer_html();
      ?>
      <p><a href=# class=pull-right>Back to top</a></p>
    </nav>
    </footer>
<?php } ?>
</div>
</div>
<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>
