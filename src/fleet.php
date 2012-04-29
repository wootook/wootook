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
$labels = Wootook_Empire_Helper_Config_Labels::getSingleton();

//Compteur de flotte en expéditions et nombre d'expédition maximum
$maxExpedition = $user->getElement(Legacies_Empire::ID_RESEARCH_EXPEDITION_TECHNOLOGY);
$ExpeditionEnCours = 0;
$EnvoiMaxExpedition = 0;
if ($maxExpedition >= 1) {
    $maxexpde = doquery("SELECT 1 FROM {{table}} WHERE `fleet_owner` = '".$user['id']."' AND `fleet_mission` = '15';", 'fleets');
    $ExpeditionEnCours = $maxexpde->rowCount();
    $maxexpde->closeCursor();

    $EnvoiMaxExpedition = 1 + floor($maxExpedition / 3);
}

$MaxFlottes = 1 + $user->getElement(Legacies_Empire::ID_RESEARCH_COMPUTER_TECHNOLOGY);

includeLang('fleet');

$missionTypes = array(
    Legacies_Empire::ID_MISSION_ATTACK        => $lang['type_mission'][Legacies_Empire::ID_MISSION_ATTACK],
    Legacies_Empire::ID_MISSION_GROUP_ATTACK  => $lang['type_mission'][Legacies_Empire::ID_MISSION_GROUP_ATTACK],
    Legacies_Empire::ID_MISSION_TRANSPORT     => $lang['type_mission'][Legacies_Empire::ID_MISSION_TRANSPORT],
    Legacies_Empire::ID_MISSION_STATION       => $lang['type_mission'][Legacies_Empire::ID_MISSION_STATION],
    Legacies_Empire::ID_MISSION_STATION_ALLY  => $lang['type_mission'][Legacies_Empire::ID_MISSION_STATION_ALLY],
    Legacies_Empire::ID_MISSION_SPY           => $lang['type_mission'][Legacies_Empire::ID_MISSION_SPY],
    Legacies_Empire::ID_MISSION_SETTLE_COLONY => $lang['type_mission'][Legacies_Empire::ID_MISSION_SETTLE_COLONY],
    Legacies_Empire::ID_MISSION_RECYCLE       => $lang['type_mission'][Legacies_Empire::ID_MISSION_RECYCLE],
    Legacies_Empire::ID_MISSION_DESTROY       => $lang['type_mission'][Legacies_Empire::ID_MISSION_DESTROY],
    Legacies_Empire::ID_MISSION_EXPEDITION    => $lang['type_mission'][Legacies_Empire::ID_MISSION_EXPEDITION]
);

// Histoire de recuperer les infos passées par galaxy
$galaxy        = isset($_GET['galaxy']) ? intval($_GET['galaxy']) : 0;
$system        = isset($_GET['system']) ? intval($_GET['system']) : 0;
$position      = isset($_GET['planet']) ? intval($_GET['planet']) : 0;
$planetType    = isset($_GET['planettype']) ? intval($_GET['planettype']) : 0;
$targetMission = isset($_GET['target_mission']) ? intval($_GET['target_mission']) : 0;

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$planet = $user->getCurrentPlanet();

$MaxFlyingFleets = $user->getFleetCount();

if ($galaxy <= 0 || $galaxy > Wootook::getGameConfig('engine/universe/galaxies')) {
    $galaxy = $planet['galaxy'];
}
if ($system <= 0 || $system > Wootook::getGameConfig('engine/universe/systems')) {
    $system = $planet['system'];
}
if ($position <= 0 || $position > Wootook::getGameConfig('engine/universe/positions')) {
    $position = $planet['planet'];
}

$allowedPlanetTypes = array(
    Wootook_Empire_Model_Planet::TYPE_PLANET,
    Wootook_Empire_Model_Planet::TYPE_DEBRIS,
    Wootook_Empire_Model_Planet::TYPE_MOON
    );

if (!in_array($planetType, $allowedPlanetTypes)) {
    $planetType = $planet['planet_type'];
}

