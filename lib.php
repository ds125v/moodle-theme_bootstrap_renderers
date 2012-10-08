<?php

function processor($css, $theme) {
    // $subtheme = $theme->settings->subtheme;
    // $fixed = $theme->settings->fixed;
    // $random = $theme->settings->random;

    $themes = array(
        'amelia' => '50px',
        'bootstrap' => '40px',
        'cerulean' => '50px',
        'cyborg' => '40px',
        'journal' => '60px',
        'readable' => '50px',
        'simplex' => '40px',
        'slate' => '40px',
        'spacelab' => '40px',
        'spruce' => '50px',
        'superhero' => '70px',
        'united' => '40px',
    );
    $random = true;
    if ($random) {
        $subtheme = array_rand($themes);
        $responsive = true;
        $fixed = true;
        $awesome = true;
    }

    $themedir = "/var/www/bootstrap/theme/bootstrap_renderers";

    $find[] = "[[bootstrap]]";
    $replace[] = file_get_contents("$themedir/style/$subtheme/bootstrap.css");

    $find[] = "[[fixed-nav-padding]]";
    // This needs to be between bootstrap and bootstrap-responsive.
    $padding = $themes[$subtheme];
    if ($fixed) {
        $replace[] = "body {padding-top: $padding;}";
    } else {
        $replace[] = '';
    }

    $find[] = "[[bootstrap-responsive]]";
    if ($responsive) {
        $replace[] = file_get_contents("$themedir/style/bootstrap/bootstrap-responsive.css");
    } else {
        $replace[] = '';
    }

    $find[] = "[[font-awesome]]";
    if ($awesome) {
        $fix = '[class*="icon-"] {background-image: none;}';
        $replace[] = file_get_contents("$themedir/style/font-awesome/font-awesome.css") . $fix;

        $find[] = "../font/fontawesome-webfont";
        $replace[] = current_theme() . '/font/fontawesome-webfont';
    } else {
        $replace[] = '';
    }

    $find[] = "../img/glyphicons-halflings";
    $replace[] = current_theme() . '/pix/glyphicons-halflings';

    return str_replace($find, $replace, $css);
}
