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

/**
 *
 * @deprecated
 * @param unknown_type $IsUserChecked
 */
function CheckCookies($IsUserChecked)
{
    global $lang, $gameConfig;

    includeLang('cookies');

    $userData = array();
    if (isset($_SESSION['user_id'])) {
        $sql =<<<EOF
SELECT * FROM {{table}}
    WHERE id={$_SESSION['user_id']}
    LIMIT 1
EOF;
        $userData = doquery($sql, 'users', true);
    } else if (isset($_COOKIE['nova-cookie'])) {
        $cookieData = unserialize(stripslashes($_COOKIE['nova-cookie']));
        $cookieData = array(
            'id' => (isset($cookieData['id']) ? (int) $cookieData['id'] : 0),
            'key' => (isset($cookieData['key']) ? (string) $cookieData['key'] : null)
            );

        $sql =<<<EOF
SELECT * FROM {{table}} AS user
    WHERE id={$cookieData['id']}
      AND (@key:="{$cookieData['key']}")=CONCAT((@salt:=MID(@key, 0, 4)), SHA1(CONCAT(user.username, user.password, @salt)))
    LIMIT 1
EOF;
        $userData = doquery($sql, 'users', true);
        $_SESSION['user_id'] = $userData['id'];
        if (empty($userData)) {
            message($lang['cookies']['Error2'] );
        }
    } else {
        return array(
            'state' => false,
            'record' => array()
            );
    }

    $sessionData = array(
        'request_uri' => mysql_real_escape_string($_SERVER['REQUEST_URI']),
        'remote_addr' => mysql_real_escape_string($_SERVER['REMOTE_ADDR']/* . (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? '|' . $_SERVER['HTTP_X_FORWARDED_FOR'] : '')*/),
        'user_agent' => mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])
        );
    $now = time();
    $sql =<<<EOF
UPDATE {{table}}
    SET `onlinetime` = "{$now}",
        `current_page` = "{$sessionData['request_uri']}",
        `user_lastip` = "{$sessionData['remote_addr']}",
        `user_agent` = "{$sessionData['user_agent']}"
    WHERE `id`={$_SESSION['user_id']}
    LIMIT 1;
EOF;
    doquery($sql, 'users');
    $IsUserChecked = true;

    return array(
        'state' => $IsUserChecked,
        'record' => $userData
        );
}
