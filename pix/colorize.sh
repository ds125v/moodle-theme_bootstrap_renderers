#!/bin/bash

# usage: ./colorize.sh pink
# creates a glyphicons-halflings-pink.png
# requires imagemagick, see http://www.imagemagick.org/

convert glyphicons-halflings.png +level-colors $1, glyphicons-halflings-$1.png
