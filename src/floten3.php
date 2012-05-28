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

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/application/bootstrap.php';

includeLang('fleet');

$session = Wootook::getSession('fleet');

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$planet = $user->getCurrentPlanet();

$mission = isset($_POST['mission']) ? intval($_POST['mission']) : 0;
$galaxy = isset($session['galaxy']) ? $session['galaxy'] : 0;
$system = isset($session['system']) ? $session['system'] : 0;
$position = isset($session['position']) ? $session['position'] : 0;
$type = isset($session['type']) ? $session['type'] : 0;
$speed = isset($session['speed']) ? $session['speed'] : 10;

$protection      = Wootook::getGameConfig('game/noob-protection/active');
$protectionPointsCap  = Wootook::getGameConfig('game/noob-protection/points-cap');
$protectionMultiplier = Wootook::getGameConfig('game/noob-protection/multiplier');

$readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()
    ->getConnection('core_read');
$writeAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()
    ->getConnection('core_write');

if ($protectionPointsCap < 1) {
    $protectionPointsCap = 0x7FFFFFFF;
}

$fleetArray = $session['fleet'];

if (!is_array($fleetArray)) {
    message($lang['fl_fleet_err'], $lang['fl_error'], "fleet.php", 2);
}

// On verifie s'il y a assez de vaisseaux sur la planete !
foreach ($fleetArray as $shipId => $count) {
    if ($count > $planet->getElement($shipId)) {
        message($lang['fl_fleet_err'], $lang['fl_error'], "fleet.php", 2);
    }
}

$allowedPlanetTypes = array(
    Wootook_Empire_Model_Planet::TYPE_PLANET,
    Wootook_Empire_Model_Planet::TYPE_DEBRIS,
    Wootook_Empire_Model_Planet::TYPE_MOON
    );
if (!in_array($type, $allowedPlanetTypes)) {
    message($lang['fl_fleet_err_pl'], $lang['fl_error'], "fleet.php", 2);
}

if ($planet->getGalaxy() == $galaxy &&
    $planet->getSystem() == $system &&
    $planet->getPosition() == $position &&
    $planet->getType() == $type) {
    message($lang['fl_ownpl_err'], $lang['fl_error'], "fleet.php", 2);
}

$YourPlanet = false;
$UsedPlanet = false;
$coords = array(
    'galaxy'   => $galaxy,
    'system'   => $system,
    'position' => $position
    );
if ($mission == Legacies_Empire::ID_MISSION_RECYCLE) {
    $destination = Wootook_Empire_Model_Planet::factoryFromCoords($coords);
} else {
    $destination = Wootook_Empire_Model_Planet::factoryFromCoords($coords, $type);
}

