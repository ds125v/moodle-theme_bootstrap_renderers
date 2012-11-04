#!/bin/bash

# usage: ./colorize.sh pink
# creates a glyphicons-halflings-pink.png
# requires imagemagick, see http://www.imagemagick.org/

mkdir pix-$1
convert glyphicons-halflings.png +level-colors $1, pix-$1/glyphicons-halflings-$1.png
convert icon-copy.png +level-colors $1, pix-$1/icon-copy-$1.png
