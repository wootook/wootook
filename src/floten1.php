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

$speed = array(
    10 => 100,
    9  => 90,
    8  => 80,
    7  => 70,
    6  => 60,
    5  => 50,
    4  => 40,
    3  => 30,
    2  => 20,
    1  => 10,
    );

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$planetrow = $user->getCurrentPlanet();

$g = isset($_POST['galaxy']) ? intval($_POST['galaxy']) : 0;
$s = isset($_POST['system']) ? intval($_POST['system']) : 0;
$p = isset($_POST['planet']) ? intval($_POST['planet']) : 0;
$t = isset($_POST['planet_type']) ? intval($_POST['planet_type']) : 0;

if ($g <= 0 || $g > Wootook::getGameConfig('engine/universe/galaxies')) {
    $g = $planetrow['galaxy'];
}
if ($s <= 0 || $s > Wootook::getGameConfig('engine/universe/systems')) {
    $s = $planetrow['system'];
}
if ($p <= 0 || $p > Wootook::getGameConfig('engine/universe/positions')) {
    $p = $planetrow['planet'];
}
$allowedPlanetTypes = array(
    Wootook_Empire_Model_Planet::TYPE_PLANET,
    Wootook_Empire_Model_Planet::TYPE_DEBRIS,
    Wootook_Empire_Model_Planet::TYPE_MOON
    );

if (!in_array($t, $allowedPlanetTypes)) {
    $t = $planet['planet_type'];
}

$session = Wootook::getSession('fleet');
$session->clearData();
$FleetHiddenBlock  = "";

$fleetArray = array();
$speedArray = array();
$consumptionArray = array();
$capacityArray = array();
if (isset($_POST['ship']) && is_array($_POST['ship'])) {
    $selectedShips = $_POST['ship'];
}
foreach ($reslist[Legacies_Empire::TYPE_SHIP] as $shipId) {
    if (!isset($selectedShips[$shipId])) {
        continue;
    }

    $qty = intval($selectedShips[$shipId]);
    if ($qty <= 0) {
        continue;
    }

    if ($qty > $planetrow->getElement($shipId)) {
        $page .= $lang['fl_noenought'];
        $speedalls[$shipId] = GetFleetMaxSpeed("", $shipId, $user);
    } else {
        $fleetArray[$shipId] = $qty;
        $session['qty'] += $qty;
        $speedArray[$shipId] = GetFleetMaxSpeed("", $shipId, $user);
        $consumptionArray[$shipId] = GetShipConsumption($shipId, $user);
        $capacityArray[$shipId] = $pricelist[$shipId]['capacity'];

        // Tableau des vitesses
        $FleetHiddenBlock   .= "<input type=\"hidden\" name=\"consumption[". $shipId ."]\" value=\"". GetShipConsumption($shipId, $user) ."\" />";
        $FleetHiddenBlock   .= "<input type=\"hidden\" name=\"speed[". $shipId ."]\"       value=\"". GetFleetMaxSpeed("", $shipId, $user) ."\" />";
        $FleetHiddenBlock   .= "<input type=\"hidden\" name=\"capacity[". $shipId ."]\"    value=\"". $pricelist[$shipId]['capacity'] ."\" />";
        $FleetHiddenBlock   .= "<input type=\"hidden\" name=\"ship[". $shipId ."]\"        value=\"". $qty ."\" />";
        $speedalls[$shipId]  = GetFleetMaxSpeed("", $shipId, $user);
    }
}
unset($selectedShips);

$session['fleet'] = $fleetArray;
$session['speed'] = $speedArray;
$session['consumption'] = $consumptionArray;
$session['capacity'] = $capacityArray;

if (empty($fleetArray)) {
    message($lang['fl_unselectall'], $lang['fl_error'], "fleet." . PHPEXT, 1);
} else {
    $speedallsmin = min($speedalls);
}

$speedFactor = GetGameSpeedFactor();
$session['speedallsmin'] = $speedallsmin;