// Test d'existence de l'enregistrement dans la galaxie !
if ($mission != Legacies_Empire::ID_MISSION_EXPEDITION) {
    if (!$destination->getId()) {
        if ($mission != Legacies_Empire::ID_MISSION_SETTLE_COLONY) {
            message($lang['fl_unknow_target'], $lang['fl_error'], "fleet." . PHPEXT, 2);
        } else if ($mission == Legacies_Empire::ID_MISSION_DESTROY) {
            message($lang['fl_used_target'], $lang['fl_error'], "fleet." . PHPEXT, 2);
        }
    } else if ($mission == Legacies_Empire::ID_MISSION_DESTROY && !$destination->isMoon()) {
        message($lang['fl_used_target'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
} else {
    $EnvoiMaxExpedition = $user->getElement(Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY);
    $Expedition = 0;
    if ($EnvoiMaxExpedition > 0) {
        $statement = $readAdapter->select()
            ->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'))
            ->from(array('fleet' => $readAdapter->getTable('fleets')))
            ->where('fleet_owner', new Wootook_Core_Database_Sql_Placeholder_Param('player', $user->getId()))
            ->where('fleet_mission', Legacies_Empire::ID_MISSION_EXPEDITION)
            ->prepare()
        ;

        $Expedition = $statement->fetchColumn();
    }

    if ($EnvoiMaxExpedition == 0 ) {
        message ($lang['fl_expe_notech'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    } else if ($Expedition >= $EnvoiMaxExpedition ) {
        message ($lang['fl_expe_max'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
}

if ($destination->getUserId() == $user['id']) {
    $YourPlanet = true;
    $UsedPlanet = true;
} else if ($destination->getId()) {
    $UsedPlanet = true;
}

$missionTypes = array();
// Determinons les type de missions possibles par rapport a la planete cible
if ($position == (Wootook::getGameConfig('engine/universe/positions') + 1)) {
    $missionTypes[Legacies_Empire::ID_MISSION_EXPEDITION] = $lang['type_mission'][Legacies_Empire::ID_MISSION_EXPEDITION];
} else {
    if ($type == Wootook_Empire_Model_Planet::TYPE_DEBRIS) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_RECYCLER]) && $fleetArray[Legacies_Empire::ID_SHIP_RECYCLER] > 0) {
            $missionTypes[Legacies_Empire::ID_MISSION_RECYCLE] = $lang['type_mission'][Legacies_Empire::ID_MISSION_RECYCLE];
        }
    } else if ($type == Wootook_Empire_Model_Planet::TYPE_PLANET) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP]) && $fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP] > 0 && !$UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_SETTLE_COLONY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_SETTLE_COLONY];
        }
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_ORE_MININER]) && $fleetArray[Legacies_Empire::ID_SHIP_ORE_MININER] > 0 && !$UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_ORE_MINING] = $lang['type_mission'][Legacies_Empire::ID_MISSION_ORE_MINING];
        }
    } else if ($type == Wootook_Empire_Model_Planet::TYPE_MOON) {
        if (((isset($fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR]) && $fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA])   && $fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA] > 0)) &&
            !$YourPlanet && $UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_DESTROY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_DESTROY];
        }
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_ORE_MININER]) && $fleetArray[Legacies_Empire::ID_SHIP_ORE_MININER] > 0 && !$YourPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_ORE_MINING] = $lang['type_mission'][Legacies_Empire::ID_MISSION_ORE_MINING];
        }
    }

    if (in_array($type, array(Wootook_Empire_Model_Planet::TYPE_MOON, Wootook_Empire_Model_Planet::TYPE_PLANET))) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE]) && $fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE] > 0 && !$YourPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_SPY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_SPY];
        }

        if ((isset($fleetArray[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT]) && $fleetArray[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_LARGE_TRANSPORT]) && $fleetArray[Legacies_Empire::ID_SHIP_LARGE_TRANSPORT] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_LIGHT_FIGHTER])   && $fleetArray[Legacies_Empire::ID_SHIP_LIGHT_FIGHTER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_HEAVY_FIGHTER])   && $fleetArray[Legacies_Empire::ID_SHIP_HEAVY_FIGHTER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_CRUISER])         && $fleetArray[Legacies_Empire::ID_SHIP_CRUISER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_BATTLESHIP])      && $fleetArray[Legacies_Empire::ID_SHIP_BATTLESHIP] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP])     && $fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_RECYCLER])        && $fleetArray[Legacies_Empire::ID_SHIP_RECYCLER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE])       && $fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE] > 0 && Wootook::getGameConfig('engine/combat/allow_spy_drone_attacks')) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_BOMBER])          && $fleetArray[Legacies_Empire::ID_SHIP_BOMBER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_DESTRUCTOR])      && $fleetArray[Legacies_Empire::ID_SHIP_DESTRUCTOR] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR])      && $fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_BATTLECRUISER])   && $fleetArray[Legacies_Empire::ID_SHIP_BATTLECRUISER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA])       && $fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA] > 0)) {

            if (!$YourPlanet) {
                $missionTypes[Legacies_Empire::ID_MISSION_ATTACK] = $lang['type_mission'][Legacies_Empire::ID_MISSION_ATTACK];
                $missionTypes[Legacies_Empire::ID_MISSION_GROUP_ATTACK] = $lang['type_mission'][Legacies_Empire::ID_MISSION_GROUP_ATTACK];
                $missionTypes[Legacies_Empire::ID_MISSION_STATION_ALLY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_STATION_ALLY];
            }
            $missionTypes[Legacies_Empire::ID_MISSION_TRANSPORT] = $lang['type_mission'][Legacies_Empire::ID_MISSION_TRANSPORT];
        }

        if ($YourPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_STATION] = $lang['type_mission'][Legacies_Empire::ID_MISSION_STATION];
        }
    }
}

if (isset($fleetArray[Legacies_Empire::ID_SHIP_SOLAR_SATELLITE])) {
    $missionTypes = array();
}

