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

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.',PHP_VERSION);
    define('PHP_VERSION_ID', (((int)$version[0]) * 10000 + ((int)$version[1]) * 100 + ((int)$version[2])));
    unset($version);
}

if (!defined('DEBUG') && ($env = getenv('DEBUG')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('DEBUG', true);
} else if (!defined('DEBUG') && isset($_SERVER['DEBUG']) && in_array(strtolower($_SERVER['DEBUG']), array('1', 'on', 'true'))) {
    define('DEBUG', true);
}

if (!defined('DEPRECATION') && ($env = getenv('DEPRECATION')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('DEPRECATION', true);
} else if (!defined('DEPRECATION') && isset($_SERVER['DEPRECATION']) && in_array(strtolower($_SERVER['DEPRECATION']), array('1', 'on', 'true'))) {
    define('DEPRECATION', true);
}

if (!defined('BCNUMBERS') && ($env = getenv('BCNUMBERS')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('BCNUMBERS', true);
} else if (!defined('BCNUMBERS') && isset($_SERVER['BCNUMBERS']) && in_array(strtolower($_SERVER['BCNUMBERS']), array('1', 'on', 'true'))) {
    define('BCNUMBERS', true);
}

if (!defined('DEBUG')) {
    @ini_set('display_errors', false);
} else {
    @ini_set('display_errors', true);
    @error_reporting(E_ALL | E_STRICT);
}

defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

defined('PHPEXT') || define('PHPEXT', 'php');

defined('VERSION') || define('VERSION', '1.5.0-beta2');

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'libraries',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'local',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'community',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'core',
    get_include_path()
    )));

function __autoload($class) {
    include_once str_replace('_', '/', $class) . '.php';
}

if (defined('IN_ADMIN')) {
    $website = new Wootook_Core_Model_Website();
    $website->setId(0)->setData('code', 'admin');
    Wootook::addWebsite($website);
    Wootook::setDefaultWebsite($website);

    $game = new Wootook_Core_Model_Game();
    $game->setId(0)->setData('code', 'admin')->setData('website_id', $website->getId());
    Wootook::addGame($game);
    Wootook::setDefaultGame($game);
}

include ROOT_PATH . 'includes/constants.php';

Wootook_Core_Time::init();
Wootook_Core_ErrorProfiler::register();
Wootook_Core_Model_Config_Events::registerEvents();

if (!defined('IN_INSTALL') && 0 === filesize(APPLICATION_PATH . 'config' . DIRECTORY_SEPARATOR . 'local.php')) {
    header('HTTP/1.1 307 Temporary Redirect');
    $url = Wootook::getUrl('install/');
    header("Location: $url");
    die();
}

$lang = array();

define('DEFAULT_LANG', 'fr');

include(ROOT_PATH . 'includes/debug.class.'.PHPEXT);
$debug = new Debug();

include(ROOT_PATH . 'includes/functions.' . PHPEXT);
include(ROOT_PATH . 'includes/unlocalised.' . PHPEXT);
include(ROOT_PATH . 'includes/todofleetcontrol.' . PHPEXT);
include(ROOT_PATH . 'language/' . DEFAULT_LANG . '/lang_info.cfg');
include(ROOT_PATH . 'includes/vars.' . PHPEXT);
include(ROOT_PATH . 'includes/strings.' . PHPEXT);

$user = Wootook_Empire_Model_User::getSingleton();

if (!defined('DISABLE_IDENTITY_CHECK')) {
    if (($user === null || !$user->getId())) {
        $url = Wootook::getUrl('login.php');
        header("Location: $url");
        exit(0);
    }

    if (Wootook::getGameConfig('game/general/active') && $user !== null && !in_array($user->getData('authlevel'), array(LEVEL_ADMIN, LEVEL_MODERATOR, LEVEL_OPERATOR))) {
        $layout = new Wootook_Core_Layout(Wootook_Core_Layout::DOMAIN_FRONTEND);
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Wootook::__('Game is disabled.');
        $block['message'] = Wootook::getGameConfig('game/general/closing-message');

        echo $layout->render();
        exit(0);
    }
}

includeLang('system');
includeLang('tech');

if (($user !== null && $user->getId())) {
    if (isset($_GET['cp']) && !empty($_GET['cp'])) {
        $user->updateCurrentPlanet((int) $_GET['cp']);
    }

    $planet = $user->getCurrentPlanet();

    foreach ($user->getPlanetCollection() as $userPlanet) {
        FlyingFleetHandler($userPlanet); // TODO: implement logic into a refactored model
    }

    /*
     * Update planet resources and constructions
     */
    Wootook::dispatchEvent('planet.update', array(
        'planet' => $planet
        ));
    $planet->save();
}