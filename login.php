<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
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
 * documentation for further information about customizing Wootook.
 *
 */

define('INSIDE', true);
define('INSTALL', false);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) . '/application/bootstrap.php';

includeLang('login');

if (!empty($_POST) && isset($_POST['username']) && isset($_POST['password'])) {
    $user = Wootook_Empire_Model_User::login($_POST['username'], $_POST['password'], isset($_POST['rememberme']) && !empty($_POST['rememberme']));

    $session = Wootook::getSession(Wootook_Empire_Model_User::SESSION_KEY);
    if ($user !== null && $user->getId()) {
        $url = Wootook::getUrl('overview.php');
        header("Location: $url");
    } else {
        $url = Wootook::getUrl('login.php');
        header("Location: $url");
    }
    Wootook_Core_ErrorProfiler::unregister(true);
    exit(0);
}

$db = Wootook_Database::getSingleton();
$displayedPlayerLevels = implode(',', array(
    //LEVEL_ADMIN,
    LEVEL_OPERATOR,
    LEVEL_MODERATOR,
    LEVEL_PLAYER
    ));
$userCountStatement = $db->prepare("SELECT COUNT(DISTINCT user.id) AS `user_count` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels})");
$userCountStatement->execute();
$userCount = $userCountStatement->fetch(Wootook_Database::FETCH_COLUMN, 0);

$latestPlayerStatement = $db->prepare("SELECT user.username AS `latest_player` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels}) ORDER BY user.`register_time` DESC LIMIT 1");
$latestPlayerStatement->execute();
$latestPlayer = $latestPlayerStatement->fetch(Wootook_Database::FETCH_COLUMN, 0);

$onlinePlayersStatement = $db->prepare("SELECT COUNT(DISTINCT user.id) AS `online_players` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels}) AND user.`onlinetime` > (UNIX_TIMESTAMP() - 900)");
$onlinePlayersStatement->execute();
$onlinePlayers = $onlinePlayersStatement->fetch(Wootook_Database::FETCH_COLUMN, 0);

$layout = new Wootook_Core_Layout();
$layout->load('login');
$block = $layout->getBlock('login');

$block['user_count'] = $userCount;
$block['latest_player'] = $latestPlayer;
$block['online_players'] = $onlinePlayers;

$block['server_name'] = Wootook::getWebsiteConfig('game/general/name');
$block['board_url'] = Wootook::getWebsiteConfig('game/general/boards-url');

echo $layout->render();

