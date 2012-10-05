<?php

$THEME->name = 'bootstrap_renderers';
$THEME->parents = array(); // Has to be present, even if empty.

$THEME->sheets = array('bootstrap', 'undo', 'javascript');
$THEME->editor_sheets = array('editor');

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'processor';

$THEME->javascripts = array();
$THEME->javascripts_footer = array();

$THEME->enable_dock = false;

// Layouts.

$basic = array(
    'file' => 'general.php',
);
$normal = $basic + array(
    'regions' => array('side-pre', 'side-post'),
    'defaultregion' => 'side-post',
);
$one_column = $basic + array(
    'regions' => array('side-pre'),
    'defaultregion' => 'side-pre',
);
$plain = $basic + array(
    'options' => array(
        'noblocks'=>true,
        'nofooter'=>true,
        'nonavbar'=>false,
        'nocustommenu'=>true,
    ),
);

$THEME->layouts = array(
    'admin' => $one_column,
    'base' => $basic,
    'course' => $normal,
    'coursecategory' => $normal,
    'embedded' => $basic + array('options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true)),
    'frametop' => $basic + array('options' => array('nofooter'=>true)),
    'frontpage' => $normal,
    'incourse' => $normal,
    'login' => $basic,
    'maintenance' => $plain,
    'mydashboard' => $normal,
    'mypublic' => $normal,
    'popup' => $basic + array('options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologininfo'=>true)),
    'print' => $plain,
    'redirect' => $plain,
    'report' => $one_column,
    'standard' => $normal,
);
