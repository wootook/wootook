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

$galaxy = isset($_POST['galaxy']) ? intval($_POST['galaxy']) : 0;
$system = isset($_POST['system']) ? intval($_POST['system']) : 0;
$position = isset($_POST['planet']) ? intval($_POST['planet']) : 0;
$planetType = isset($_POST['planettype']) ? intval($_POST['planettype']) : 0;
$speed = isset($_POST['speed']) ? intval($_POST['speed']) : 10;

$coords = array(
    'galaxy'   => $galaxy,
    'system'   => $system,
    'position' => $position
    );
$destination = Wootook_Empire_Model_Planet::factoryFromCoords($coords, $planetType);
$session['planet_destination'] = $destination->getId();

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$planet = $user->getCurrentPlanet();

$YourPlanet = false;
if ($destination->getUserId() == $user->getId()) {
    $YourPlanet = true;
}
$UsedPlanet = false;
if ($destination->getId()) {
    $UsedPlanet = true;
}

$fleetArray = $session['fleet'];
if (empty($fleetArray)) {
    Wootook::getResponse()
        ->setRedirect(Wootook::getStaticUrl('fleet.php'))
        ->sendHeaders();
    exit(0);
}

$missionTypes = array();
// Determinons les type de missions possibles par rapport a la planete cible

if ($position == (Wootook::getGameConfig('engine/universe/positions') + 1)) {
    $missionTypes[Legacies_Empire::ID_MISSION_EXPEDITION] = $lang['type_mission'][Legacies_Empire::ID_MISSION_EXPEDITION];
} else {
    if ($planetType == Wootook_Empire_Model_Planet::TYPE_DEBRIS) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_RECYCLER]) && $fleetArray[Legacies_Empire::ID_SHIP_RECYCLER] > 0) {
            $missionTypes[Legacies_Empire::ID_MISSION_RECYCLE] = $lang['type_mission'][Legacies_Empire::ID_MISSION_RECYCLE];
        }
    } else if ($planetType == Wootook_Empire_Model_Planet::TYPE_PLANET) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP]) && $fleetArray[Legacies_Empire::ID_SHIP_COLONY_SHIP] > 0 && !$UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_SETTLE_COLONY] = $lang['type_mission'][7];
        }
    } else if ($planetType == Wootook_Empire_Model_Planet::TYPE_MOON) {
        if (((isset($fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR]) && $fleetArray[Legacies_Empire::ID_SHIP_DEATH_STAR] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA])   && $fleetArray[Legacies_Empire::ID_SHIP_SUPERNOVA] > 0)) &&
            !$YourPlanet && $UsedPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_DESTROY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_DESTROY];
        }
    }

    if (in_array($planetType, array(Wootook_Empire_Model_Planet::TYPE_MOON, Wootook_Empire_Model_Planet::TYPE_PLANET))) {
        if (isset($fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE]) && $fleetArray[Legacies_Empire::ID_SHIP_SPY_DRONE] > 0 && !$YourPlanet) {
            $missionTypes[Legacies_Empire::ID_MISSION_SPY] = $lang['type_mission'][Legacies_Empire::ID_MISSION_SPY];
        }

        if ((isset($fleetArray[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT]) && $fleetArray[Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_LARGE_TRANSPORT]) && $fleetArray[Legacies_Empire::ID_SHIP_LARGE_TRANSPORT] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_LIGHT_FIGHTER])   && $fleetArray[Legacies_Empire::ID_SHIP_LIGHT_FIGHTER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_HEAVY_FIGHTER])   && $fleetArray[Legacies_Empire::ID_SHIP_HEAVY_FIGHTER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_CRUISER])         && $fleetArray[Legacies_Empire::ID_SHIP_CRUISER] > 0) ||
            (isset($fleetArray[Legacies_Empire::ID_SHIP_BATTLESHIP])      && $fleetArray[Legacies_Empire::ID_SHIP_BATTLESHIP] > 0) ||
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

$mission = isset($_POST['target_mission']) ? $_POST['target_mission'] : 0;

$SpeedFactor   = Wootook::getGameConfig('game/speed/general');
$AllFleetSpeed = GetFleetMaxSpeed($fleetArray, 0, $user);
$MaxFleetSpeed = min($AllFleetSpeed);

$distance    = GetTargetDistance($planet->getGalaxy(), $galaxy, $planet->getSystem(), $system, $planet->getPosition(), $position);
$duration    = GetMissionDuration($speed, $MaxFleetSpeed, $distance, $SpeedFactor);
$consumption = GetFleetConsumption($fleetArray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $user );

$session['distance']    = $distance;
$session['duration']    = $duration;
$session['consumption'] = $consumption;
$session['speed']       = $speed;
$session['galaxy']      = $galaxy;
$session['system']      = $system;
$session['position']    = $position;
$session['type']        = $planetType;

