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
require_once dirname(__FILE__) .'/common.php';

includeLang('buildings');

$user = Legacies_Empire_Model_User::getSingleton();
$planet = $user->getCurrentPlanet();
$mode = isset($_GET['mode']) ? $_GET['mode'] : null;

switch ($mode) {
case 'fleet':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) === null) {
        $layout = new Legacies_Core_Layout();
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Legacies::__('Shipyard is required');
        $block['message'] = Legacies::__('In order to build ships you will need to build a shipyard building.');

        $layout->render();
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
    }

    $layout = new Legacies_Core_Layout();
    $layout->load('planet.shipyard');

    echo $layout->render();
    break;

case 'research':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_RESEARCH_LAB) === null) {
        $layout = new Legacies_Core_Layout();
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Legacies::__('Research lab is required');
        $block['title'] = Legacies::__('In order to do technological researches, you will need to build a research lab building.');

        $layout->render();
        break;
    }

    if (isset($_GET['research']) && !empty($_GET['research'])) {
        $data = $planet->getAllDatas();
        $planet->appendBuildingQueue(intval($_GET['research']), isset($_GET['destroy']));
        $planet->save();
    } else if (isset($_GET['cancel']) && !empty($_GET['cancel'])) {
        $planet->dequeueItem($_GET['cancel']);
        $planet->save();
    }

    $layout = new Legacies_Core_Layout();
    $layout->load('planet.research-lab');

    echo $layout->render();
    break;

case 'defense':
    if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) === null) {
        $layout = new Legacies_Core_Layout();
        $layout->load('message');

        $block = $layout->getBlock('message');
        $block['title'] = Legacies::__('Shipyard is required');
        $block['title'] = Legacies::__('In order to build ships you will need to build a shipyard building.');

        $layout->render();
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
    }

    $layout = new Legacies_Core_Layout();
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
    } else if (isset($_GET['cancel']) && !empty($_GET['cancel'])) {
        $planet->dequeueItem($_GET['cancel']);
        $planet->save();
    }

    $layout = new Legacies_Core_Layout();
    $layout->load('planet.buildings');
    echo $layout->render();
    break;
}

