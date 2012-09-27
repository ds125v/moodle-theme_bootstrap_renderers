<?php

$OUTPUT->doctype(); // throw it away to avoid warning
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasblocks1 = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hasblocks2 = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$bodyclasses = array();
if ($hasblocks1 && !$hasblocks2) {
    $bodyclasses[] = 'blocks1-only';
} else if ($hasblocks2 && !$hasblocks1) {
    $bodyclasses[] = 'blocks2-only';
} else if (!$hasblocks1 && !$hasblocks2) {
    $bodyclasses[] = 'no-blocks';
}
$favicon_url = $OUTPUT->pix_url('favicon', 'theme');
/* <html <?php echo $OUTPUT->htmlattributes() ?>> */
?><!DOCTYPE html>
<html>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $favicon_url ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
<!--[if lt IE 9]>
<script src="<?php echo new moodle_url("/theme/bootstrap/html5shiv.js")?>"></script>
<![endif]-->
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">
 <header class="navbar navbar-inverse navbar-fixed-top">
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
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
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
	    </header>



    <div class="container"> <?php echo $OUTPUT->navbar() ?> <div class="navbutton"> <?php echo $PAGE->button ?></div>

	<h1><?php echo $PAGE->heading ?></h1>

            <div id="layout" class="yui3-g">
		<?php if ($hasblocks1) { ?>
		    <div id="blocks1" class="yui3-u"><div class="region-content"> <?php echo $OUTPUT->blocks_for_region('side-pre') ?></div></div>
		<?php } ?>

	        <div id="main" class="yui3-u"><div class="region-content"> <?php echo $OUTPUT->main_content() ?></div></div>

		<?php if ($hasblocks2) { ?>
		    <div id="blocks2" class="yui3-u"><div class="region-content"> <?php echo $OUTPUT->blocks_for_region('side-post') ?></div></div>
		<?php } ?>

	   </div>
<footer>
<p class="pull-right"><a href="#">Back to top</a>
<p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
<?php
echo $OUTPUT->login_info();
echo $OUTPUT->home_link();
echo $OUTPUT->standard_footer_html();
?>
</footer> 
</div>

</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
