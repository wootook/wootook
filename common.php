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

if (!defined('DEBUG') && in_array(strtolower(getenv('DEBUG')), array('1', 'on', 'true'))) {
    define('DEBUG', true);
}

if (!defined('DEBUG')) {
    @ini_set('display_errors', false);
} else {
    @ini_set('display_errors', true);
    @error_reporting(E_ALL | E_STRICT);
}

defined('ROOT_PATH') || define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);

defined('PHPEXT') || define('PHPEXT', require 'extension.inc');

defined('VERSION') || define('VERSION', '2009.5');

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'core',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'community',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'local',
    get_include_path()
    )));

function __autoload($class) {
    include_once str_replace('_', '/', $class) . '.php';
}

if (0 === filesize(ROOT_PATH . 'config.php')) {
    header('Location: install/');
    die();
}

$lang = array();

define('DEFAULT_SKINPATH', 'skins/xnova/');
define('TEMPLATE_DIR', realpath(ROOT_PATH . '/templates/'));
define('TEMPLATE_NAME', 'OpenGame');
define('DEFAULT_LANG', 'fr');

include(ROOT_PATH . 'includes/debug.class.'.PHPEXT);
$debug = new Debug();

include(ROOT_PATH . 'includes/constants.' . PHPEXT);
include(ROOT_PATH . 'includes/functions.' . PHPEXT);
include(ROOT_PATH . 'includes/unlocalised.' . PHPEXT);
include(ROOT_PATH . 'includes/todofleetcontrol.' . PHPEXT);
include(ROOT_PATH . 'language/' . DEFAULT_LANG . '/lang_info.cfg');
include(ROOT_PATH . 'includes/vars.' . PHPEXT);
include(ROOT_PATH . 'includes/db.' . PHPEXT);
include(ROOT_PATH . 'includes/strings.' . PHPEXT);

$gameConfig = Legacies_Core_Model_Config::getSingleton();
$user = Legacies_Empire_Model_User::getSingleton();

if (!defined('DISABLE_IDENTITY_CHECK')) {
    if (($user === null || !$user->getId())) {
        header('Location: login.php');
        exit(0);
    }

    if ($gameConfig->isEnabled() && $user !== null && !in_array($user->getData('authlevel'), array(LEVEL_ADMIN, LEVEL_MODERATOR, LEVEL_OPERATOR))) {
        message(stripslashes($gameConfig->getData('close_reason')), $gameConfig->getData('game_name'));
        exit(0);
    }
}

includeLang('system');
includeLang('tech');

$now = time();
$sql =<<<SQL_EOF
SELECT
  fleet_start_galaxy AS galaxy,
  fleet_start_system AS system,
  fleet_start_planet AS planet,
  fleet_start_type AS planet_type
    FROM {{table}}
    WHERE `fleet_start_time` <= {$now}
UNION
SELECT
  fleet_end_galaxy AS galaxy,
  fleet_end_system AS system,
  fleet_end_planet AS planet,
  fleet_end_type AS planet_type
    FROM {{table}}
    WHERE `fleet_end_time` <= {$now}
SQL_EOF;

$fleets = doquery($sql, 'fleets');
while ($row = mysql_fetch_array($fleets)) {
    FlyingFleetHandler($row);
}

unset($fleets);

include(ROOT_PATH . 'rak.php');
if (!defined('IN_ADMIN')) {
    $dpath = (isset($user['dpath']) && !empty($user["dpath"])) ? $user['dpath'] : DEFAULT_SKINPATH;
} else {
    $dpath = '../' . DEFAULT_SKINPATH;
}

if (($user !== null && $user->getId())) {
    SetSelectedPlanet($user); // FIXME

    $planetrow = $user->getCurrentPlanet();
    $galaxyrow = doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$planetrow['id']."';", 'galaxy', true);

    CheckPlanetUsedFields($planetrow); // FIXME

    /*
     * Update planet resources and constructions
     */
    Legacies::dispatchEvent('planet.update', array(
        'planet' => $planetrow
        ));
    $planetrow->save();
} else {
    $planetrow = array();
    $galaxyrow = array();
}