#!/bin/bash
# rewrite glyphicon location
sed -i -e's_../img/glyphicons-halflings.png_[[pix:theme|glyphicons-halflings]]_' \
       -e's_../img/glyphicons-halflings-white.png_[[pix:theme|glyphicons-halflings-white]]_' *.css

# rewrite font location for font-awesome
sed -i 's_/font/_/theme/bootstrap\_renderers/font/_g' awesome.css
