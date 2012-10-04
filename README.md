moodle-theme_bootstrap_renderers
================================

A developer theme for working on Moodle renderers that output Bootstrap compatible HTML

TODO #1 (stuff I'm in the process of doing)
----

* go through every renderer.php in Moodle (starting with outputrenderers.php) and rewrite to match Bootstrap expectations (see [/list_renderers.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/list_renderers.txt))
* file an enhancement request about classes in Moodle that clash with Bootstrap (see [/styles/undo.css](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/style/undo.css))
* figure out how to switch off CSS coming from modules
* get a Bootstrap tag in the Moodle bug tracker to allow people to work together on finding and fixing relevant bugs

TODO #2 (stuff I'm secretly hoping someone else will do, to save me the bother)
----------------

* make sure any future forms library (currently under discussion) fits the needs of Bootstrap
* experiment with responsive layouts
* find out if anyone's doing work on a Zurb Foundation theme and see if we can work together: [http://foundation.zurb.com/](http://foundation.zurb.com/)
* set up public demo site with bootswapper.sh running to cycle through swatches
* get TinyMCE to pick up the editor.css and use the classes it finds there
* restyle the TinyMCE editor interface using Bootstrap
* find a simpler editor that is Bootstrap aware and plug it into Moodle 
* restyle the YUI UI components with Bootstrap 
* wire up all the javascript gadgets from Bootstrap with JQuery, then try using the YUI port
