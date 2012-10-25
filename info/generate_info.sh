#!/bin/bash

# output of, in moodle root:
git grep --full-name -p --break --heading -E '(public|private|protected) function' -- '../../../renderer.php' '../../../../lib/outputrenderers.php'
# > list_renderers.txt
# output of, in root of theme directory, on dev branch:
git grep --full-name -p --break --heading -E '(public|private|protected) function' -- '../*renderer.php' > progress.txt


