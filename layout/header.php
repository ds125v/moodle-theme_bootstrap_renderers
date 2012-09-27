<!doctype html public>
<html dir="ltr">
<?php
$throwaway =  $OUTPUT->doctype(); // needs to be called to prevent moodle echoing old doctype

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
?>
<head>
	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $PAGE->heading ?></title>
    
    <!-- mobile viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
     <!-- icons-->
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>">
    
    <!--[if lt IE 9]>
  		<script src="[[theme|html5shiv.js]]"></script>
	<![endif]-->

		
    <?php echo $OUTPUT->standard_head_html(); ?>
</head>


<body id="<?php echo $PAGE->bodyid ?>" class="<?php echo $PAGE->bodyclasses?>"
<div id="page">
 <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo new moodle_url("/")?>">Moodle</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="<?php echo new moodle_url("/")?>">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
              <?php echo $OUTPUT->login_info() ?> 
              <!--
            <form class="navbar-form pull-right">
              <input class="span2" type="text" placeholder="username">
              <input class="span2" type="password" placeholder="password">
              <button type="submit" class="btn">Sign in</button>
            </form> -->
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

<div role="main" class="container">

<?php if ($hasheading || $hasnavbar) : ?>
<!- PAGE HEADING -->
    <header id="page-header" class="jumbotron">    	
        <?php if ($hasheading) { ?>
        <h1 class="headermain"><a href="<?php  global $CFG; $url = $CFG->wwwroot."/course/view.php?id=".$PAGE->course->id; echo $url; ?>"><?php echo $PAGE->heading ?></a></h1>
        
        <?php if (!empty($PAGE->layout_options['langmenu'])) {
                echo $OUTPUT->lang_menu();
            }
            echo $PAGE->headingmenu
        ?><?php } ?>
        
        
        <?php if ($hasnavbar) { ?>
            <div class="stickynav">
                <?php echo $OUTPUT->navbar(); ?>
                <div class="navbutton"> <?php echo $PAGE->button; ?></div>
            </div>
        <?php } ?>
    </header>
<!- END PAGE HEADING -->
<?php endif; ?>
