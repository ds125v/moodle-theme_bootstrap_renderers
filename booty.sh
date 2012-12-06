#!/bin/bash

mkdir ../$1
cp -r ../standard/* ../$1/.

cp html5shiv.js ../$1/.
cp renderers.php ../$1/.
mkdir ../$1/renderers
cp renderers/*.php ../$1/renderers/.
mkdir ../$1/layout
cp layout/*.php ../$1/layout/.
mkdir ../$1/javascript
cp -r javascript/* ../$1/javascript/.

sed -i s/theme_bootstrap_renderers/theme_$1/ ../$1/renderers/*.php

cp booty/config.php ../$1/.
sed -i s/booty/$1/ ../$1/config.php

rm ../$1/lang/en/theme_standard.php
cp booty/theme_booty.php ../$1/lang/en/theme_$1.php.

rm ../$1/style/*.css
php generate.php > ../$1/style/$1.css