$MissionSelector  = "";
if (count($missionTypes) > 0) {
    if ($position == (Wootook::getGameConfig('engine/universe/positions') + 1)) {
        $MissionSelector .= "<tr height=\"20\">";
        $MissionSelector .= "<th>";
        $MissionSelector .= "<input type=\"radio\" name=\"mission\" value=\"15\" checked=\"checked\">". $lang['type_mission'][Legacies_Empire::ID_MISSION_EXPEDITION] ."<br /><br />";
        $MissionSelector .= "<font color=\"red\">". $lang['fl_expe_warning'] ."</font>";
        $MissionSelector .= "</th>";
        $MissionSelector .= "</tr>";
    } else {
        $i = 0;
        foreach ($missionTypes as $a => $b) {
            $MissionSelector .= "<tr height=\"20\">";
            $MissionSelector .= "<th>";
            $MissionSelector .= "<input id=\"inpuT_".$i."\" type=\"radio\" name=\"mission\" value=\"".$a."\"". ($mission == $a ? " checked=\"checked\"":"") .">";
            $MissionSelector .= "<label for=\"inpuT_".$i."\">".$b."</label><br>";
            $MissionSelector .= "</th>";
            $MissionSelector .= "</tr>";
            $i++;
        }
    }
} else {
    $MissionSelector .= "<tr height=\"20\">";
    $MissionSelector .= "<th>";
    $MissionSelector .= "<font color=\"red\">". $lang['fl_bad_mission'] ."</font>";
    $MissionSelector .= "</th>";
    $MissionSelector .= "</tr>";
}

if ($planet->isPlanet()) {
    $TableTitle = "{$planet->getCoords()} - {$lang['fl_planet']}";
} elseif ($planet->isMoon()) {
    $TableTitle = "{$planet->getCoords()} - {$lang['fl_moon']}";
}

$readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');
$maxExpedition = $user->getElement(Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY);
$ExpeditionEnCours = 0;
$EnvoiMaxExpedition = 0;
if ($maxExpedition >= 1) {
    $ExpeditionEnCours = $readAdapter->select()
        ->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'))
        ->from(array('fleet' => $readAdapter->getTable('fleets')))
        ->where('fleet_owner', $user->getId())
        ->where('fleet_mission', Legacies_Empire::ID_MISSION_EXPEDITION)
        ->prepare()
        ->fetchColumn()
    ;

    $EnvoiMaxExpedition = 1 + floor(sqrt($maxExpedition));
}

$MaxFlyingFleets = $readAdapter->select()
    ->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'))
    ->from(array('fleet' => $readAdapter->getTable('fleets')))
    ->where('fleet_owner', $user->getId())
    ->prepare()
    ->fetchColumn()
;