$scriptPath = Wootook::getStaticUrl('scripts/flotten.js');
$page = "<script type=\"text/javascript\" src=\"{$scriptPath}\"></script>";
$page .= "<script type=\"text/javascript\">\n";
$page .= "function getStorageFaktor() {\n";
$page .= "    return 1\n";
$page .= "}\n";
$page .= "</script>\n";
$page .= "<form action=\"floten2.php\" method=\"post\">";
$page .= $FleetHiddenBlock;
$page .= "<input type=\"hidden\" name=\"speedallsmin\"   value=\"". $speedallsmin ."\" />";
$page .= "<input type=\"hidden\" name=\"thisgalaxy\"     value=\"". $planetrow['galaxy'] ."\" />";
$page .= "<input type=\"hidden\" name=\"thissystem\"     value=\"". $planetrow['system'] ."\" />";
$page .= "<input type=\"hidden\" name=\"thisplanet\"     value=\"". $planetrow['planet'] ."\" />";
$page .= "<input type=\"hidden\" name=\"galaxyend\"      value=\"". $g ."\" />";
$page .= "<input type=\"hidden\" name=\"systemend\"      value=\"". $s ."\" />";
$page .= "<input type=\"hidden\" name=\"planetend\"      value=\"". $p ."\" />";
$page .= "<input type=\"hidden\" name=\"speedfactor\"    value=\"". $speedFactor ."\" />";
$page .= "<input type=\"hidden\" name=\"thisplanettype\" value=\"". $t ."\" />";
$page .= "<input type=\"hidden\" name=\"thisresource1\"  value=\"". floor($planetrow['metal']) ."\" />";
$page .= "<input type=\"hidden\" name=\"thisresource2\"  value=\"". floor($planetrow['crystal']) ."\" />";
$page .= "<input type=\"hidden\" name=\"thisresource3\"  value=\"". floor($planetrow['deuterium']) ."\" />";

$page .= "<br><div><center>";
$page .= "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"2\" class=\"c\">". $lang['fl_floten1_ttl'] ."</td>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th width=\"50%\">". $lang['fl_dest'] ."</th>";
$page .= "<th>";
$page .= "<input name=\"galaxy\" size=\"3\" maxlength=\"2\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" value=\"". $g ."\" />";
$page .= "<input name=\"system\" size=\"3\" maxlength=\"3\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" value=\"". $s ."\" />";
$page .= "<input name=\"planet\" size=\"3\" maxlength=\"2\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" value=\"". $p ."\" />";
$page .= "<select name=\"planettype\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\">";
$page .= "<option value=\"1\"". (($t == 1) ? " SELECTED" : "" ) .">". $lang['fl_planet'] ." </option>";
$page .= "<option value=\"2\"". (($t == 2) ? " SELECTED" : "" ) .">". $lang['fl_ruins']  ." </option>";
$page .= "<option value=\"3\"". (($t == 3) ? " SELECTED" : "" ) .">". $lang['fl_moon'] ." </option>";
$page .= "</select>";
$page .= "</th>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th>". $lang['fl_speed'] ."</th>";
$page .= "<th>";
$page .= "<select name=\"speed\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\">";
foreach ($speed as $a => $b) {
    $page .= "<option value=\"".$a."\">".$b."</option>";
}
$page .= "</select> %";
$page .= "</th>";
$page .= "</tr>";

$page .= "<tr height=\"20\">";
$page .= "<th>". $lang['fl_dist'] ."</th>";
$page .= "<th><div id=\"distance\">-</div></th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_fltime'] ."</th>";
$page .= "<th><div id=\"duration\">-</div></th>";
$page .= "</tr><tr height=\"20\">";
/* A faire assez rapidement (faut juste savoir comment)
    $page .= "<th>". $lang['fl_time_go'] ."</th>";
    $page .= "<th><font color=\"lime\"><div id=\"llegada1\"><font>". gmdate("H:i:s") ."</font></div></font></th>";
    $page .= "</tr><tr height=\"20\">";
    $page .= "<th>". $lang['fl_time_back'] ."</th>";
    $page .= "<th><font color=\"lime\"><div id=\"llegada2\"><font>". gmdate("H:i:s") ."</font></div></font></th>";
    $page .= "</tr><tr height=\"20\">";
*/
$page .= "<th>". $lang['fl_deute_need'] ."</th>";
$page .= "<th><div id=\"consumption\">-</div></th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_speed_max'] ."</th>";
$page .= "<th><div id=\"maxspeed\">-</div></th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_max_load'] ."</th>";
$page .= "<th><div id=\"storage\">-</div></th>";
$page .= "</tr>";

