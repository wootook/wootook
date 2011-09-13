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

define('INSIDE', true);
define('INSTALL', false);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) . '/common.php';

includeLang('login');

if (!empty($_POST) && isset($_POST['username']) && isset($_POST['password'])) {
    $user = Legacies_Empire_Model_User::login($_POST['username'], $_POST['password'], isset($_POST['rememberme']) && !empty($_POST['rememberme']));

    $session = Legacies::getSession(Legacies_Empire_Model_User::SESSION_KEY);
    if ($user !== null && $user->getId()) {
        header("Location: frames.php");
    } else {
        header("Location: login.php");
    }
    Legacies_Core_ErrorProfiler::unregister(true);
    exit(0);
}

$db = Legacies_Database::getSingleton();
$displayedPlayerLevels = implode(',', array(
    //LEVEL_ADMIN,
    LEVEL_OPERATOR,
    LEVEL_MODERATOR,
    LEVEL_PLAYER
    ));
$userCountStatement = $db->prepare("SELECT COUNT(DISTINCT user.id) AS `user_count` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels})");
$userCountStatement->execute();
$userCount = $userCountStatement->fetch(Legacies_Database::FETCH_COLUMN, 0);

$latestPlayerStatement = $db->prepare("SELECT user.username AS `latest_player` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels}) ORDER BY user.`register_time` DESC LIMIT 1");
$latestPlayerStatement->execute();
$latestPlayer = $latestPlayerStatement->fetch(Legacies_Database::FETCH_COLUMN, 0);

$onlinePlayersStatement = $db->prepare("SELECT COUNT(DISTINCT user.id) AS `online_players` FROM {$db->getTable('users')} AS user WHERE user.authlevel IN({$displayedPlayerLevels}) AND user.`onlinetime` > (UNIX_TIMESTAMP() - 900)");
$onlinePlayersStatement->execute();
$onlinePlayers = $onlinePlayersStatement->fetch(Legacies_Database::FETCH_COLUMN, 0);

$layout = new Legacies_Core_Layout();
$layout->load('login');
$block = $layout->getBlock('login');

$block['user_count'] = $userCount;
$block['latest_player'] = $latestPlayer;
$block['online_players'] = $onlinePlayers;

$block['server_name'] = $gameConfig['game_name'];
$block['board_url'] = $gameConfig['forum_url'];

echo $layout->render();