$page  = "<script type=\"text/javascript\" src=\"scripts/flotten.js\"></script>\n";
$page .= "<script language=\"JavaScript\" src=\"scripts/ocnt.js\"></script>\n";
$page .= "<br><center>";
$page .= "<table width='519' border='0' cellpadding='0' cellspacing='1'>";
$page .= "<tr height='20'>";
$page .= "<td colspan='9' class='c'>";
$page .= "<table border=\"0\" width=\"100%\">";
$page .= "<tbody><tr>";
$page .= "<td style=\"background-color: transparent;\">";
$page .= $lang['fl_title']." ".$MaxFlyingFleets." ".$lang['fl_sur']." ".$MaxFlottes;
$page .= "</td><td style=\"background-color: transparent;\" align=\"right\">";
$page .= $ExpeditionEnCours."/".$EnvoiMaxExpedition." ".$lang['fl_expttl'];
$page .= "</td>";
$page .= "</tr></tbody></table>";
$page .= "</td>";
$page .= "</tr><tr height='20'>";
$page .= "<th>".$lang['fl_id']."</th>";
$page .= "<th>".$lang['fl_mission']."</th>";
$page .= "<th>".$lang['fl_count']."</th>";
$page .= "<th>".$lang['fl_from']."</th>";
$page .= "<th>".$lang['fl_start_t']."</th>";
$page .= "<th>".$lang['fl_dest']."</th>";
$page .= "<th>".$lang['fl_dest_t']."</th>";
//$page .= "<th>".$lang['fl_back_t']."</th>";
$page .= "<th>".$lang['fl_back_in']."</th>";
$page .= "<th>".$lang['fl_order']."</th>";
$page .= "</tr>";

$i = 0;
foreach ($user->getFleetCollection() as $f) {
    $i++;
    $page .= "<tr height=20>";
    // (01) Fleet ID
    $page .= "<th>".$i."</th>";
    // (02) Fleet Mission
    $page .= "<th>";
    $page .= "<a>". $missionTypes[$f['fleet_mission']] ."</a>";
    if (($f['fleet_start_time'] + 1) == $f['fleet_end_time']) {
        $page .= "<br><a title=\"".$lang['fl_back_to_ttl']."\">".$lang['fl_back_to']."</a>";
    } else {
        $page .= "<br><a title=\"".$lang['fl_get_to_ttl']."\">".$lang['fl_get_to']."</a>";
    }
    $page .= "</th>";
    // (03) Fleet Mission
    $page .= "<th><a title=\"";
    // Fleet details (commentaire)
    $fleet = explode(";", $f['fleet_array']);
    $e = 0;
    foreach ($fleet as $a => $b) {
        if ($b != '') {
            $e++;
            $a = explode(",", $b);
            $page .= $lang['tech'][$a[0]]. ":". $a[1] ."\n";
            if ($e > 1) {
                $page .= "\t";
            }
        }
    }
    $page .= "\">". pretty_number($f['fleet_amount']) ."</a></th>";
    // (04) Fleet From (Planete d'origine)
    $page .= "<th>[".$f[fleet_start_galaxy].":".$f['fleet_start_system'].":".$f['fleet_start_planet']."]</th>";
    // (05) Fleet Start Time
    $page .= "<th>". gmdate("d. M Y H:i:s", $f['fleet_start_time']) ."</th>";
    // (06) Fleet Target (Planete de destination)
    $page .= "<th>[".$f['fleet_end_galaxy'].":".$f['fleet_end_system'].":".$f['fleet_end_planet']."]</th>";
    // (07) Fleet Target Time
    $page .= "<th>". gmdate("d. M Y H:i:s", $f['fleet_end_time']) ."</th>";
    // (08) Fleet Back Time
//    $page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>". pretty_time(floor($f['fleet_end_time'] + 1 - time())) ."</font></th>";
    // (09) Fleet Back In
    $page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>". pretty_time(floor($f['fleet_end_time'] + 1 - time())) ."</font></th>";
    // (10) Orders
    $page .= "<th>";
    if ($f['fleet_mess'] == 0) {
            $page .= "<form action=\"fleetback.php\" method=\"post\">";
            $page .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
            $page .= "<input value=\" ".$lang['fl_back_to_ttl']." \" type=\"submit\" name=\"send\">";
            $page .= "</form>";
        if ($f[fleet_mission] == 1) {
            $page .= "<form action=\"verband.php\" method=\"post\">";
            $page .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
            $page .= "<input value=\" ".$lang['fl_associate']." \" type=\"submit\">";
            $page .= "</form>";
        }
    } else {
        $page .= "&nbsp;-&nbsp;";
    }
    $page .= "</th>";
    // Fin de ligne
    $page .= "</tr>";
}

    // Y a pas de flottes en vol ... on met des '-'
if ($i == 0) {
    $page .= "<tr>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
//    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "<th>-</th>";
    $page .= "</tr>";
}

