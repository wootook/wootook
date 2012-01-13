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

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/application/bootstrap.php';

includeLang('buildings');

$user = Wootook_Empire_Model_User::getSingleton();
$planet = $user->getCurrentPlanet();
$mode = isset($_GET['mode']) ? $_GET['mode'] : null;

switch ($mode) {
case 'fleet':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) < 1) {
        $layout = new Wootook_Core_Layout(Wootook_Core_Layout::DOMAIN_FRONTEND);
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Wootook::__('Shipyard is required');
        $block['message'] = Wootook::__('In order to build ships you will need to build a shipyard building.');

        echo $layout->render();
        break;
    }

    /** @var Legacies_Empire_Model_Planet_Building_Shipyard $shipyard */
    $shipyard = $planet->getShipyard();
    if (isset($_POST['ship']) && is_array($_POST['ship'])) {
        foreach ($_POST['ship'] as $shipId => $count) {
            $shipId = intval($shipId);
            $count = intval($count);

            $shipyard->appendQueue($shipId, $count);
        }
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    }

    $layout = new Wootook_Core_Layout();
    $layout->load('planet.shipyard');

    echo $layout->render();
    break;

case 'research':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_RESEARCH_LAB) < 1) {
        $layout = new Wootook_Core_Layout();
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Wootook::__('Research lab is required');
        $block['message'] = Wootook::__('In order to do technological researches, you will need to build a research lab building.');

        echo $layout->render();
        break;
    }

    if (isset($_GET['research']) && !empty($_GET['research'])) {
        $data = $planet->getAllDatas();
        $planet->appendBuildingQueue(intval($_GET['research']), isset($_GET['destroy']));
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    } else if (isset($_GET['cancel']) && !empty($_GET['cancel'])) {
        $planet->dequeueItem($_GET['cancel']);
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    }

    $layout = new Wootook_Core_Layout();
    $layout->load('planet.research-lab');

    echo $layout->render();
    break;

case 'defense':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) < 1) {
        $layout = new Wootook_Core_Layout();
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Wootook::__('Shipyard is required');
        $block['message'] = Wootook::__('In order to build ships you will need to build a shipyard building.');

        echo $layout->render();
        break;
    }

    /** @var Legacies_Empire_Model_Planet_Building_Shipyard $shipyard */
    $shipyard = $planet->getShipyard();
    if (isset($_POST['defense']) && is_array($_POST['defense'])) {
        foreach ($_POST['defense'] as $defenseId => $count) {
            $defenseId = intval($defenseId);
            $count = intval($count);

            $shipyard->appendQueue($defenseId, $count);
        }
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    }

    $layout = new Wootook_Core_Layout();
    $layout->load('planet.defense');

    /** @var Legacies_Empire_Block_Planet_Shipyard $block */
    $block = $layout->getBlock('item-list');
    $block->setType(Legacies_Empire::TYPE_DEFENSE);

    echo $layout->render();
    break;

default:
    if (isset($_GET['building']) && !empty($_GET['building'])) {
        $data = $planet->getAllDatas();
        $planet->appendBuildingQueue(intval($_GET['building']), isset($_GET['destroy']));
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    } else if (isset($_GET['cancel']) && !empty($_GET['cancel'])) {
        $planet->dequeueItem($_GET['cancel']);
        $planet->save();

        Wootook::getResponse()
            ->setRedirect(Wootook::getUrl('buildings.php', array('mode' => $mode)))
            ->sendHeaders();
        exit(0);
    }

    $layout = new Wootook_Core_Layout();
    $layout->load('planet.buildings');
    echo $layout->render();
    break;
}

