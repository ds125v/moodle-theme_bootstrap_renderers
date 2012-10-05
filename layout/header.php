<?php

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
$bodyclasses = s($PAGE->bodyclasses.' '.join(' ', $bodyclasses));

$html5shiv = new moodle_url('/theme/bootstrap_renderers/html5shiv.js');
$html5shiv = "<script src='$html5shiv'></script>";

$favicon_url = $OUTPUT->pix_url('favicon', 'theme');

$random = true;
if ($random) {
    $navbar_inverse = rand(0, 1);
    $fluid = rand(0, 1);
}

$navbar_inverse = $navbar_inverse ? 'navbar-inverse' : '';
$fluid = $fluid ? '-fluid' : '';

$header = '';
if ($hasheading || $hasnavbar) {
    $header .= "<header>";
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

<body id="<?php echo $PAGE->bodyid ?>" class="<?php $bodyclasses?>">

<div class="container<?php echo $fluid ?>">

<div class="navbar <?php echo $navbar_inverse ?> navbar-top">
    <div class="navbar-inner">
        <a class="brand" href="<?php echo new moodle_url("/")?>"><?php echo $PAGE->heading?></a>
        <ul class="nav">
            <li class="active"><a href="<?php echo new moodle_url("/")?>">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <form class="navbar-form pull-right navbar-text">
              <?php echo $OUTPUT->login_info() ?> 
        </form> 
    </div>
</div>
<?php

echo $header;
