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

define('INSTALL' , false);
require_once dirname(__FILE__) .'/application/bootstrap.php';

$user   = Wootook_Empire_Model_User::getSingleton();
$planet = $user->getCurrentPlanet();
$moon   = $planet->getMoon();

$action = isset($_GET['action']) ? $_GET['action'] : null;

if (isset($_POST) && !empty($_POST)) {
    $action = isset($_POST['action']) && !empty($_POST['action']) ? $_POST['action'] : $action;
    $formKey = isset($_POST['form_key']) && !empty($_POST['form_key']) ? $_POST['form_key'] : null;

    if ($formKey == Wootook::getSession('security')->getData('form_key')) {
        if ($action == 'rename' && isset($_POST['name']) && !empty($_POST['name'])) {
            $planet->setData('name', $_POST['name'])->save();
        } else if ($action == 'destroy' && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['confirm'])) {
            if (!$user->checkPassword($_POST['password'])) {
                Wootook::getSession('user')
                    ->addError(Wootook::__('Password was not correct.'));
            }

            try {
                $user->getCurrentPlanet()->destroy();

                Wootook::getSession('user')
                    ->addSucces(Wootook::__('Planet has been successfully destroyed.'));
            } catch (Wootook_Empire_Model_Planet_Exception $e) {
                Wootook::getSession('user')
                    ->addError($e->getMessage());
            }
        }
    } else {
        Wootook::getSession('user')
            ->addError(Wootook::__('Invalid security key.'));
    }

    header('HTTP/1.1 302 Found');
    header('Location: ' . Wootook::getUrl(basename(__FILE__)));
    exit(0);
} else if ($action == 'rename') {
    $layout = new Wootook_Core_Layout();
    $layout->load('overview.rename-planet');

    echo $layout->render();
    exit(0);
} else if ($action == 'destroy') {
    $layout = new Wootook_Core_Layout();
    $layout->load('overview.destroy-planet');

    echo $layout->render();
    exit(0);
} else {
    includeLang('resources');
    includeLang('overview');

    $layout = new Wootook_Core_Layout();
    $layout->load('overview');

    if ($user->getId()) {
        $planetCollection = $user->getPlanetCollection();
        $planetBlock = $layout->createBlock('core/deprecated', 'planet.view');
        $planetBlock->setTemplate('empire/overview/planet/view.phtml');

        $planetList = $layout->createBlock('core/concat', 'planet.list');
        $planetBlock->planetList = $planetList;

        $i = 0;
        foreach ($planetCollection as $userPlanet) {
            if ($userPlanet->getId() == $planet->getId()) {
                continue;
            }

            /*
             * Update planet resources and constructions
             */
            Wootook::dispatchEvent('planet.update', array(
                'planet' => $userPlanet
                ));
            $userPlanet->save();

            if ($userPlanet->getId() != $user->getCurrentPlanet()->getId() && $userPlanet->isPlanet()) {
                $id = uniqid();
                $block = $layout->createBlock('core/deprecated', "planet.list.item.{$id}");
                $block->setTemplate('empire/overview/planet/list/item.phtml');
                $block['planet'] = $userPlanet;

                $buildingQueue = $userPlanet->getBuildingQueue();
                $buildingQueue->rewind();
                $currentItem = $buildingQueue->current();
                $block['queue_item'] = $currentItem;
                $block['even'] = (($i++ % 4) == 3);

                $planetList->$id = $block;
            }
        }

        /**
         * Missile attack management
         * Refactoring needed
         * {{{
         */
        $iraks_query = doquery("SELECT * FROM {{table}} WHERE owner = '" . $user['id'] . "'", 'iraks');
        $Record = 4000;
        while ($irak = $iraks_query->fetch(PDO::FETCH_ASSOC)) {
            $Record++;
            $fpage[$irak['zeit']] = '';

            if ($irak['zeit'] > time()) {
                $time = $irak['zeit'] - time();

                $fpage[$irak['zeit']] .= InsertJavaScriptChronoApplet ("fm", $Record, $time, true);

                $planet_start = doquery("SELECT * FROM {{table}} WHERE
					galaxy = '" . $irak['galaxy'] . "' AND
					system = '" . $irak['system'] . "' AND
					planet = '" . $irak['planet'] . "' AND
					planet_type = '1'", 'planets');

                $user_planet = doquery("SELECT * FROM {{table}} WHERE
					galaxy = '" . $irak['galaxy_angreifer'] . "' AND
					system = '" . $irak['system_angreifer'] . "' AND
					planet = '" . $irak['planet_angreifer'] . "' AND
					planet_type = '1'", 'planets', true);

                if ($planet_start->rowCount() == 1) {
                    $planet = mysql_fetch_array($planet_start);
                }

                $fpage[$irak['zeit']] .= "<tr><th><div id=\"bxxfs$i\" class=\"z\"></div><font color=\"lime\">" . gmdate("H:i:s", $irak['zeit'] + 1 * 60 * 60) . "</font> </th><th colspan=\"3\"><font color=\"#0099FF\">Une attaque de missiles (" . $irak['anzahl'] . ") de " . $user_planet['name'] . " ";
                $fpage[$irak['zeit']] .= '<a href="galaxy.php?mode=3&galaxy=' . $irak["galaxy_angreifer"] . '&system=' . $irak["system_angreifer"] . '&planet=' . $irak["planet_angreifer"] . '">[' . $irak["galaxy_angreifer"] . ':' . $irak["system_angreifer"] . ':' . $irak["planet_angreifer"] . ']</a>';
                $fpage[$irak['zeit']] .= ' arrive sur la plan&egrave;te' . $planet["name"] . ' ';
                $fpage[$irak['zeit']] .= '<a href="galaxy.php?mode=3&galaxy=' . $irak["galaxy"] . '&system=' . $irak["system"] . '&planet=' . $irak["planet"] . '">[' . $irak["galaxy"] . ':' . $irak["system"] . ':' . $irak["planet"] . ']</a>';
                $fpage[$irak['zeit']] .= '</font>';
                $fpage[$irak['zeit']] .= InsertJavaScriptChronoApplet ("fm", $Record, $time, false);
                $fpage[$irak['zeit']] .= "</th>";
            }
        }
        /**
         * }}}
         */

        /*
         * Update fleet list
         */
        Wootook::dispatchEvent('fleet.update', array(
            'user' => $user
            ));

        $fleetList = $layout->createBlock('core/concat', 'fleet.list');
        $fleetCollection = $user->getFleetCollection();
        foreach ($fleetCollection as $fleet) {
            $id = uniqid();
            $block = $layout->createBlock('core/deprecated', "fleet.list.item.{$id}");
            $block->setTemplate('empire/overview/fleet/item.phtml');

            $block['class'] = $fleet->getRowClass();
            $block['fleet'] = $fleet;
            $block['user'] = $user;

            $fleetList->setPartial($id, $block);
        }

        $messageCollection = new Wootook_Core_Collection('messages');
        $messageCollection->where('message_owner=:user');
        $messageCollection->where('message_read_at<=0');

        $count = $messageCollection->getSize(array('user' => $user->getId()));
        $newMessages = $layout->createBlock('core/deprecated', 'overview.messages');
        $newMessages->setTemplate('empire/overview/messages.phtml');
        $newMessages['count'] = (int) $count;

        /**
         * Page display
         * Refactoring needed
         * {{{
         */
        // -----------------------------------------------------------------------------------------------
        $parse = $lang;
        // -----------------------------------------------------------------------------------------------
        // News Frame ...
        // External Chat Frame ...
        // Banner ADS Google (meme si je suis contre cela)
        if ($gameConfig['OverviewNewsFrame'] == '1') {
            $parse['NewsFrame'] = "<tr><th>" . $lang['ov_news_title'] . "</th><th colspan=\"3\">" . stripslashes($gameConfig['OverviewNewsText']) . "</th></tr>";
        }
        if ($gameConfig['OverviewExternChat'] == '1') {
            $parse['ExternalTchatFrame'] = "<tr><th colspan=\"4\">" . stripslashes($gameConfig['OverviewExternChatCmd']) . "</th></tr>";
        }
        if ($gameConfig['OverviewClickBanner'] != '') {
            $parse['ClickBanner'] = stripslashes($gameConfig['OverviewClickBanner']);
        }
        if ($gameConfig['ForumBannerFrame'] == '1') {

            $BannerURL = Wootook::getUrl('scripts/createbanner.php', array('id' => $user['id']));

            $parse['bannerframe'] = "<th colspan=\"4\"><img src=\"{$BannerURL}\"><br>".$lang['InfoBanner']."<br><input name=\"bannerlink\" type=\"text\" id=\"bannerlink\" value=\"[img]".$BannerURL."[/img]\" size=\"62\"></th></tr>";
        }
        // --- Gestion de l'affichage d'une lune ---------------------------------------------------------
        if ($moon['id']) {
            if ($planet->isPlanet()) {
                $lune = doquery ("SELECT * FROM {{table}} WHERE `galaxy` = '" . $planet['galaxy'] . "' AND `system` = '" . $planet['system'] . "' AND `planet` = '" . $planet['planet'] . "' AND `planet_type` = '3'", 'planets', true);
                $parse['moon_img'] = "<a href=\"?cp=" . $lune['id'] . "&re=0\" title=\"" . $lune['name'] . "\"><img src=\"" . Wootook::getSkinUrl('base', 'default', "graphics/planeten/{$lune['image']}.jpg") . '" height="50" width="50"></a>';
                $parse['moon'] = $planet->getMoon()->getName();
            } else {
                $parse['moon_img'] = "";
                $parse['moon'] = "";
            }
        } else {
            $parse['moon_img'] = "";
            $parse['moon'] = "";
        }
        // Moon END
        $parse['planet_name'] = $planet['name'];
        $parse['planet_diameter'] = Math::render($planet['diameter']);
        $parse['planet_field_current'] = Math::render($planet->getUsedFields());
        $parse['planet_field_max'] = Math::render($planet->getBuildingFields());
        $parse['planet_temp_min'] = $planet['temp_min'];
        $parse['planet_temp_max'] = $planet['temp_max'];
        $parse['galaxy_galaxy'] = $planet['galaxy'];
        $parse['galaxy_planet'] = $planet['planet'];
        $parse['galaxy_system'] = $planet['system'];
        $StatRecord = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $user['id'] . "';", 'statpoints', true);

        $parse['user_points'] = Math::render($StatRecord['build_points']);
        $parse['user_fleet'] = Math::render($StatRecord['fleet_points']);
        $parse['player_points_tech'] = Math::render($StatRecord['tech_points']);
        $parse['total_points'] = Math::render($StatRecord['total_points']);;

        $parse['user_rank'] = $StatRecord['total_rank'];
        $ile = $StatRecord['total_old_rank'] - $StatRecord['total_rank'];
        if ($ile >= 1) {
            $parse['ile'] = "<font color=lime>+" . $ile . "</font>";
        } elseif ($ile < 0) {
            $parse['ile'] = "<font color=red>-" . $ile . "</font>";
        } elseif ($ile == 0) {
            $parse['ile'] = "<font color=lightblue>" . $ile . "</font>";
        }
        $parse['u_user_rank'] = $StatRecord['total_rank'];
        $parse['user_username'] = $user['username'];

        $parse['fleet_list'] = $fleetList->render();
        $parse['energy_used'] = $planet["energy_max"] - $planet["energy_used"];

        $parse['Have_new_message'] = $newMessages->render();
        $parse['time'] = "<div id=\"dateheure\"></div>";
        $parse['planet_image'] = $planet['image'];
        $parse['anothers_planets'] = $planetBlock->render();
        $parse['max_users'] = $gameConfig['users_amount'];

        $galaxyData = $planet->getGalaxyData();
        $parse['metal_debris'] = Math::render($galaxyData['metal']);
        $parse['crystal_debris'] = Math::render($galaxyData['crystal']);
        if (($galaxyData['metal'] != 0 || $galaxyData['crystal'] != 0) && Math::isPositive($planet->getElement(Legacies_Empire::ID_SHIP_RECYCLER))) {
            $parse['get_link'] = " (<a href=\"quickfleet.php?mode=8&g=" . $galaxyData['galaxy'] . "&s=" . $galaxyData['system'] . "&p=" . $galaxyData['planet'] . "&t=2\">" . $lang['type_mission'][Legacies_Empire::ID_MISSION_RECYCLE] . "</a>)";
        } else {
            $parse['get_link'] = '';
        }

        $query = doquery('SELECT username FROM {{table}} ORDER BY register_time DESC', 'users', true);
        $parse['last_user'] = $query['username'];
        $query = doquery("SELECT COUNT(DISTINCT(id)) FROM {{table}} WHERE onlinetime>" . (time()-900), 'users', true);
        $parse['online_users'] = $query[0];
        // $count = doquery(","users",true);
        $parse['users_amount'] = $gameConfig['users_amount'];

        // Rajout d'une barre pourcentage
        // Calcul du pourcentage de remplissage
        // Barre de remplissage
        $size = Math::floor(Math::div($planet->getUsedFields(), $planet->getBuildingFields()) * 100);
        // Couleur de la barre de remplissage
        $parse['case_pourcentage'] = $size . $lang['o/o'];
        if (Math::comp($size, 100) > 0) {
            $size = 100;
            $parse['case_barre_barcolor'] = '#C00000';
        } elseif (Math::comp($size, 80) > 0) {
            $parse['case_barre_barcolor'] = '#C0C000';
        } else {
            $parse['case_barre_barcolor'] = '#00C000';
        }
        $parse['case_barre'] = $size;

        // Mode Améliorations
        $parse['xpminier'] = $user['xpminier'];
        $parse['xpraid'] = $user['xpraid'];
        $parse['lvl_minier'] = $user['lvl_minier'];
        $parse['lvl_raid'] = $user['lvl_raid'];

        $LvlMinier = $user['lvl_minier'];
        $LvlRaid = $user['lvl_raid'];

        $parse['lvl_up_minier'] = $LvlMinier * 5000;
        $parse['lvl_up_raid'] = $LvlRaid * 10;
        // Nombre de raids, pertes, etc ...
        $parse['Raids'] = $lang['Raids'];
        $parse['NumberOfRaids'] = $lang['NumberOfRaids'];
        $parse['RaidsWin'] = $lang['RaidsWin'];
        $parse['RaidsLoose'] = $lang['RaidsLoose'];

        $parse['raids'] = $user['raids'];
        $parse['raidswin'] = $user['raidswin'];
        $parse['raidsloose'] = $user['raidsloose'];
        // Compteur de Membres en ligne
        $OnlineUsers = doquery("SELECT COUNT(*) FROM {{table}} WHERE onlinetime>='" . (time()-15 * 60) . "'", 'users', 'true');
        $parse['NumberMembersOnline'] = $OnlineUsers[0];

        $page = parsetemplate(gettemplate('overview_body'), $parse);

        display($page);
        /**
         * }}}
         */
    }
}