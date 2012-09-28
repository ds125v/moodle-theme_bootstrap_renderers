moodle-theme_bootstrap_renderers
================================

A developer theme for working on Moodle renderers that output Bootstrap compatible HTML

TODO
----

* Make layout look a bit nicer, and both Moodly and Bootstrappy at the same time.
* file an enhancement request about classes in Moodle that clash with Bootstrap (see [/styles/undo.css](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/style/undo.css))
* go through every renderer.php in Moodle (starting with outputrenderers.php) and rewrite to match Bootstrap expectations (see [/list_renderers.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/list_renderers.txt))
* figure out how to switch off CSS coming from modules
* generate Bootswatch CSS for all their themes to help with debugging
* split renderers.php into multiple files? It's getting quite big.

Longer term TODO
----------------

* get TinyMCE to pick up the editor.css and use the classes it finds there
* restyle the TinyMCE editor interface using Bootstrap
* find a simpler editor that is Bootstrap aware and plug it into Moodle 
* restyle the YUI UI components with Bootstrap 
* wire up all the javascript gadgets from Bootstrap, possibly using the YUI port