if (!isset($missionTypes[$mission])) {
    message($lang['fl_bad_mission'], $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if (!$destination->getId()) {
    $destinationUser = $user;
} else {
    $destinationUser = $destination->getUser();
}

$myGameLevel = $readAdapter->select()
    ->column('total_points')
    ->from(array('statpoints' => $readAdapter->getTable('statpoints')))
    ->where('stat_type', 1)
    ->where('stat_code', 1)
    ->where('id_owner', new Wootook_Core_Database_Sql_Placeholder_Param('player', $user->getId()))
    ->prepare()
    ->fetchColumn()
;
if ($destinationUser->getId()) {
    $hisGameLevel = $readAdapter->select()
        ->column('total_points')
        ->from(array('statpoints' => $readAdapter->getTable('statpoints')))
        ->where('stat_type', 1)
        ->where('stat_code', 1)
        ->where('id_owner', new Wootook_Core_Database_Sql_Placeholder_Param('player', $destinationUser->getId()))
        ->prepare()
        ->fetchColumn()
    ;
} else {
    $hisGameLevel = 0;
}

$vacationMode = $destinationUser->isVacation();

if ($protection) {
    $protectedMissions = array(
        Legacies_Empire::ID_MISSION_ATTACK,
        Legacies_Empire::ID_MISSION_GROUP_ATTACK,
        Legacies_Empire::ID_MISSION_STATION_ALLY,
        Legacies_Empire::ID_MISSION_SPY,
        Legacies_Empire::ID_MISSION_DESTROY
        );
    if ($destinationUser->getId() && in_array($mission, $protectedMissions)) {
        if ($myGameLevel > ($hisGameLevel * $protectionMultiplier) && $hisGameLevel < ($protectionPointsCap * 1000)) {
            message($lang['fl_noob_mess_n'], $lang['fl_noob_title'], "fleet." . PHPEXT, 2);
        } else if (($myGameLevel * $protectionMultiplier) < $hisGameLevel && $myGameLevel < ($protectionPointsCap * 1000)) {
            message($lang['fl_noob_mess_n'], $lang['fl_noob_title'], "fleet." . PHPEXT, 2);
        }
    }
}

if ($vacationMode && $mission != Legacies_Empire::ID_MISSION_RECYCLE) {
    message($lang['fl_vacation_pla'], $lang['fl_vacation_ttl'], "fleet." . PHPEXT, 2);
}

$currentFleets = $user->getFleets()->getSize();

if (($user->getElement(Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY) + 1) <= $currentFleets) {
    message("Pas de slot disponible", "Erreur", "fleet." . PHPEXT, 1);
}

$metal     = isset($_POST['resource1']) ? intval($_POST['resource1']) : 0;
$cristal   = isset($_POST['resource2']) ? intval($_POST['resource2']) : 0;
$deuterium = isset($_POST['resource3']) ? intval($_POST['resource3']) : 0;

if ($metal + $cristal + $deuterium < 1 && $mission == Legacies_Empire::ID_MISSION_TRANSPORT) {
    message("<font color=\"lime\"><b>".$lang['fl_noenoughtgoods']."</b></font>", $lang['type_mission'][3], "fleet." . PHPEXT, 1);
}
if ($mission != Legacies_Empire::ID_MISSION_EXPEDITION) {
    $userMissions = array(
        Legacies_Empire::ID_MISSION_ATTACK,
        Legacies_Empire::ID_MISSION_GROUP_ATTACK,
        Legacies_Empire::ID_MISSION_TRANSPORT,
        Legacies_Empire::ID_MISSION_STATION,
        Legacies_Empire::ID_MISSION_STATION_ALLY,
        Legacies_Empire::ID_MISSION_SPY
        );
    if (!$destination->getUserId() && in_array($mission, $userMissions)) {
        message ($lang['fl_bad_planet01'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getId() && $mission == Legacies_Empire::ID_MISSION_SETTLE_COLONY) {
        message ($lang['fl_bad_planet02'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getId() != $planet->getId() && $mission == Legacies_Empire::ID_MISSION_STATION) {
        message ($lang['fl_dont_stay_here'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getElement(Legacies_Empire::ID_BUILDING_ALLIANCE_DEPOT) < 1 && $destinationUser->getId() != $user->getId() && $mission == Legacies_Empire::ID_MISSION_STATION_ALLY) {
        message ($lang['fl_no_allydeposit'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() == $user->getId() && ($mission == Legacies_Empire::ID_MISSION_ATTACK)) {
        message ($lang['fl_no_self_attack'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() == $user->getId() && ($mission == Legacies_Empire::ID_MISSION_SPY)) {
        message ($lang['fl_no_self_spy'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() != $user->getId() && $mission == Legacies_Empire::ID_MISSION_STATION) {
        message ($lang['fl_only_stay_at_home'], $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
}

$possibleSpeeds = array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1);

$AllFleetSpeed  = GetFleetMaxSpeed($fleetArray, 0, $user);
$SpeedFactor    = Wootook::getGameConfig('game/speed/general');
$MaxFleetSpeed  = min($AllFleetSpeed);

if (!in_array($speed, $possibleSpeeds)) {
    message ($lang['fl_cheat_speed'], $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$error = 0;
$errorlist = "";
if ($galaxy > Wootook::getGameConfig('engine/universe/galaxies') || $galaxy < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_galaxy'];
}
if ($system > Wootook::getGameConfig('engine/universe/systems') || $system < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_system'];
}
if ($position > (Wootook::getGameConfig('engine/universe/positions') + 1) || $position < 1) {
    $error++;
    $errorlist .= $lang['fl_limit_planet'];
}

if ($error > 0) {
    message($errorlist, $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if (!isset($fleetArray)) {
    message($lang['fl_no_fleetarray'], $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$distance    = GetTargetDistance($planet->getGalaxy(), $galaxy, $planet->getSystem(), $system, $planet->getPosition(), $position);
$duration    = GetMissionDuration($speed, $MaxFleetSpeed, $distance, $SpeedFactor);
$consumption = GetFleetConsumption($fleetArray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $user);

$startTime = time() + $duration;
if ($mission == Legacies_Empire::ID_MISSION_EXPEDITION && isset($_POST['expeditiontime']) && is_int($_POST['expeditiontime'])
        && ($expeditionTime = intval($_POST['expeditiontime'])) >= 1 && $expeditionTime <= 10) {
    $StayDuration    = $expeditionTime * 3600;
    $StayTime        = $startTime + $expeditionTime * 3600;
} elseif ($mission == Legacies_Empire::ID_MISSION_STATION_ALLY || $mission == Legacies_Empire::ID_MISSION_ORE_MINING && is_int($_POST['holdingtime'])
        && ($holdingTime = intval($_POST['holdingtime'])) >= 1 && $holdingTime <= 10) {
    $StayDuration    = $holdingTime * 3600;
    $StayTime        = $startTime + $holdingTime * 3600;
} else {
    $StayDuration    = 0;
    $StayTime        = 0;
}

/* END Refactoring */
$endTime   = $StayDuration + (2 * $duration) + time();
$FleetStorage   = 0;
$FleetShipCount = array_sum($fleetArray);
$serializedFleetArray = '';

foreach ($fleetArray as $shipId => $count) {
    $FleetStorage    += $pricelist[$shipId]["capacity"] * $count;
    $serializedFleetArray .= $shipId .",". $count .";";
    $planet->setElement($shipId, $planet->getElement($shipId) - $count);
}

$StockMetal      = $planet['metal'];
$StockCrystal    = $planet['cristal'];
$StockDeuterium  = $planet['deuterium'] - $consumption;

$FleetStorage -= $consumption;
$StorageNeeded = 0;
if (!isset($_POST['resource1']) || intval($_POST['resource1']) < 1) {
    $TransMetal = 0;
} else {
    $TransMetal = intval($_POST['resource1']);
    if (Math::comp($StockMetal, $TransMetal) < 0) {
        $TransMetal = $StockMetal;
    }
    $StorageNeeded = Math::add($TransMetal, $StorageNeeded);
}
if (!isset($_POST['resource2']) || intval($_POST['resource2']) < 1) {
    $TransCrystal = 0;
} else {
    $TransCrystal = intval($_POST['resource2']);
    if (Math::comp($StockCrystal, $TransCrystal) < 0) {
        $TransCrystal = $StockCrystal;
    }
    $StorageNeeded = Math::add($TransCrystal, $StorageNeeded);
}
if (!isset($_POST['resource3']) || intval($_POST['resource3']) < 1) {
    $TransDeuterium  = 0;
} else {
    $TransDeuterium = intval($_POST['resource3']);
    if (Math::comp($StockDeuterium, $TransDeuterium) < 0) {
        $TransDeuterium = $StockDeuterium;
    }
    $StorageNeeded = Math::add($TransDeuterium, $StorageNeeded);
}

if ($StorageNeeded > $FleetStorage) {
    message($lang['fl_nostoragespa'] . pretty_number($StorageNeeded - $FleetStorage), $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if ($destinationUser['authlevel'] > $user['authlevel']) {
    $specialMissions = array(
        Legacies_Empire::ID_MISSION_ATTACK,
        Legacies_Empire::ID_MISSION_GROUP_ATTACK,
        Legacies_Empire::ID_MISSION_SPY,
        Legacies_Empire::ID_MISSION_DESTROY
        );

    if (in_array($mission, $specialMissions)) {
        message($lang['fl_adm_attak'], $lang['fl_warning'], "fleet." . PHPEXT, 2);
    }
}

/* BEGIN Refactoring */
try {
    $writeAdapter->beginTransaction();

    $mapper = $writeAdapter->getDataMapper();
    $dateMapper = $mapper->load('date-time');

    $writeAdapter->insert()
        ->into($writeAdapter->getTable('fleets'))
        ->set('fleet_owner', $user->getId())
        ->set('fleet_mission', $mission)
        ->set('fleet_amount', $FleetShipCount)
        ->set('fleet_array', $serializedFleetArray)
        ->set('fleet_start_time', $dateMapper->encode($startTime))
        ->set('fleet_start_galaxy', $planet->getGalaxy())
        ->set('fleet_start_system', $planet->getSystem())
        ->set('fleet_start_planet', $planet->getPosition())
        ->set('fleet_start_type', $planet->getType())
        ->set('fleet_end_time', $dateMapper->encode($endTime))
        ->set('fleet_end_stay', $dateMapper->encode($StayTime))
        ->set('fleet_end_galaxy', $galaxy)
        ->set('fleet_end_system', $system)
        ->set('fleet_end_planet', $position)
        ->set('fleet_end_type', $type)
        ->set('fleet_resource_metal', intval($TransMetal))
        ->set('fleet_resource_crystal', intval($TransCrystal)) // FIXME: refactor field name
        ->set('fleet_resource_deuterium', intval($TransDeuterium))
        ->set('fleet_target_owner', $destination['id_owner'])
        ->set('start_time',  $dateMapper->encode(time()))
        ->execute()
    ;

    $planet["metal"]     = $planet["metal"] - $TransMetal;
    $planet["cristal"]   = $planet["cristal"] - $TransCrystal;
    $planet["deuterium"] = $planet["deuterium"] - $TransDeuterium - $consumption;
    $planet->save();

    $writeAdapter->commit();
} catch (Exception $e) {
    Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
    $writeAdapter->rollback();
    message($errorlist, $lang['fl_error'], "fleet." . PHPEXT, 2);
}
/* END Refactoring */

$page  = "<br><div><center>";
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"519\">";
$page .= "<tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\"><span class=\"success\">". $lang['fl_fleet_send'] ."</span></td>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_mission'] ."</th>";
$page .= "<th>". $missionTypes[$mission] ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_dist'] ."</th>";
$page .= "<th>". pretty_number($distance) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_speed'] ."</th>";
$page .= "<th>". pretty_number($_POST['speedallsmin']) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_deute_need'] ."</th>";
$page .= "<th>". pretty_number($consumption) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_from'] ."</th>";
$page .= "<th>". $planet->getCoords() ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_dest'] ."</th>";
$page .= "<th>". $galaxy . ':' . $system . ':' . $position ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_time_go'] ."</th>";
$page .= "<th>". date("M D d H:i:s", $startTime) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_time_back'] ."</th>";
$page .= "<th>". date("M D d H:i:s", $endTime) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\">". $lang['fl_title'] ."</td>";

$helper = Wootook_Empire_Helper_Config_Labels::getSingleton();
foreach ($fleetArray as $shipId => $shipCount) {
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $helper[$shipId]['name'] ."</th>";
    $page .= "<th>". pretty_number($shipCount) ."</th>";
}
$page .= "</tr></table></div></center>";

display($page, $lang['fl_title']);
