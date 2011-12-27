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

includeLang('fleet');

$session = Wootook::getSession('fleet');

$user = Wootook_Empire_Model_User::getSingleton();
$planet = $user->getCurrentPlanet();

$galaxy = isset($_POST['galaxy']) ? intval($_POST['galaxy']) : 0;
$system = isset($_POST['system']) ? intval($_POST['system']) : 0;
$position = isset($_POST['planet']) ? intval($_POST['planet']) : 0;
$type = isset($_POST['planettype']) ? intval($_POST['planettype']) : 0;
$mission = isset($_POST['mission']) ? intval($_POST['mission']) : 0;
$galaxy = isset($session['galaxy']) ? $session['galaxy'] : 0;
$system = isset($session['system']) ? $session['system'] : 0;
$position = isset($session['position']) ? $session['position'] : 0;
$type = isset($session['type']) ? $session['type'] : 0;
$speed = isset($session['speed']) ? $session['speed'] : 10;

$protection      = Wootook::getGameConfig('game/noob-protection/active');
$protectiontime  = Wootook::getGameConfig('game/noob-protection/points-cap');
$protectionmulti = Wootook::getGameConfig('game/noob-protection/multiplier');

if ($protectiontime < 1) {
    $protectiontime = 9999999999999999;
}

$fleetArray = $session['fleet'];

