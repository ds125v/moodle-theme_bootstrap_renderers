<?php

function processor($css, $theme) {

    $find[] = "../img/glyphicons-halflings";
    $replace[] = current_theme() . '/pix/glyphicons-halflings';

    $find[] = "../font/fontawesome-webfont";
    $replace[] = current_theme() . '/font/fontawesome-webfont';

    return str_replace($find, $replace, $css);
}