$page  = "<script type=\"text/javascript\" src=\"scripts/flotten.js\">\n</script>";
$page .= "<script type=\"text/javascript\">\n";
$page .= "function getStorageFaktor() {\n";
$page .= "    return 1;\n";
$page .= "}\n";
$page .= "</script>\n";
$page .= "<br><center>";
$page .= "<form action=\"floten3.php\" method=\"post\">\n";
$page .= "<input type=\"hidden\" name=\"consumption\"    value=\"". $consumption ."\" />\n";
$page .= "<input type=\"hidden\" name=\"dist\"           value=\"". $distance ."\" />\n";
$page .= "<input type=\"hidden\" name=\"speedfactor\"    value=\"". $SpeedFactor ."\" />\n";
$page .= "<input type=\"hidden\" name=\"thisgalaxy\"     value=\"". $planet->getGalaxy() ."\" />\n";
$page .= "<input type=\"hidden\" name=\"thissystem\"     value=\"". $planet->getSystem() ."\" />\n";
$page .= "<input type=\"hidden\" name=\"thisplanet\"     value=\"". $planet->getPosition() ."\" />\n";
$page .= "<input type=\"hidden\" name=\"galaxy\"         value=\"". $galaxy ."\" />\n";
$page .= "<input type=\"hidden\" name=\"system\"         value=\"". $system ."\" />\n";
$page .= "<input type=\"hidden\" name=\"planet\"         value=\"". $position ."\" />\n";
$page .= "<input type=\"hidden\" name=\"thisplanettype\" value=\"". $planet->getType() ."\" />\n";
$page .= "<input type=\"hidden\" name=\"planettype\"     value=\"". $planetType ."\" />\n";
$page .= "<input type=\"hidden\" name=\"speedallsmin\"   value=\"". $session['speedallsmin'] ."\" />\n";
$page .= "<input type=\"hidden\" name=\"speed\"          value=\"". $speed ."\" />\n";
$page .= "<input type=\"hidden\" name=\"usedfleet\"      value=\"". $MaxFlyingFleets ."\" />\n";
$page .= "<input type=\"hidden\" name=\"maxepedition\"   value=\"". $maxExpedition ."\" />\n";
$page .= "<input type=\"hidden\" name=\"curepedition\"   value=\"". $ExpeditionEnCours ."\" />\n";
foreach ($fleetArray as $shipId => $count) {
    $page .= "<input type=\"hidden\" name=\"ship[". $shipId ."]\"        value=\"". $count ."\" />\n";
    $page .= "<input type=\"hidden\" name=\"capacity[". $shipId ."]\"    value=\"". $pricelist[$shipId]['capacity'] ."\" />\n";
    $page .= "<input type=\"hidden\" name=\"consumption[". $shipId ."]\" value=\"". GetShipConsumption($shipId, $user) ."\" />\n";
    $page .= "<input type=\"hidden\" name=\"speed[". $shipId ."]\"       value=\"". GetFleetMaxSpeed(array(), $shipId, $user) ."\" />\n";

}
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"519\">\n";
$page .= "<tbody>\n";
$page .= "<tr align=\"left\" height=\"20\">\n";
$page .= "<td class=\"c\" colspan=\"2\">". $TableTitle ."</td>\n";
$page .= "</tr>\n";
$page .= "<tr align=\"left\" valign=\"top\">\n";
$page .= "<th width=\"50%\">\n";
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"259\">\n";
$page .= "<tbody>\n";
$page .= "<tr height=\"20\">\n";
$page .= "<td class=\"c\" colspan=\"2\">". $lang['fl_mission'] ."</td>\n";
$page .= "</tr>\n";
$page .= $MissionSelector;
$page .= "</tbody>\n";
$page .= "</table>\n";
$page .= "</th>\n";
$page .= "<th>\n";
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"259\">\n";
$page .= "<tbody>\n";
$page .= "<tr height=\"20\">\n";
$page .= "<td colspan=\"3\" class=\"c\">". $lang['fl_ressources'] ."</td>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th>". $lang['Metal'] ."</th>\n";
$page .= "<th><a href=\"javascript:maxResource('1');\">". $lang['fl_selmax'] ."</a></th>\n";
$page .= "<th><input name=\"resource1\" alt=\"". $lang['Metal'] ." ". floor($planet["metal"]) ."\" size=\"10\" onchange=\"calculateTransportCapacity();\" type=\"text\"></th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th>". $lang['Crystal'] ."</th>\n";
$page .= "<th><a href=\"javascript:maxResource('2');\">". $lang['fl_selmax'] ."</a></th>\n";
$page .= "<th><input name=\"resource2\" alt=\"". $lang['Crystal'] ." ". floor($planet["crystal"]) ."\" size=\"10\" onchange=\"calculateTransportCapacity();\" type=\"text\"></th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th>". $lang['Deuterium'] ."</th>\n";
$page .= "<th><a href=\"javascript:maxResource('3');\">". $lang['fl_selmax'] ."</a></th>\n";
$page .= "<th><input name=\"resource3\" alt=\"". $lang['Deuterium'] ." ". floor($planet["deuterium"]) ."\" size=\"10\" onchange=\"calculateTransportCapacity();\" type=\"text\"></th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th>". $lang['fl_space_left'] ."</th>\n";
$page .= "<th colspan=\"2\"><div id=\"remainingresources\">-</div></th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th colspan=\"3\"><a href=\"javascript:maxResources()\">". $lang['fl_allressources'] ."</a></th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th colspan=\"3\">&nbsp;</th>\n";
$page .= "</tr>\n";
if ($position == (Wootook::getGameConfig('engine/universe/positions') + 1)) {
    $page .= "<tr height=\"20\">";
    $page .= "<td class=\"c\" colspan=\"3\">". $lang['fl_expe_staytime'] ."</td>";
    $page .= "</tr>";
    $page .= "<tr height=\"20\">";
    $page .= "<th colspan=\"3\">";
    $page .= "<select name=\"expeditiontime\" >";
    for ($i = 1; $i <= 10; $i++) {
        $page .= "<option value=\"{$i}\">{$i}h</option>";
    }
    $page .= "</select>";
    $page .= $lang['fl_expe_hours'];
    $page .= "</th>";
    $page .= "</tr>";
} else if (isset($missionTypes[Legacies_Empire::ID_MISSION_STATION_ALLY]) || isset($missionTypes[Legacies_Empire::ID_MISSION_ORE_MINING])) {
    $page .= "<tr height=\"20\">";
    $page .= "<td class=\"c\" colspan=\"3\">". $lang['fl_expe_staytime'] ."</td>";
    $page .= "</tr>";
    $page .= "<tr height=\"20\">";
    $page .= "<th colspan=\"3\">";
    $page .= "<select name=\"holdingtime\" >";
    $page .= "<option value=\"0\">0</option>";
    for ($i = 1; $i <= 32; $i++) {
        $page .= "<option value=\"{$i}\">{$i}h</option>";
    }
    $page .= "</select>";
    $page .= $lang['fl_expe_hours'];
    $page .= "</th>";
    $page .= "</tr>";
}
$page .= "</tbody>\n";
$page .= "</table>\n";
$page .= "</th>\n";
$page .= "</tr><tr height=\"20\">\n";
$page .= "<th colspan=\"2\"><input accesskey=\"z\" value=\"". $lang['fl_continue'] ."\" type=\"submit\"></th>\n";
$page .= "</tr>\n";
$page .= "</tbody>\n";
$page .= "</table>\n";
$page .= "</form></center>\n";

display($page, $lang['fl_title']);