if (!is_array($fleetArray)) {
    message ("<font color=\"red\"><b>". $lang['fl_fleet_err'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

// On verifie s'il y a assez de vaisseaux sur la planete !
foreach ($fleetArray as $shipId => $count) {
    if ($Count > $planet->getElement($shipId)) {
        message ("<font color=\"red\"><b>". $lang['fl_fleet_err'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
}

$allowedPlanetTypes = array(
    Wootook_Empire_Model_Planet::TYPE_PLANET,
    Wootook_Empire_Model_Planet::TYPE_DEBRIS,
    Wootook_Empire_Model_Planet::TYPE_MOON
    );
if (!in_array($type, $allowedPlanetTypes)) {
    message ("<font color=\"red\"><b>". $lang['fl_fleet_err_pl'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if ($planet->getGalaxy() == $galaxy &&
    $planet->getSystem() == $system &&
    $planet->getPosition() == $position &&
    $planet->getType() == $type) {
    message ("<font color=\"red\"><b>". $lang['fl_ownpl_err'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
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
            message ("<font color=\"red\"><b>". $lang['fl_unknow_target'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
        } else if ($mission == Legacies_Empire::ID_MISSION_DESTROY) {
            message ("<font color=\"red\"><b>". $lang['fl_used_target'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
        }
    } else if ($mission == Legacies_Empire::ID_MISSION_DESTROY && !$destination->isMoon()) {
        message ("<font color=\"red\"><b>". $lang['fl_used_target'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
} else {
    $EnvoiMaxExpedition = $user->getElement(Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY);
    $Expedition = 0;
    if ($EnvoiMaxExpedition > 0) {
        $maxexpde = doquery("SELECT 1 FROM {{table}} WHERE `fleet_owner` = '".$user['id']."' AND `fleet_mission` = '15';", 'fleets');
        $Expedition = $maxexpde->rowCount();
        $maxexpde->closeCursor();
    }

    if ($EnvoiMaxExpedition == 0 ) {
        message ("<font color=\"red\"><b>". $lang['fl_expe_notech'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    } else if ($Expedition >= $EnvoiMaxExpedition ) {
        message ("<font color=\"red\"><b>". $lang['fl_expe_max'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
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
            $missionTypes[Legacies_Empire::ID_MISSION_SETTLE_COLONY] = $lang['type_mission'][7];
        }
    } else if ($type == Wootook_Empire_Model_Planet::TYPE_MOON) {
        if (((isset($fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR]) && $fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA])   && $fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA] > 0)) &&
            !$YourPlanet && $UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_DESTROY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_DESTROY];
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
    message ("<font color=\"red\"><b>". $lang['fl_bad_mission'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if (!$destination->getId()) {
    $destinationUser = $user;
} else {
    $destinationUser = $destination->getUser();
}

$userPoints = doquery("SELECT total_points FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner`='{$user->getId()}'", 'statpoints', true);
$myGameLevel  = $userPoints['total_points'];
if ($destinationUser->getId()) {
    $destinationPoints = doquery("SELECT total_points FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '{$destinationUser->getId()}';", 'statpoints', true);
    $hisGameLevel = $destinationPoints['total_points'];
} else {
    $hisGameLevel = 0;
}

$vacationMode = $destinationUser['urlaubs_modus'];

if ($protection) {
    $protectedMissions = array(
        Legacies_Empire::ID_MISSION_ATTACK,
        Legacies_Empire::ID_MISSION_GROUP_ATTACK,
        Legacies_Empire::ID_MISSION_STATION_ALLY,
        Legacies_Empire::ID_MISSION_SPY,
        Legacies_Empire::ID_MISSION_DESTROY
        );

    if ($myGameLevel > ($hisGameLevel * $protectionmulti) && $destinationUser->getId() &&
        in_array($mission, $protectedMissions) && $hisGameLevel < ($protectiontime * 1000)) {
        message("<font color=\"lime\"><b>".$lang['fl_noob_mess_n']."</b></font>", $lang['fl_noob_title'], "fleet." . PHPEXT, 2);
    }

    if (($myGameLevel * $protectionmulti) < $hisGameLevel && $destinationUser->getId() &&
        in_array($mission, $protectedMissions) && $myGameLevel < ($protectiontime * 1000)) {
        message("<font color=\"lime\"><b>".$lang['fl_noob_mess_n']."</b></font>", $lang['fl_noob_title'], "fleet." . PHPEXT, 2);
    }
}

if ($vacationMode && $mission != Legacies_Empire::ID_MISSION_RECYCLE) {
    message("<font color=\"lime\"><b>".$lang['fl_vacation_pla']."</b></font>", $lang['fl_vacation_ttl'], "fleet." . PHPEXT, 2);
}

$currentFleets = $user->getFleets()->count();

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
        message ("<font color=\"red\"><b>". $lang['fl_bad_planet01'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getId() && $mission == Legacies_Empire::ID_MISSION_SETTLE_COLONY) {
        message ("<font color=\"red\"><b>". $lang['fl_bad_planet02'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getId() != $planet->getId() && $mission == Legacies_Empire::ID_MISSION_STATION) {
        message ("<font color=\"red\"><b>". $lang['fl_dont_stay_here'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destination->getElement(Legacies_Empire::ID_BUILDING_ALLIANCE_DEPOT) < 1 && $destinationUser->getId() != $user->getId() && $mission == Legacies_Empire::ID_MISSION_STATION_ALLY) {
        message ("<font color=\"red\"><b>". $lang['fl_no_allydeposit'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() == $user->getId() && ($mission == Legacies_Empire::ID_MISSION_ATTACK)) {
        message ("<font color=\"red\"><b>". $lang['fl_no_self_attack'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() == $user->getId() && ($mission == Legacies_Empire::ID_MISSION_SPY)) {
        message ("<font color=\"red\"><b>". $lang['fl_no_self_spy'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
    if ($destinationUser->getId() != $user->getId() && $mission == Legacies_Empire::ID_MISSION_STATION) {
        message ("<font color=\"red\"><b>". $lang['fl_only_stay_at_home'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }
}

$possibleSpeeds = array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1);

$AllFleetSpeed  = GetFleetMaxSpeed($fleetArray, 0, $user);
$SpeedFactor    = GetGameSpeedFactor();
$MaxFleetSpeed  = min($AllFleetSpeed);

if (!in_array($speed, $possibleSpeeds)) {
    message ("<font color=\"red\"><b>". $lang['fl_cheat_speed'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
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
    message ("<font color=\"red\"><ul>" . $errorlist . "</ul></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

if (!isset($fleetArray)) {
    message ("<font color=\"red\"><b>". $lang['fl_no_fleetarray'] ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
}

$distance    = GetTargetDistance($planet->getGalaxy(), $galaxy, $planet->getSystem(), $system, $planet->getPosition(), $position);
$duration    = GetMissionDuration($speed, $MaxFleetSpeed, $distance, $SpeedFactor);
$consumption = GetFleetConsumption($fleetArray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $user);

/***************/
    $fleet['start_time'] = $duration + time();
    if ($mission == Legacies_Empire::ID_MISSION_EXPEDITION) {
        $StayDuration    = $_POST['expeditiontime'] * 3600;
        $StayTime        = $fleet['start_time'] + $_POST['expeditiontime'] * 3600;
    } elseif ($_POST['mission'] == 5) {
        $StayDuration    = $_POST['holdingtime'] * 3600;
        $StayTime        = $fleet['start_time'] + $_POST['holdingtime'] * 3600;
    } else {
        $StayDuration    = 0;
        $StayTime        = 0;
    }
    $fleet['end_time']   = $StayDuration + (2 * $duration) + time();
    $FleetStorage        = 0;
    $FleetShipCount      = 0;
    $fleet_array         = "";
    $FleetSubQRY         = "";

    foreach ($fleetArray as $Ship => $Count) {
        $FleetStorage    += $pricelist[$Ship]["capacity"] * $Count;
        $FleetShipCount  += $Count;
        $fleet_array     .= $Ship .",". $Count .";";
        $FleetSubQRY     .= "`".$resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . " , ";
    }

    $FleetStorage        -= $consumption;
    $StorageNeeded        = 0;
    if ($_POST['resource1'] < 1) {
        $TransMetal      = 0;
    } else {
        $TransMetal      = $_POST['resource1'];
        $StorageNeeded  += $TransMetal;
    }
    if ($_POST['resource2'] < 1) {
        $TransCrystal    = 0;
    } else {
        $TransCrystal    = $_POST['resource2'];
        $StorageNeeded  += $TransCrystal;
    }
    if ($_POST['resource3'] < 1) {
        $TransDeuterium  = 0;
    } else {
        $TransDeuterium  = $_POST['resource3'];
        $StorageNeeded  += $TransDeuterium;
    }

    $StockMetal      = $planet['metal'];
    $StockCrystal    = $planet['cristal'];
    $StockDeuterium  = $planet['deuterium'];
    $StockDeuterium -= $consumption;

    $StockOk         = false;
    if ($StockMetal >= $TransMetal) {
        if ($StockCrystal >= $TransCrystal) {
            if ($StockDeuterium >= $TransDeuterium) {
                $StockOk         = true;
            }
        }
    }
    if ( !$StockOk ) {
        message ("<font color=\"red\"><b>". $lang['fl_noressources'] . pretty_number($consumption) ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }

    if ( $StorageNeeded > $FleetStorage) {
        message ("<font color=\"red\"><b>". $lang['fl_nostoragespa'] . pretty_number($StorageNeeded - $FleetStorage) ."</b></font>", $lang['fl_error'], "fleet." . PHPEXT, 2);
    }

    if ($destination['id_level'] > $user['authlevel']) {
        $Allowed = true;
        switch ($_POST['mission']){
            case 1:
            case 2:
            case 6:
            case 9:
                $Allowed = false;
                break;
            case 3:
            case 4:
            case 5:
            case 7:
            case 8:
            case 15:
                break;
            default:
        }
        if ($Allowed == false) {
            message ("<font color=\"red\"><b>". $lang['fl_adm_attak'] ."</b></font>", $lang['fl_warning'], "fleet." . PHPEXT, 2);
        }
    }

    // ecriture de l'enregistrement de flotte (a partir de l�, y a quelque chose qui vole et c'est toujours sur la planete d'origine)
    $QryInsertFleet  = "INSERT INTO {{table}} SET ";
    $QryInsertFleet .= "`fleet_owner` = '". $user['id'] ."', ";
    $QryInsertFleet .= "`fleet_mission` = '". $_POST['mission'] ."', ";
    $QryInsertFleet .= "`fleet_amount` = '". $FleetShipCount ."', ";
    $QryInsertFleet .= "`fleet_array` = '". $fleet_array ."', ";
    $QryInsertFleet .= "`fleet_start_time` = '". $fleet['start_time'] ."', ";
    $QryInsertFleet .= "`fleet_start_galaxy` = '". intval($_POST['thisgalaxy']) ."', ";
    $QryInsertFleet .= "`fleet_start_system` = '". intval($_POST['thissystem']) ."', ";
    $QryInsertFleet .= "`fleet_start_planet` = '". intval($_POST['thisplanet']) ."', ";
    $QryInsertFleet .= "`fleet_start_type` = '". intval($_POST['thisplanettype']) ."', ";
    $QryInsertFleet .= "`fleet_end_time` = '". $fleet['end_time'] ."', ";
    $QryInsertFleet .= "`fleet_end_stay` = '". $StayTime ."', ";
    $QryInsertFleet .= "`fleet_end_galaxy` = '". intval($_POST['galaxy']) ."', ";
    $QryInsertFleet .= "`fleet_end_system` = '". intval($_POST['system']) ."', ";
    $QryInsertFleet .= "`fleet_end_planet` = '". intval($_POST['planet']) ."', ";
    $QryInsertFleet .= "`fleet_end_type` = '". intval($_POST['planettype']) ."', ";
    $QryInsertFleet .= "`fleet_resource_metal` = '". intval($TransMetal) ."', ";
    $QryInsertFleet .= "`fleet_resource_cristal` = '". intval($TransCrystal) ."', ";
    $QryInsertFleet .= "`fleet_resource_deuterium` = '". intval($TransDeuterium) ."', ";
    $QryInsertFleet .= "`fleet_target_owner` = '". $destination['id_owner'] ."', ";
    $QryInsertFleet .= "`start_time` = '". time() ."';";
    doquery( $QryInsertFleet, 'fleets');


    $planet["metal"]     = $planet["metal"] - $TransMetal;
    $planet["cristal"]   = $planet["cristal"] - $TransCrystal;
    $planet["deuterium"] = $planet["deuterium"] - $TransDeuterium;
    $planet["deuterium"] = $planet["deuterium"] - $consumption;

    $QryUpdatePlanet  = "UPDATE {{table}} SET ";
    $QryUpdatePlanet .= $FleetSubQRY;
    $QryUpdatePlanet .= "`metal` = '". $planet["metal"] ."', ";
    $QryUpdatePlanet .= "`cristal` = '". $planet["cristal"] ."', ";
    $QryUpdatePlanet .= "`deuterium` = '". $planet["deuterium"] ."' ";
    $QryUpdatePlanet .= "WHERE ";
    $QryUpdatePlanet .= "`id` = '". $planet['id'] ."'";

    // Mise a jours de l'enregistrement de la planete de depart (a partir de là, y a quelque chose qui vole et ce n'est plus sur la planete de depart)
    doquery("LOCK TABLE {{table}} WRITE", 'planets');
    doquery ($QryUpdatePlanet, "planets");
    doquery("UNLOCK TABLES", '');
//    doquery("FLUSH TABLES", '');

    // Un peu de blabla pour l'utilisateur, affichage d'un joli tableau de la flotte expedi�e
    $page  = "<br><div><center>";
    $page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"519\">";
    $page .= "<tr height=\"20\">";
    $page .= "<td class=\"c\" colspan=\"2\"><span class=\"success\">". $lang['fl_fleet_send'] ."</span></td>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $lang['fl_mission'] ."</th>";
    $page .= "<th>". $missionTypes[$_POST['mission']] ."</th>";
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
    $page .= "<th>". $_POST['thisgalaxy'] .":". $_POST['thissystem']. ":". $_POST['thisplanet'] ."</th>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $lang['fl_dest'] ."</th>";
    $page .= "<th>". $_POST['galaxy'] .":". $_POST['system'] .":". $_POST['planet'] ."</th>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $lang['fl_time_go'] ."</th>";
    $page .= "<th>". date("M D d H:i:s", $fleet['start_time']) ."</th>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $lang['fl_time_back'] ."</th>";
    $page .= "<th>". date("M D d H:i:s", $fleet['end_time']) ."</th>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<td class=\"c\" colspan=\"2\">". $lang['fl_title'] ."</td>";

    foreach ($fleetArray as $Ship => $Count) {
        $page .= "</tr><tr height=\"20\">";
        $page .= "<th>". $lang['tech'][$Ship] ."</th>";
        $page .= "<th>". pretty_number($Count) ."</th>";
    }
    $page .= "</tr></table></div></center>";

    // Provisoire
    sleep (1);

    $planetrow = doquery ("SELECT * FROM {{table}} WHERE `id` = '". $planet['id'] ."';", 'planets', true);

    display($page, $lang['fl_title']);
