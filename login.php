<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

define('INSIDE' , true);
define('INSTALL' , false);
define('LOGIN'   , true);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) . '/common.php';

includeLang('login');

if (!empty($_POST) && isset($_POST['username']) && isset($_POST['password'])) {
    Legacies_Empire_Model_User::login($_POST['username'], $_POST['password'], isset($_POST['rememberme']));

    header("Location: frames.php");
    exit(0);
} else {
    $parse                 = $lang;
    $Count                 = doquery('SELECT COUNT(DISTINCT users.id) AS `players` FROM {{table}} AS users WHERE users.authlevel < 3', 'users', true);
    $LastPlayer            = doquery('SELECT users.`username` FROM {{table}} AS users ORDER BY `register_time` DESC LIMIT 1', 'users', true);
    $parse['last_user']    = $LastPlayer['username'];
    $PlayersOnline         = doquery("SELECT COUNT(DISTINCT id) AS `onlinenow` FROM {{table}} AS users WHERE `onlinetime` > (UNIX_TIMESTAMP()-900) AND users.authlevel < 3", 'users', true);
    $parse['online_users'] = $PlayersOnline['onlinenow'];
    $parse['users_amount'] = $Count['players'];
    $parse['servername']   = $gameConfig['game_name'];
    $parse['forum_url']    = $gameConfig['forum_url'];
    $parse['PasswordLost'] = $lang['PasswordLost'];

    $page = parsetemplate(gettemplate('login_body'), $parse);

    display($page, $lang['Login'], false);
}

