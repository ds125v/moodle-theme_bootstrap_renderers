#!/bin/bash

# output of, in moodle root of 2.4/5:
git grep --full-name -p --break --heading -E '(public|private|protected) function' -- '*/renderer.php' 'lib/outputrenderers.php' > list_renderers.txt
# output of, in root of theme's info directory, on master branch:
git grep --full-name -p --break --heading -E '(public|private|protected) function' -- '../*renderer.php' > progress.txt


