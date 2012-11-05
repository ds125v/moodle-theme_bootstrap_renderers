#!/bin/bash
convert -page 480x168+5+5 -background transparent glyphicons-halflings*.png -flatten with_padding.png

convert with_padding.png -crop 20x7@ +repage +adjoin glyphs+2_%d.png

rm with_padding.png

mogrify  -shave 5x5 glyphs+2_*.png

# move and rename
mkdir -p t
mkdir -p i
mv glyphs+2_5.png i/marked.png
mv glyphs+2_6.png i/marker.png
mv glyphs+2_7.png i/users.png
cp i/users.png i/roles.png
cp i/users.png i/group.png
mv glyphs+2_11.png i/report.png
mv glyphs+2_12.png i/grades.png
mv glyphs+2_13.png t/delete.png
mv glyphs+2_18.png i/checkpermissions.png
mv glyphs+2_25.png t/backup.png
cp t/backup.png i/backup.png
mv glyphs+2_26.png t/restore.png
mv glyphs+2_29.png i/return.png
cp t/restore.png i/restore.png
mv glyphs+2_60.png t/editstring.png
mv glyphs+2_64.png i/edit.png
cp i/edit.png t/edit.png
mv glyphs+2_65.png i/publish.png
mv glyphs+2_67.png i/move_2d.png
mv glyphs+2_79.png t/collapsed.png
mv glyphs+2_80.png t/switch_plus.png
mv glyphs+2_81.png t/switch_minus.png
mv glyphs+2_84.png help.png
mv glyphs+2_85.png i/info.png
mv glyphs+2_90.png t/left.png
mv glyphs+2_91.png t/right.png
mv glyphs+2_92.png t/up.png
mv glyphs+2_93.png t/down.png
mv glyphs+2_97.png t/add.png
cp t/add.png t/enroladd.png
mv glyphs+2_98.png t/minus.png
mv glyphs+2_99.png i/course.png
mv glyphs+2_101.png i/cohort.png
mv glyphs+2_104.png t/hide.png
cp t/hide.png i/hide.png
mv glyphs+2_105.png t/show.png
cp t/show.png i/show.png
mv glyphs+2_113.png t/expanded.png
mv glyphs+2_118.png t/move.png
mv glyphs+2_135.png i/settings.png
mv glyphs+2_137.png i/filter.png


# extra icons, created to match
cp icon-copy*.png t/copy.png

# some irregular ones
# not done any yet
# will cut them according to position from sprites.less

rm glyphs+2_*.png
