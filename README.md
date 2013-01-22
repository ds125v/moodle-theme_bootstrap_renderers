moodle-theme_bootstrap_renderers
================================

A developer theme for working on Moodle 2.4/5 renderers that output Bootstrap 3 compatible HTML.

Currently aiming to have something usable by end-users by this summer (June 2013). If you want something with the Bootstrap look to use right now check out the Bootstrap theme by Bas Brands](http://basbrands.nl/2012/10/01/moodle-bootstrap-theme/).

TODO #1 (stuff I'm in the process of doing)
----

* go through every renderer.php in Moodle (starting with outputrenderers.php) and rewrite to match Bootstrap expectations (see [list_renderers.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/info/list_renderers.txt))
* file an enhancement request about classes in Moodle that clash with Bootstrap (see [/styles/undo.css](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/style/undo.css))
* figure out how to switch off CSS coming from modules (current plan: delete almost all non-bootstrap ids and classes), maybe can be done in the pre-processor?
* figure out how to switch off YUI CSS (at least the 3.5.1 stuff, might need some of the YUI2 stuff e.g. for date selector) (this might become an option in 2.5)
* write up testing and contribution guidelines (just started [testing.txt](https://github.com/ds125v/moodle-theme_bootstrap_renderers/blob/master/info/testing.txt))
* create an appropriate editor.css file for TinyMCE from Bootstrap css.
* see if we can pass any Selenium/Behat tests Moodle has, and investigate running them on Travis CI.

TODO #2 (stuff I'm secretly hoping someone else will do, to save me the bother)
----------------

* set it up so the [RTL fork of Bootstrap](https://github.com/AbdullahDiaa/Bootstrap-RTL) gets pulled in when required (needs updated for Bootstrap 3)
* make sure any future forms library (currently under discussion) fits the needs of Bootstrap
* find out if anyone's doing work on a Zurb Foundation theme and see if we can work together: [http://foundation.zurb.com/](http://foundation.zurb.com/)
* restyle the TinyMCE editor interface using Bootstrap (this looks quite easy once you find where the CSS lives)
* find a simpler editor that is Bootstrap aware and plug it into Moodle e.g. (bootstrap-wysihtml5)[http://jhollingworth.github.com/bootstrap-wysihtml5/] 
* restyle the Moodle gadgets UI components (file picker, activity chooser etc.) with Bootstrap 

Current Build Status
--------------------

[![Build Status](https://travis-ci.org/ds125v/moodle-theme_bootstrap_renderers.png)](https://travis-ci.org/ds125v/moodle-theme_bootstrap_renderers)
