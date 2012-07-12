<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://wootook.org>
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

require_once __DIR__ . '/bc.php';

if (defined('IN_ADMIN')) {
    Wootook::app()->setDefaultWebsiteId(0);
    Wootook::app()->setDefaultGameId(0);
}

include ROOT_PATH . 'includes/constants.php';

if (!Wootook::$isInstalled) {
    Wootook::app()->getFrontController()
        ->getResponse()
        ->setRedirect(Wootook::getStaticUrl('install/'), Wootook\Core\Mvc\Controller\Response\Http::REDIRECT_TEMPORARY)
        ->sendHeaders();
    exit(0);
}

$lang = array();

define('DEFAULT_LANG', 'fr');

include(ROOT_PATH . 'includes/functions.' . PHPEXT);
include(ROOT_PATH . 'includes/unlocalised.' . PHPEXT);
include(ROOT_PATH . 'includes/todofleetcontrol.' . PHPEXT);
include(ROOT_PATH . 'language/' . DEFAULT_LANG . '/lang_info.cfg');
include(ROOT_PATH . 'includes/vars.' . PHPEXT);
include(ROOT_PATH . 'includes/strings.' . PHPEXT);

$user = Wootook\Player\Model\Session::getSingleton()->getPlayer();

if (!defined('DISABLE_IDENTITY_CHECK')) {
    if ($user === null || !$user->getId()) {
        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('player/account/login'))
            ->sendHeaders();
        exit(0);
    }

    if (!Wootook::getGameConfig('game/general/active') && !in_array($user->getData('authlevel'), array(LEVEL_ADMIN, LEVEL_MODERATOR, LEVEL_OPERATOR))) {
        $layout = new Wootook\Core\Layout\Manager(Wootook\Core\Layout\Manager::DOMAIN_FRONTEND);
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
