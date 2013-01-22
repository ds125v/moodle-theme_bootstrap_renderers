<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('html.php');
require_once('bootstrap.php');
require_once('label.php');
require_once('classes.php');
if (isset($CFG)) {
    require_once($CFG->dirroot . '/admin/renderer.php');
} else {
    class core_admin_renderer {
        // Empty class for standalone unit testing.
    }
}

class theme_bootstrap_renderers_core_admin_renderer extends core_admin_renderer {
    /**
     * Display the 'Do you acknowledge the terms of the GPL' page. The first page
     * during install.
     * @return string HTML to output.
     */
    public function install_licence_page() {
        global $CFG;
        $output = '';

        $copyrightnotice = text_to_html(get_string('gpl3'));
        $copyrightnotice = str_replace('target="_blank"', 'onclick="this.target=\'_blank\'"', $copyrightnotice); // extremely ugly validation hack

        $continue = new single_button(new moodle_url('/admin/index.php', array('lang'=>$CFG->lang, 'agreelicense'=>1)), get_string('continue'), 'get');

        $output .= $this->header();
        $output .= $this->heading('<a href="http://moodle.org">Moodle</a> - Modular Object-Oriented Dynamic Learning Environment');
        $output .= $this->heading(get_string('copyrightnotice'));
        $output .= $this->box($copyrightnotice, 'copyrightnotice');
        $output .= html_writer::empty_tag('br');
        $output .= $this->confirm(get_string('doyouagree'), $continue, "http://docs.moodle.org/dev/License");
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display page explaining proper upgrade process,
     * there can not be any PHP file leftovers...
     *
     * @return string HTML to output.
     */
    public function upgrade_stale_php_files_page() {
        $output = '';
        $output .= $this->header();
        $output .= $this->heading(get_string('upgradestalefiles', 'admin'));
        $output .= $this->box_start('generalbox', 'notice'); // TODO replace with alert or something
        $output .= format_text(get_string('upgradestalefilesinfo', 'admin', get_docs_url('Upgrading')), FORMAT_MARKDOWN);
        $output .= html_writer::empty_tag('br'); // TODO replace this with div or something
        $output .= html::div('buttons', $this->single_button($this->page->url, get_string('reload'), 'get'));
        $output .= $this->box_end();
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the 'environment check' page that is displayed during install.
     * @param int $maturity
     * @param boolean $envstatus final result of the check (true/false)
     * @param array $environment_results array of results gathered
     * @param string $release moodle release
     * @return string HTML to output.
     */
    public function install_environment_page($maturity, $envstatus, $environment_results, $release) {
        global $CFG;
        $output = '';

        $output .= $this->header();
        $output .= $this->maturity_warning($maturity);
        $output .= $this->heading("Moodle $release");
        $output .= $this->release_notes_link();

        $output .= $this->environment_check_table($envstatus, $environment_results);

        if (!$envstatus) {
            $output .= $this->upgrade_reload(new moodle_url('/admin/index.php', array('agreelicense' => 1, 'lang' => $CFG->lang)));
        } else {
            $output .= $this->notification(get_string('environmentok', 'admin'), 'notifysuccess');
            $output .= $this->continue_button(new moodle_url('/admin/index.php', array('agreelicense'=>1, 'confirmrelease'=>1, 'lang'=>$CFG->lang)));
        }

        $output .= $this->footer();
        return $output;
    }

    /**
     * Displays the list of plugins with unsatisfied dependencies
     *
     * @param double|string|int $version Moodle on-disk version
     * @param array $failed list of plugins with unsatisfied dependecies
     * @param moodle_url $reloadurl URL of the page to recheck the dependencies
     * @return string HTML
     */
    public function unsatisfied_dependencies_page($version, array $failed, moodle_url $reloadurl) {
        $output = '';

        $output .= $this->header();
        $output .= $this->heading(get_string('pluginscheck', 'admin'));
        $output .= $this->warning(get_string('pluginscheckfailed', 'admin', array('pluginslist' => implode(', ', array_unique($failed)))));
        $output .= $this->plugins_check_table(plugin_manager::instance(), $version, array('xdep' => true));
        $output .= $this->warning(get_string('pluginschecktodo', 'admin'));
        $output .= $this->continue_button($reloadurl);

        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the 'You are about to upgrade Moodle' page. The first page
     * during upgrade.
     * @param string $strnewversion
     * @param int $maturity
     * @return string HTML to output.
     */
    public function upgrade_confirm_page($strnewversion, $maturity) {
        $output = '';

        $continueurl = new moodle_url('index.php', array('confirmupgrade' => 1));
        $cancelurl = new moodle_url('index.php');

        $output .= $this->header();
        $output .= $this->maturity_warning($maturity);
        $output .= $this->confirm(get_string('upgradesure', 'admin', $strnewversion), $continueurl, $cancelurl);
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the environment page during the upgrade process.
     * @param string $release
     * @param boolean $envstatus final result of env check (true/false)
     * @param array $environment_results array of results gathered
     * @return string HTML to output.
     */
    public function upgrade_environment_page($release, $envstatus, $environment_results) {
        global $CFG;
        $output = '';

        $output .= $this->header();
        $output .= $this->heading("Moodle $release");
        $output .= $this->release_notes_link();
        $output .= $this->environment_check_table($envstatus, $environment_results);

        if (!$envstatus) {
            $output .= $this->upgrade_reload(new moodle_url('/admin/index.php'), array('confirmupgrade' => 1));

        } else {
            $output .= $this->notification(get_string('environmentok', 'admin'), 'notifysuccess');

            if (empty($CFG->skiplangupgrade) and current_language() !== 'en') {
                $output .= $this->box(get_string('langpackwillbeupdated', 'admin'), 'generalbox', 'notice');
            }

            $output .= $this->continue_button(new moodle_url('/admin/index.php', array('confirmupgrade' => 1, 'confirmrelease' => 1)));
        }

        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the upgrade page that lists all the plugins that require attention.
     * @param plugin_manager $pluginman provides information about the plugins.
     * @param available_update_checker $checker provides information about available updates.
     * @param int $version the version of the Moodle code from version.php.
     * @param bool $showallplugins
     * @param moodle_url $reloadurl
     * @param moodle_url $continueurl
     * @return string HTML to output.
     */
    public function upgrade_plugin_check_page(plugin_manager $pluginman, available_update_checker $checker,
            $version, $showallplugins, $reloadurl, $continueurl) {
        global $CFG;

        $output = $this->header();
        $output .= html::p(get_string('pluginchecknotice', 'core_plugin'));
        if (empty($CFG->disableupdatenotifications)) {
            $output .= $this->single_button(new moodle_url($reloadurl, array('fetchupdates' => 1)), get_string('checkforupdates', 'core_plugin'));
            if ($timefetched = $checker->get_last_timefetched()) {
                $output .= get_string('checkforupdateslast', 'core_plugin',
                    userdate($timefetched, get_string('strftimedatetime', 'core_langconfig')));
            }
        }

        $output .= $this->plugins_check_table($pluginman, $version, array('full' => $showallplugins));
        $output .= $this->upgrade_reload($reloadurl);

        if ($pluginman->some_plugins_updatable()) {
            $output .= bootstrap::alert_info($this->help_icon('upgradepluginsinfo', 'core_admin', get_string('upgradepluginsfirst', 'core_admin')));
        }

        $button = new single_button($continueurl, get_string('upgradestart', 'admin'), 'get');
        $button->class = 'btn btn-primary';
        $output .= $this->render($button);
        $output .= $this->footer();

        return $output;
    }


    /**
     * Output a warning message, of the type that appears on the admin notifications page.
     * @param string $message the message to display.
     * @param string $type type class
     * @return string HTML to output.
     */
    protected function warning($message, $type = '') {
        if ($type == 'error') {
            return bootstrap::alert_error($message);
        }
        if ($type == '') {
            return bootstrap::alert_error($message);
        }
        // what other types are there?
        return bootstrap::alert($type, "warning type:$type".$message);
    }


    /**
     * Display a warning about installing development code if necesary.
     * @param int $maturity
     * @return string HTML to output.
     */
    protected function maturity_warning($maturity) {
        if ($maturity == MATURITY_STABLE) {
            return ''; // No worries.
        }

        $maturitylevel = get_string('maturity' . $maturity, 'admin');
        return html_writer::tag('div',
                    $this->container(get_string('maturitycorewarning', 'admin', $maturitylevel)) .
                    $this->container($this->doc_link('admin/versions', get_string('morehelp'))),
                'alert maturitywarning');
    }

    /**
     * Output the copyright notice.
     * @return string HTML to output.
     */
    protected function moodle_copyright() {
        global $CFG;

        $copyrighttext = '<p><a href="http://moodle.org/">Moodle</a> '.
                         '<a href="http://docs.moodle.org/dev/Releases" title="'.$CFG->version.'">'.$CFG->release.'</a></p>'.
                         '<p>Copyright &copy; 1999 onwards, Martin Dougiamas '.
                         'and <a href="http://docs.moodle.org/dev/Credits">many other contributors</a>.</p>'.
                         '<p><a href="http://docs.moodle.org/dev/License">GNU Public License</a><p>';
        return bootstrap::alert_info($copyrighttext);
    }

    /**
     * Display a warning about installing development code if necesary.
     * @param int $maturity
     * @return string HTML to output.
     */
    protected function maturity_info($maturity) {
        if ($maturity == MATURITY_STABLE) {
            return ''; // No worries.
        }

        $maturitylevel = get_string('maturity' . $maturity, 'admin');
        return $this->box(
                    get_string('maturitycoreinfo', 'admin', $maturitylevel) . ' ' .
                    $this->doc_link('admin/versions', get_string('morehelp')),
                'alert maturityinfo maturity'.$maturity);
    }

    /**
     * Displays the info about available Moodle updates
     *
     * @param array|null $updates array of available_update_info objects or null
     * @param int|null $fetch timestamp of the most recent updates fetch or null (unknown)
     * @return string
     */
    protected function available_updates($updates, $fetch) {

        $updateinfo = $this->box_start('alert alert-info availableupdatesinfo');
        $someupdateavailable = false;
        if (is_array($updates)) {
            if (is_array($updates['core'])) {
                $someupdateavailable = true;
                $updateinfo .= $this->heading(get_string('updateavailable', 'core_admin'), 3);
                foreach ($updates['core'] as $update) {
                    $updateinfo .= $this->moodle_available_update_info($update);
                }
            }
            unset($updates['core']);
            // If something has left in the $updates array now, it is updates for plugins.
            if (!empty($updates)) {
                $someupdateavailable = true;
                $updateinfo .= $this->heading(get_string('updateavailableforplugin', 'core_admin'), 3);
                $pluginsoverviewurl = new moodle_url('/admin/plugins.php', array('updatesonly' => 1));
                $updateinfo .= $this->container(get_string('pluginsoverviewsee', 'core_admin',
                    array('url' => $pluginsoverviewurl->out())));
            }
        }

        if (!$someupdateavailable) {
            $now = time();
            if ($fetch and ($fetch <= $now) and ($now - $fetch < HOURSECS)) {
                $updateinfo .= $this->heading(get_string('updateavailablenot', 'core_admin'), 3);
            }
        }

        $updateinfo .= $this->container_start('checkforupdates');
        $updateinfo .= $this->single_button(new moodle_url($this->page->url, array('fetchupdates' => 1)), get_string('checkforupdates', 'core_plugin'));
        if ($fetch) {
            $updateinfo .= $this->container(get_string('checkforupdateslast', 'core_plugin',
                userdate($fetch, get_string('strftimedatetime', 'core_langconfig'))));
        }
        $updateinfo .= $this->container_end();

        $updateinfo .= $this->box_end();

        return $updateinfo;
    }


    public function upgrade_reload($url) {
        return html::div(html::a_button($url, bootstrap::icon('refresh') . ' ' . get_string('reload')));
    }

    /**
     * need to split these tables or something, mark the headers as thead?
     */
    public function plugins_check_table(plugin_manager $pluginman, $version, array $options = array()) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        if (empty($plugininfo)) {
            return '';
        }

        $options['full'] = isset($options['full']) ? (bool)$options['full'] : false;
        $options['xdep'] = isset($options['xdep']) ? (bool)$options['xdep'] : false;

        $table = new html_table();
        $table->id = 'plugins-check';
        $table->attributes['class'] = 'table table-striped table-hover';
        $table->head = array(
            get_string('displayname', 'core_plugin'),
            get_string('rootdir', 'core_plugin'),
            get_string('source', 'core_plugin'),
            get_string('versiondb', 'core_plugin'),
            get_string('versiondisk', 'core_plugin'),
            get_string('requires', 'core_plugin'),
            get_string('status', 'core_plugin'),
        );
        $table->data = array();

        $numofhighlighted = array();    // number of highlighted rows per this subsection

        foreach ($plugininfo as $type => $plugins) {

            $header = new html_table_cell($pluginman->plugintype_name_plural($type));
            $header->header = true;
            $header->colspan = count($table->head);
            $header = new html_table_row(array($header));
            $header->attributes['class'] = 'plugintypeheader type-' . $type;

            $numofhighlighted[$type] = 0;

            if (empty($plugins) and $options['full']) {
                $msg = new html_table_cell(get_string('noneinstalled', 'core_plugin'));
                $msg->colspan = count($table->head);
                $row = new html_table_row(array($msg));
                $row->attributes['class'] .= 'warning msg-noneinstalled';
                $table->data[] = $header;
                $table->data[] = $row;
                continue;
            }

            $plugintyperows = array();

            foreach ($plugins as $name => $plugin) {
                $row = new html_table_row();
                $row->attributes['class'] = 'type-' . $plugin->type . ' name-' . $plugin->type . '_' . $plugin->name;

                if ($this->page->theme->resolve_image_location('icon', $plugin->type . '_' . $plugin->name)) {
                    $icon = $this->output->pix_icon('icon', '', $plugin->type . '_' . $plugin->name, array('class' => 'smallicon pluginicon'));
                } else {
                    $icon = bootstrap::icon_spacer();
                }
                $displayname  = $icon . ' ' . $plugin->displayname;
                $displayname = new html_table_cell($displayname);

                $rootdir = new html_table_cell($plugin->get_dir());

                if ($isstandard = $plugin->is_standard()) {
                    $source = new html_table_cell(get_string('sourcestd', 'core_plugin'));
                } else {
                    $source = new html_table_cell(label::warning(get_string('sourceext', 'core_plugin')));
                }

                $versiondb = new html_table_cell($plugin->versiondb);
                $versiondisk = new html_table_cell($plugin->versiondisk);

                $statuscode = $plugin->get_status();
                $status = get_string('status_' . $statuscode, 'core_plugin');
                if ($statuscode === 'upgrade') {
                    $status = label::info($status);
                } else if ($statuscode === 'new') {
                    $status = label::success($status);
                }

                $availableupdates = $plugin->available_updates();
                if (!empty($availableupdates) and empty($CFG->disableupdatenotifications)) {
                    foreach ($availableupdates as $availableupdate) {
                        $status .= $this->plugin_available_update_info($availableupdate);
                    }
                }

                $status = new html_table_cell($status);

                $requires = new html_table_cell($this->required_column($plugin, $pluginman, $version));

                $statusisboring = in_array($statuscode, array(
                        plugin_manager::PLUGIN_STATUS_NODB, plugin_manager::PLUGIN_STATUS_UPTODATE));

                $coredependency = $plugin->is_core_dependency_satisfied($version);
                $otherpluginsdependencies = $pluginman->are_dependencies_satisfied($plugin->get_other_required_plugins());
                $dependenciesok = $coredependency && $otherpluginsdependencies;

                if ($options['xdep']) {
                    // we want to see only plugins with failed dependencies
                    if ($dependenciesok) {
                        continue;
                    }

                } else if ($isstandard and $statusisboring and $dependenciesok and empty($availableupdates)) {
                    // no change is going to happen to the plugin - display it only
                    // if the user wants to see the full list
                    if (empty($options['full'])) {
                        continue;
                    }
                }

                // ok, the plugin should be displayed
                $numofhighlighted[$type]++;

                $row->cells = array($displayname, $rootdir, $source,
                    $versiondb, $versiondisk, $requires, $status);
                $plugintyperows[] = $row;
            }

            if (empty($numofhighlighted[$type]) and empty($options['full'])) {
                continue;
            }

            $table->data[] = $header;
            $table->data = array_merge($table->data, $plugintyperows);
        }

        $sumofhighlighted = array_sum($numofhighlighted);

        if ($options['xdep']) {
            // we do not want to display no heading and links in this mode
            $out = '';

        } else if ($sumofhighlighted == 0) {
            $out  = $this->output->container_start('nonehighlighted', 'plugins-check-info');
            $out .= $this->output->heading(get_string('nonehighlighted', 'core_plugin'));
            if (empty($options['full'])) {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 1)),
                    get_string('nonehighlightedinfo', 'core_plugin'));
            }
            $out .= $this->output->container_end();

        } else {
            $out  = $this->output->container_start('somehighlighted', 'plugins-check-info');
            $out .= $this->output->heading(get_string('somehighlighted', 'core_plugin', $sumofhighlighted));
            if (empty($options['full'])) {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 1)),
                    get_string('somehighlightedinfo', 'core_plugin'));
            } else {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 0)),
                    get_string('somehighlightedonly', 'core_plugin'));
            }
            $out .= $this->output->container_end();
        }

        if ($sumofhighlighted > 0 or $options['full']) {
            $out .= html_writer::table($table);
        }

        return $out;
    }

    /**
     * Formats the information that needs to go in the 'Requires' column.
     * @param plugininfo_base $plugin the plugin we are rendering the row for.
     * @param plugin_manager $pluginman provides data on all the plugins.
     * @param string $version
     * @return string HTML code
     */
    protected function required_column(plugininfo_base $plugin, plugin_manager $pluginman, $version) {
        $requires = array();

        if (!empty($plugin->versionrequires)) {
            $required = get_string('moodleversion', 'core_plugin', $plugin->versionrequires);
            if ($plugin->versionrequires > $version) {
                $required  = label::important($required);
            }
            $requires[] = html::li($required);
        }

        foreach ($plugin->get_other_required_plugins() as $component => $requiredversion) {
            $ok = true;
            $otherplugin = $pluginman->get_plugin_info($component);

            if (is_null($otherplugin)) {
                $ok = false;
            } else if ($requiredversion != ANY_VERSION and $otherplugin->versiondisk < $requiredversion) {
                $ok = false;
            }


            if ($requiredversion != ANY_VERSION) {
                $required = get_string('otherpluginversion', 'core_plugin', array('component' => $component, 'version' => $requiredversion));
            } else {
                $required = get_string('otherplugin', 'core_plugin', array('component' => $component, 'version' => $requiredversion));
            }
            if (!$ok) {
                $required = label::important($required);
            }
            $requires[] = html::li($required);
        }

        if (!$requires) {
            return '';
        }
        return html::ul('list-unstyled', $requires);
    }

    public function plugins_overview_panel(plugin_manager $pluginman, array $options = array()) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        $numtotal = $numdisabled = $numextension = $numupdatable = 0;

        foreach ($plugininfo as $type => $plugins) {
            foreach ($plugins as $name => $plugin) {
                if ($plugin->get_status() === plugin_manager::PLUGIN_STATUS_MISSING) {
                    continue;
                }
                $numtotal++;
                if ($plugin->is_enabled() === false) {
                    $numdisabled++;
                }
                if (!$plugin->is_standard()) {
                    $numextension++;
                }
                if (empty($CFG->disableupdatenotifications) and $plugin->available_updates()) {
                    $numupdatable++;
                }
            }
        }

        $info = array();
        $filter = array();
        $somefilteractive = false;
        $info[] = html_writer::tag('span', get_string('numtotal', 'core_plugin', $numtotal), array('class' => 'info total'));
        $info[] = html_writer::tag('span', get_string('numdisabled', 'core_plugin', $numdisabled), array('class' => 'info disabled'));
        $info[] = html_writer::tag('span', get_string('numextension', 'core_plugin', $numextension), array('class' => 'info extension'));
        if ($numextension > 0) {
            if (empty($options['contribonly'])) {
                $filter[] = html_writer::link(
                    new moodle_url($this->page->url, array('contribonly' => 1)),
                    get_string('filtercontribonly', 'core_plugin'),
                    array('class' => 'filter-item show-contribonly')
                );
            } else {
                $filter[] = html_writer::tag('span', get_string('filtercontribonlyactive', 'core_plugin'),
                    array('class' => 'filter-item active show-contribonly'));
                $somefilteractive = true;
            }
        }
        if ($numupdatable > 0) {
            $info[] = html_writer::tag('span', get_string('numupdatable', 'core_plugin', $numupdatable), array('class' => 'info updatable'));
            if (empty($options['updatesonly'])) {
                $filter[] = html_writer::link(
                    new moodle_url($this->page->url, array('updatesonly' => 1)),
                    get_string('filterupdatesonly', 'core_plugin'),
                    array('class' => 'filter-item show-updatesonly')
                );
            } else {
                $filter[] = html_writer::tag('span', get_string('filterupdatesonlyactive', 'core_plugin'),
                    array('class' => 'filter-item active show-updatesonly'));
                $somefilteractive = true;
            }
        }
        if ($somefilteractive) {
            $filter[] = html_writer::link($this->page->url, get_string('filterall', 'core_plugin'), array('class' => 'filter-item show-all'));
        }

        $output  = $this->output->box(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $info), '', 'plugins-overview-panel');

        if (!empty($filter)) {
            $output .= $this->output->box(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $filter), '', 'plugins-overview-filter');
        }

        return $output;
    }

    public function plugins_control_panel(plugin_manager $pluginman, array $options = array()) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        // Filter the list of plugins according the options.
        if (!empty($options['updatesonly'])) {
            $updateable = array();
            foreach ($plugininfo as $plugintype => $pluginnames) {
                foreach ($pluginnames as $pluginname => $pluginfo) {
                    if (!empty($pluginfo->availableupdates)) {
                        foreach ($pluginfo->availableupdates as $pluginavailableupdate) {
                            if ($pluginavailableupdate->version > $pluginfo->versiondisk) {
                                $updateable[$plugintype][$pluginname] = $pluginfo;
                            }
                        }
                    }
                }
            }
            $plugininfo = $updateable;
        }

        if (!empty($options['contribonly'])) {
            $contribs = array();
            foreach ($plugininfo as $plugintype => $pluginnames) {
                foreach ($pluginnames as $pluginname => $pluginfo) {
                    if (!$pluginfo->is_standard()) {
                        $contribs[$plugintype][$pluginname] = $pluginfo;
                    }
                }
            }
            $plugininfo = $contribs;
        }

        if (empty($plugininfo)) {
            return '';
        }

        $table = new html_table();
        $table->id = 'plugins-control-panel';
        $table->head = array(
            get_string('displayname', 'core_plugin'),
            get_string('source', 'core_plugin'),
            get_string('version', 'core_plugin'),
            get_string('availability', 'core_plugin'),
            get_string('actions', 'core_plugin'),
            get_string('notes','core_plugin'),
        );
        $table->colclasses = array(
            'pluginname', 'source', 'version', 'availability', 'actions', 'notes'
        );

        foreach ($plugininfo as $type => $plugins) {

            $header = new html_table_cell($pluginman->plugintype_name_plural($type));
            $header->header = true;
            $header->colspan = count($table->head);
            $header = new html_table_row(array($header));
            $header->attributes['class'] = 'plugintypeheader type-' . $type;
            $table->data[] = $header;

            if (empty($plugins)) {
                $msg = new html_table_cell(get_string('noneinstalled', 'core_plugin'));
                $msg->colspan = count($table->head);
                $row = new html_table_row(array($msg));
                $row->attributes['class'] .= 'msg msg-noneinstalled';
                $table->data[] = $row;
                continue;
            }

            foreach ($plugins as $name => $plugin) {
                $row = new html_table_row();
                $row->attributes['class'] = 'type-' . $plugin->type . ' name-' . $plugin->type . '_' . $plugin->name;

                if ($this->page->theme->resolve_image_location('icon', $plugin->type . '_' . $plugin->name)) {
                    $icon = $this->output->pix_icon('icon', '', $plugin->type . '_' . $plugin->name, array('class' => 'icon pluginicon'));
                } else {
                    $icon = $this->output->pix_icon('spacer', '', 'moodle', array('class' => 'icon pluginicon noicon'));
                }
                if ($plugin->get_status() === plugin_manager::PLUGIN_STATUS_MISSING) {
                    $msg = html_writer::tag('span', get_string('status_missing', 'core_plugin'), array('class' => 'notifyproblem'));
                    $row->attributes['class'] .= ' missingfromdisk';
                } else {
                    $msg = '';
                }
                $pluginname  = html_writer::tag('div', $icon . '' . $plugin->displayname . ' ' . $msg, array('class' => 'displayname')).
                               html_writer::tag('div', $plugin->component, array('class' => 'componentname'));
                $pluginname  = new html_table_cell($pluginname);

                if ($plugin->is_standard()) {
                    $row->attributes['class'] .= ' standard';
                    $source = new html_table_cell(get_string('sourcestd', 'core_plugin'));
                } else {
                    $row->attributes['class'] .= ' extension';
                    $source = new html_table_cell(get_string('sourceext', 'core_plugin'));
                }

                $version = new html_table_cell($plugin->versiondb);

                $isenabled = $plugin->is_enabled();
                if (is_null($isenabled)) {
                    $availability = new html_table_cell('');
                } else if ($isenabled) {
                    $row->attributes['class'] .= ' enabled';
                    $availability = new html_table_cell(get_string('pluginenabled', 'core_plugin'));
                } else {
                    $row->attributes['class'] .= ' disabled';
                    $availability = new html_table_cell(get_string('plugindisabled', 'core_plugin'));
                }

                $actions = array();

                $settingsurl = $plugin->get_settings_url();
                if (!is_null($settingsurl)) {
                    $actions[] = html_writer::link($settingsurl, get_string('settings', 'core_plugin'), array('class' => 'settings'));
                }

                $uninstallurl = $plugin->get_uninstall_url();
                if (!is_null($uninstallurl)) {
                    $actions[] = html_writer::link($uninstallurl, get_string('uninstall', 'core_plugin'), array('class' => 'uninstall'));
                }

                $actions = new html_table_cell(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $actions));

                $requriedby = $pluginman->other_plugins_that_require($plugin->component);
                if ($requriedby) {
                    $requiredby = html_writer::tag('div', get_string('requiredby', 'core_plugin', implode(', ', $requriedby)),
                        array('class' => 'requiredby'));
                } else {
                    $requiredby = '';
                }

                $updateinfo = '';
                if (empty($CFG->disableupdatenotifications) and is_array($plugin->available_updates())) {
                    foreach ($plugin->available_updates() as $availableupdate) {
                        $updateinfo .= $this->plugin_available_update_info($availableupdate);
                    }
                }

                $notes = new html_table_cell($requiredby.$updateinfo);

                $row->cells = array(
                    $pluginname, $source, $version, $availability, $actions, $notes
                );
                $table->data[] = $row;
            }
        }

        return html_writer::table($table);
    }

    /**
     * Helper method to render the information about the available plugin update
     *
     * The passed objects always provides at least the 'version' property containing
     * the (higher) version of the plugin available.
     *
     * @param available_update_info $updateinfo information about the available update for the plugin
     */
    protected function plugin_available_update_info(available_update_info $updateinfo) {

        $boxclasses = 'pluginupdateinfo';
        $info = array();

        if (isset($updateinfo->release)) {
            $info[] = html_writer::tag('span', get_string('updateavailable_release', 'core_plugin', $updateinfo->release),
                array('class' => 'info release'));
        }

        if (isset($updateinfo->maturity)) {
            $info[] = html_writer::tag('span', get_string('maturity'.$updateinfo->maturity, 'core_admin'),
                array('class' => 'info maturity'));
            $boxclasses .= ' maturity'.$updateinfo->maturity;
        }

        if (isset($updateinfo->download)) {
            $info[] = html_writer::link($updateinfo->download, get_string('download'), array('class' => 'info download'));
        }

        if (isset($updateinfo->url)) {
            $info[] = html_writer::link($updateinfo->url, get_string('updateavailable_moreinfo', 'core_plugin'),
                array('class' => 'info more'));
        }

        $box  = $this->output->box_start($boxclasses);
        $box .= html::div('version', get_string('updateavailable', 'core_plugin', $updateinfo->version));
        $box .= $this->output->box(implode(html::span('separator', ' '), $info), '');
        $box .= $this->output->box_end();

        return $box;
    }

    public function environment_check_table($result, $environment_results) {
        global $CFG;

        $servertable = new html_table();
        $servertable->head  = array(
            get_string('name'),
            get_string('info'),
            get_string('report'),
            get_string('status'),
        );
        $servertable->attributes['class'] = 'table table-striped table-hover';

        $serverdata = array('success'=>array(), 'warning'=>array(), 'important'=>array());

        $othertable = new html_table();
        $othertable->head  = array(
            get_string('info'),
            get_string('report'),
            get_string('status'),
        );
        $othertable->attributes['class'] = 'table table-striped table-hover';

        $otherdata = array('success'=>array(), 'warning'=>array(), 'important'=>array());

        // Iterate over each environment_result
        $continue = true;
        foreach ($environment_results as $environment_result) {
            $errorline   = false;
            $warningline = false;
            $stringtouse = '';
            if ($continue) {
                $type = $environment_result->getPart();
                $info = $environment_result->getInfo();
                $status = $environment_result->getStatus();
                $error_code = $environment_result->getErrorCode();
                // Process Report field
                $rec = new stdClass();
                // Something has gone wrong at parsing time
                if ($error_code) {
                    $stringtouse = 'environmentxmlerror';
                    $rec->error_code = $error_code;
                    $status = get_string('error');
                    $errorline = true;
                    $continue = false;
                }

                if ($continue) {
                    if ($rec->needed = $environment_result->getNeededVersion()) {
                        // We are comparing versions
                        $rec->current = $environment_result->getCurrentVersion();
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequireversion';
                        } else {
                            $stringtouse = 'environmentrecommendversion';
                        }

                    } else if ($environment_result->getPart() == 'custom_check') {
                        // We are checking installed & enabled things
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequirecustomcheck';
                        } else {
                            $stringtouse = 'environmentrecommendcustomcheck';
                        }

                    } else if ($environment_result->getPart() == 'php_setting') {
                        if ($status) {
                            $stringtouse = 'environmentsettingok';
                        } else if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentmustfixsetting';
                        } else {
                            $stringtouse = 'environmentshouldfixsetting';
                        }

                    } else {
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequireinstall';
                        } else {
                            $stringtouse = 'environmentrecommendinstall';
                        }
                    }

                    // Calculate the status value
                    if ($environment_result->getBypassStr() != '') {            //Handle bypassed result (warning)
                        $status = get_string('bypassed');
                        $warningline = true;
                    } else if ($environment_result->getRestrictStr() != '') {   //Handle restricted result (error)
                        $status = get_string('restricted');
                        $errorline = true;
                    } else {
                        if ($status) {                                          //Handle ok result (ok)
                            $status = get_string('ok');
                        } else {
                            if ($environment_result->getLevel() == 'optional') {//Handle check result (warning)
                                $status = get_string('check');
                                $warningline = true;
                            } else {                                            //Handle error result (error)
                                $status = get_string('check');
                                $errorline = true;
                            }
                        }
                    }
                }

                // Build the text
                $linkparts = array();
                $linkparts[] = 'admin/environment';
                $linkparts[] = $type;
                if (!empty($info)){
                    $linkparts[] = $info;
                }
                if (empty($CFG->docroot)) {
                    $report = get_string($stringtouse, 'admin', $rec);
                } else {
                    $report = $this->doc_link(join($linkparts, '/'), get_string($stringtouse, 'admin', $rec));
                }

                // Format error or warning line
                if ($errorline || $warningline) {
                    $messagetype = $errorline? 'important':'warning';
                } else {
                    $messagetype = 'success';
                }
                $status = label::make($messagetype, $status);
                // Here we'll store all the feedback found
                $feedbacktext = '';
                // Append the feedback if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getFeedbackStr(), 'alert alert-'.$messagetype);
                //Append the bypass if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getBypassStr(), 'alert');
                //Append the restrict if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getRestrictStr(), 'alert alert-important');

                $report .= $feedbacktext;

                // Add the row to the table
                if ($environment_result->getPart() == 'custom_check'){
                    $otherdata[$messagetype][] = array ($info, $report, $status);
                } else {
                    $serverdata[$messagetype][] = array ($type, $info, $report, $status);
                }
            }
        }

        $servertable->data = array_merge($serverdata['important'], $serverdata['warning'], $serverdata['success']);
        $othertable->data = array_merge($otherdata['important'], $otherdata['warning'], $otherdata['success']);

        $output = $this->heading(get_string('serverchecks', 'admin'));
        $output .= html_writer::table($servertable);
        if (count($othertable->data)) {
            $output .= $this->heading(get_string('customcheck', 'admin'));
            $output .= html_writer::table($othertable);
        }

        // Finally, if any error has happened, print the summary box.
        if (!$result) {
            $output .= bootstrap::alert_error(get_string('environmenterrortodo', 'admin'));
        }

        return $output;
    }
}
