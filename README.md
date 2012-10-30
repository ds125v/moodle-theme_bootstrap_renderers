moodle-theme_bootstrap_renderers
================================

A developer theme for working on Moodle renderers that output Bootstrap compatible HTML.

Check out a live demo, built from the latest code, which cycles though the various Bootstrap display options and Bootswatch colorschemes:

[http://moodle.iyware.com/?theme=bootstrap_renderers](http://moodle.iyware.com/?theme=bootstrap_renderers)

TODO #1 (stuff I'm in the process of doing)
----

* go through every renderer.php in Moodle (starting with outputrenderers.php) and rewrite to match Bootstrap expectations (see [list_renderers.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/info/list_renderers.txt))
* file an enhancement request about classes in Moodle that clash with Bootstrap (see [/styles/undo.css](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/style/undo.css))
* figure out how to switch off CSS coming from modules (current plan: delete almost all non-bootstrap ids and classes)
* write up testing and contribution guidelines (just started [testing.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/info/testing.txt))
* make the random mode trigger various responsive CSS changes even on the largest of monitors

TODO #2 (stuff I'm secretly hoping someone else will do, to save me the bother)
----------------

* set it up so the [RTL fork of Bootstrap](https://github.com/AbdullahDiaa/Bootstrap-RTL) gets pulled in when required 
* make sure any future forms library (currently under discussion) fits the needs of Bootstrap
* experiment with responsive layouts to see if two columns can be collapsed into one (putting pre- above post-) for medium screens
* find out if anyone's doing work on a Zurb Foundation theme and see if we can work together: [http://foundation.zurb.com/](http://foundation.zurb.com/)
* get TinyMCE to pick up the editor.css and use the classes it finds there
* restyle the TinyMCE editor interface using Bootstrap
* find a simpler editor that is Bootstrap aware and plug it into Moodle e.g. (bootstrap-wysihtml5)[http://jhollingworth.github.com/bootstrap-wysihtml5/] 
* restyle the YUI UI components with Bootstrap 
* wire up all the javascript gadgets from Bootstrap with JQuery, then try using the YUI port
