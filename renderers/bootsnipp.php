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
 * Mid-level functional chunks built on Bootstrap Inspired by the
 * examples from the site Bootsnipp.com
 *
 * @package    theme_bootstrap_renderers
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('bootstrap.php');

class bootsnipp {

    public static function sign_up_sign_in($url) {
        // TODO: add sign in as guest.
        $dropdown_content = array('class'=>'dropdown-menu', 'style'=>'padding: 15px; padding-bottom: 0px;');
        $form = array('method'=>'post', 'action'=>$url, 'accept-charset'=>'UTF-8');
        $username = array('type'=>'text', 'placeholder'=>'Username', 'id'=>'username', 'name'=>'username');
        $password = array('type'=>'password', 'placeholder'=>'Password', 'id'=>'password', 'name'=>'password');
        $submit = array('value'=>'Sign In', 'class'=>'btn btn-primary btn-block', 'id'=>'sign-in');
        return html::ul('nav pull-right',
            html::li(html::a(new moodle_url('/login/signup.php'), 'Sign Up')) .
            bootstrap::list_divider() .
            bootstrap::dropdown('Sign In',
                html::div($dropdown_content,
                    html::form($form,
                    html::input($username) .
                    html::input($password) .
                    html::checkbox('rememberusername', 'Remember username') .
                    html::submit($submit)))));
    }
    public static function guest_user($text, $guest, $logout) {
        $links[] = bootstrap::li_icon_link($guest['link'], 'user', $guest['name']);
        $links[] = bootstrap::list_divider();
        $links[] = bootstrap::li_icon_link($logout['link'], 'off', $logout['name']);

        return html::ul('nav pull-right', bootstrap::dropdown_menu($text, $links));
    }
    public static function signed_in($user, $loginfailures, $mnet, $real, $role_switch, $logout) {
        $links[] = bootstrap::li_icon_link($user['link'], 'user', 'Profile');
        if ($mnet !== null) {
            $links[] = bootstrap::li_icon_link($mnet['link'], 'globe', $mnet['name']);
        }
        if (isset($loginfailures)) {
            $links[] = bootstrap::li_icon_link($loginfailures['link'], 'warning-sign', $loginfailures['name']);
            $user['name'] .= ' ' . bootstrap::icon('warning-sign');
        }
        if ($role_switch !== null) {
            $links[] = bootstrap::li_icon_link($role_switch['link'], 'repeat', $role_switch['name']);
        }
        if ($real !== null) {
            $links[] = bootstrap::li_icon_link($real['link'], 'user', $real['name']);
        }
        $links[] = bootstrap::list_divider();
        $links[] = bootstrap::li_icon_link($logout['link'], 'off', $logout['name']);

        return html::ul('nav pull-right', bootstrap::dropdown_menu($user['name'], $links));
    }
}
