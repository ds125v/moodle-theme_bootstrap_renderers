#!/bin/bash
convert -page 480x168+5+5 $1 -flatten with_padding.png

convert with_padding.png -crop 20x7@ +repage +adjoin glyphs+1_%d.png

convert glyphs+1_* -shave 5x5 glyphs+2_%d.png

# move and rename
mkdir -p pix/t
mkdir -p pix/i
mv glyphs+2_5.png pix/t/hide.png
cp pix/t/hide.png pix/i/hide.png
mv glyphs+2_6.png pix/t/show.png
cp pix/t/show.png pix/i/show.png
mv glyphs+2_15.png pix/t/expanded.png
mv glyphs+2_20.png pix/i/move.png
mv glyphs+2_39.png pix/i/settings.png
mv glyphs+2_41.png pix/i/filter.png
mv glyphs+2_44.png pix/t/delete.png
mv glyphs+2_57.png pix/i/backup.png
mv glyphs+2_58.png pix/i/restore.png
mv glyphs+2_96.png pix/t/editstring.png
mv glyphs+2_100.png pix/i/edit.png
mv glyphs+2_103.png pix/t/move_2d.png
mv glyphs+2_116.png pix/t/collapsed.png
mv glyphs+2_117.png pix/i/users.png
cp pix/i/users.png pix/i/roles.png
mv glyphs+2_118.png pix/t/switch_plus.png
mv glyphs+2_119.png pix/t/switch_minus.png
mv glyphs+2_122.png pix/help.png
mv glyphs+2_129.png pix/t/left.png
mv glyphs+2_130.png pix/t/right.png
mv glyphs+2_131.png pix/t/up.png
mv glyphs+2_132.png pix/t/down.png
mv glyphs+2_136.png pix/t/add.png
mv glyphs+2_137.png pix/t/minus.png
# some irregular ones