if ($MaxFlottes == $MaxFlyingFleets) {
    $page .= "<tr height=\"20\"><th colspan=\"9\"><font color=\"red\">".$lang['fl_noslotfree']."</font></th></tr>";
}

$page .= "</table></center>";

$page .= "<center>";

// Selection d'une nouvelle mission
$page .= "<form action=\"floten1.php\" method=\"post\">";
$page .= "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"4\" class=\"c\">".$lang['fl_new_miss']."</td>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th>".$lang['fl_fleet_typ']."</th>";
$page .= "<th>".$lang['fl_fleet_disp']."</th>";
$page .= "<th>-</th>";
$page .= "<th>-</th>";
$page .= "</tr>";

if (!$planet) {
    message($lang['fl_noplanetrow'], $lang['fl_error']);
}

$ShipData       = "";

$have_ships = false;
foreach ($reslist['fleet'] as $n => $i) {
    if ($planet->getElement($i) > 0) {
        $page .= "<tr height=\"20\">";
        $page .= "<th><a title=\"". $lang['fl_fleetspeed'] /*. $CurrentShipSpeed*/ ."\">" . $labels[$i]['name'] . "</a></th>";
        $page .= "<th>". pretty_number ($planet->getElement($i));
        $ShipData .= "<input type=\"hidden\" name=\"maxship[". $i ."]\" value=\"". $planet->getElement($i) ."\" />";
        $ShipData .= "<input type=\"hidden\" name=\"consumption[". $i ."]\" value=\"". GetShipConsumption ( $i, $user ) ."\" />";
        $ShipData .= "<input type=\"hidden\" name=\"speed[" .$i ."]\" value=\"" . GetFleetMaxSpeed ("", $i, $user) . "\" />";
        $ShipData .= "<input type=\"hidden\" name=\"capacity[". $i ."]\" value=\"". $pricelist[$i]['capacity'] ."\" />";
        $page .= "</th>";
        // Satelitte Solaire (eux ne peuvent pas bouger !)
        if ($i == 212) {
            $page .= "<th></th><th></th>";
        } else {
            $page .= "<th><a href=\"javascript:maxShip('ship[". $i ."]'); shortInfo();\">".$lang['fl_selmax']."</a> </th>";
            $page .= "<th><input name=\"ship[". $i ."]\" size=\"10\" value=\"0\" onfocus=\"javascript:if(this.value == '0') this.value='';\" onblur=\"javascript:if(this.value == '') this.value='0';\" alt=\"". $labels[$i]['name'] . $planet->getElement($i) ."\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" /></th>";
        }
        $page .= "</tr>";
        $have_ships = true;
    }
}

$btncontinue = "<tr height=\"20\"><th colspan=\"4\"><input type=\"submit\" value=\" ".$lang['fl_continue']." \" /></th>";
$page .= "<tr height=\"20\">";
if (!$have_ships) {
    // Il n'y a pas de vaisseaux sur cette planete
    $page .= "<th colspan=\"4\">". $lang['fl_noships'] ."</th>";
    $page .= "</tr>";
    $page .= $btncontinue;
} else {
    $page .= "<th colspan=\"2\"><a href=\"javascript:noShips();shortInfo();noResources();\" >". $lang['fl_unselectall'] ."</a></th>";
    $page .= "<th colspan=\"2\"><a href=\"javascript:maxShips();shortInfo();\" >". $lang['fl_selectall'] ."</a></th>";
    $page .= "</tr>";

    if ($MaxFlottes > $MaxFlyingFleets) {
        $page .= $btncontinue;
    }
}
$page .= "</tr>";
$page .= "</table>";
$page .= $ShipData;
$page .= "<input type=\"hidden\" name=\"galaxy\" value=\"". $galaxy ."\" />";
$page .= "<input type=\"hidden\" name=\"system\" value=\"". $system ."\" />";
$page .= "<input type=\"hidden\" name=\"planet\" value=\"". $position ."\" />";
$page .= "<input type=\"hidden\" name=\"planet_type\" value=\"". $planetType ."\" />";
$page .= "<input type=\"hidden\" name=\"mission\" value=\"". $targetMission ."\" />";
$page .= "<input type=\"hidden\" name=\"maxepedition\" value=\"". $EnvoiMaxExpedition ."\" />";
$page .= "<input type=\"hidden\" name=\"curepedition\" value=\"". $ExpeditionEnCours ."\" />";
$page .= "<input type=\"hidden\" name=\"target_mission\" value=\"". $targetMission ."\" />";
$page .= "</form>";
$page .= "</center>";

display($page, $lang['fl_title']);