// Gestion des raccourcis sur la galaxie
$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"2\" class=\"c\">". $lang['fl_shortcut'] ." <a href=\"fleetshortcut.php\">". $lang['fl_shortlnk'] ."</a></td>";
$page .= "</tr>";
if ($user['fleet_shortcut']) {
    $scarray = explode("\r\n", $user['fleet_shortcut']);
    $i = 0;
    foreach ($scarray as $a => $b) {
        if ($b != "") {
            $c = explode(',', $b);
            if ($i == 0) {
                $page .= "<tr height=\"20\">";
            }
            $page .= "<th><a href=\"javascript:setTarget(". $c[1] .",". $c[2] .",". $c[3] .",". $c[4] ."); shortInfo();\"";
            $page .= ">". $c[0] ." ". $c[1] .":". $c[2] .":". $c[3] ." ";
            // Signalisation du type de raccourci ...
            // (P)lanete
            // (D)ebris
            // (L)une
            if ($c[4] == 1) {
                $page .= $lang['fl_shrtcup1'];
            } elseif ($c[4] == 2) {
                $page .= $lang['fl_shrtcup2'];
            } elseif ($c[4] == 3) {
                $page .= $lang['fl_shrtcup3'];
            }
            $page .= "</a></th>";
            if ($i == 1) {
                $page .= "</tr>";
            }
            if ($i == 1) {
                $i = 0;
            } else {
                $i = 1;
            }
        }
    }
    if ($i == 1) {
        $page .= "<th></th></tr>";
    }
} else {
    $page .= "<tr height=\"20\">";
    $page .= "<th colspan=\"2\">". $lang['fl_noshortc'] ."</th>";
    $page .= "</tr>";
}

$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"2\" class=\"c\">". $lang['fl_myplanets'] ."</td>";
$page .= "</tr>";

// Gestion des raccourcis vers ses propres colonies ou planetes
$kolonien      = $user->getPlanetCollection();
$currentplanet = $user->getCurrentPlanet();

if ($kolonien->getSize() > 1) {
    $i = 0;
    $w = 0;
    $tr = true;
    foreach ($user->getPlanetCollection() as $userPlanet) {
        /** @var Wootook_Empire_Model_Planet $userPlanet */
        if ($w == 0 && $tr) {
            $page .= "<tr height=\"20\">";
            $tr = false;
        }
        if ($w == 2) {
            $page .= "</tr>";
            $w = 0;
            $tr = true;
        }

        $name = $userPlanet->getName();
        if ($userPlanet->getType() == Wootook_Empire_Model_Planet::TYPE_MOON) {
            $name .= " " . $lang['fl_shrtcup3'];
        }

        if ($currentplanet->getId() != $userPlanet->getId()) {
            $page .= "<th><a href=\"javascript:setTarget(". $userPlanet->getGalaxy() .",". $userPlanet->getSystem() .",". $userPlanet->getPosition() .",". $userPlanet->getType() ."); shortInfo();\">". $name . " ". $userPlanet->getCoords() ."</a></th>";
            $w++;
            $i++;
        }
    }

    if ($i % 2 != 0) {
        $page .= "<th>&nbsp;</th></tr>";
    } elseif ($w == 2) {
        $page .= "</tr>";
    }
} else {
    $page .= "<th colspan=\"2\">". $lang['fl_nocolonies'] ."</th>";
}

$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"2\" class=\"c\">". $lang['fl_grattack'] ."</td>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th colspan=\"2\">-</th>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th colspan=\"2\"><input type=\"submit\" value=\"". $lang['fl_continue'] ."\" /></th>";
$page .= "</tr>";
$page .= "</table>";
$page .= "</div></center>";

$maxExpedition = $user->getElement(Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY);
$ExpeditionEnCours = 0;
$EnvoiMaxExpedition = 0;
if ($maxExpedition >= 1) {
    $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');
    $ExpeditionEnCours = $readAdapter->select()
        ->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'))
        ->from(array('fleet' => $readAdapter->getTable('fleets')))
        ->where('fleet_owner', $user->getId())
        ->where('fleet_mission', Legacies_Empire::ID_MISSION_EXPEDITION)
        ->prepare()
        ->fetchColumn()
    ;

    $EnvoiMaxExpedition = 1 + floor($maxExpedition / 3);
}
$page .= "<input type=\"hidden\" name=\"maxepedition\" value=\"". $maxExpedition ."\" />";
$page .= "<input type=\"hidden\" name=\"curepedition\" value=\"". $ExpeditionEnCours ."\" />";
$page .= "<input type=\"hidden\" name=\"target_mission\" value=\"". (isset($_GET['target_mission']) ? intval($_GET['target_mission']) : '') ."\" />";
$page .= "</form>";
$page .= "<script>javascript:shortInfo(); </script>";

display($page, $lang['fl_title']);

