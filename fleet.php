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

	$maxfleet  = doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', true);

	$MaxFlyingFleets     = $maxfleet['actcnt'];

    //Compteur de flotte en expéditions et nombre d'expédition maximum
    $MaxExpedition      = $user[$resource[124]];
    if ($MaxExpedition >= 1) {
		$maxexpde  = doquery("SELECT COUNT(fleet_owner) AS `expedi` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."' AND `fleet_mission` = '15';", 'fleets', true);
	    $ExpeditionEnCours  = $maxexpde['expedi'];
		$EnvoiMaxExpedition = 1 + floor( $MaxExpedition / 3 );
    }

	$MaxFlottes         = 1 + $user[$resource[108]];

	CheckPlanetUsedFields($planetrow);

	includeLang('fleet');

	$missiontype = array(
		1 => $lang['type_mission'][1],
		2 => $lang['type_mission'][2],
		3 => $lang['type_mission'][3],
		4 => $lang['type_mission'][4],
		5 => $lang['type_mission'][5],
		6 => $lang['type_mission'][6],
		7 => $lang['type_mission'][7],
		8 => $lang['type_mission'][8],
		9 => $lang['type_mission'][9],
		15 => $lang['type_mission'][15]
	);

	// Histoire de recuperer les infos passées par galaxy
	$galaxy         = $_GET['galaxy'];
	$system         = $_GET['system'];
	$planet         = $_GET['planet'];
	$planettype     = $_GET['planettype'];
	$target_mission = $_GET['target_mission'];

	if (!$galaxy) {
		$galaxy = $planetrow['galaxy'];
	}
	if (!$system) {
		$system = $planetrow['system'];
	}
	if (!$planet) {
		$planet = $planetrow['planet'];
	}
	if (!$planettype) {
		$planettype = $planetrow['planet_type'];
	}

	$page  = "<script language=\"JavaScript\" src=\"scripts/flotten.js\"></script>\n";
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
//	$page .= "<th>".$lang['fl_back_t']."</th>";
	$page .= "<th>".$lang['fl_back_in']."</th>";
	$page .= "<th>".$lang['fl_order']."</th>";
	$page .= "</tr>";

	// Gestion des flottes du joueur actif
	$fq = doquery("SELECT * FROM {{table}} WHERE fleet_owner={$user[id]}", "fleets");
	$i  = 0;


	while ($f = mysql_fetch_array($fq)) {
		$i++;
		$page .= "<tr height=20>";
		// (01) Fleet ID
		$page .= "<th>".$i."</th>";
		// (02) Fleet Mission
		$page .= "<th>";
		$page .= "<a>". $missiontype[$f[fleet_mission]] ."</a>";
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
		$page .= "\">". pretty_number($f[fleet_amount]) ."</a></th>";
		// (04) Fleet From (Planete d'origine)
		$page .= "<th>[".$f[fleet_start_galaxy].":".$f[fleet_start_system].":".$f[fleet_start_planet]."]</th>";
		// (05) Fleet Start Time
		$page .= "<th>". gmdate("d. M Y H:i:s", $f['fleet_start_time']) ."</th>";
		// (06) Fleet Target (Planete de destination)
		$page .= "<th>[".$f[fleet_end_galaxy].":".$f[fleet_end_system].":".$f[fleet_end_planet]."]</th>";
		// (07) Fleet Target Time
		$page .= "<th>". gmdate("d. M Y H:i:s", $f['fleet_end_time']) ."</th>";
		// (08) Fleet Back Time
//		$page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>". pretty_time(floor($f['fleet_end_time'] + 1 - time())) ."</font></th>";
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
//		$page .= "<th>-</th>";
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

	if (!$planetrow) {
		message($lang['fl_noplanetrow'], $lang['fl_error']);
	}

	// Prise des coordonnées sur la ligne de commande
	$galaxy         = intval($_GET['galaxy']);
	$system         = intval($_GET['system']);
	$planet         = intval($_GET['planet']);
	$planettype     = intval($_GET['planettype']);
	$target_mission = intval($_GET['target_mission']);
	$ShipData       = "";

	foreach ($reslist['fleet'] as $n => $i) {
		if ($planetrow[$resource[$i]] > 0) {
			$page .= "<tr height=\"20\">";
			$page .= "<th><a title=\"". $lang['fl_fleetspeed'] . $CurrentShipSpeed ."\">" . $lang['tech'][$i] . "</a></th>";
			$page .= "<th>". pretty_number ($planetrow[$resource[$i]]);
			$ShipData .= "<input type=\"hidden\" name=\"maxship". $i ."\" value=\"". $planetrow[$resource[$i]] ."\" />";
			$ShipData .= "<input type=\"hidden\" name=\"consumption". $i ."\" value=\"". GetShipConsumption ( $i, $user ) ."\" />";
			$ShipData .= "<input type=\"hidden\" name=\"speed" .$i ."\" value=\"" . GetFleetMaxSpeed ("", $i, $user) . "\" />";
			$ShipData .= "<input type=\"hidden\" name=\"capacity". $i ."\" value=\"". $pricelist[$i]['capacity'] ."\" />";
			$page .= "</th>";
			// Satelitte Solaire (eux ne peuvent pas bouger !)
			if ($i == 212) {
				$page .= "<th></th><th></th>";
			} else {
				$page .= "<th><a href=\"javascript:maxShip('ship". $i ."'); shortInfo();\">".$lang['fl_selmax']."</a> </th>";
				$page .= "<th><input name=\"ship". $i ."\" size=\"10\" value=\"0\" onfocus=\"javascript:if(this.value == '0') this.value='';\" onblur=\"javascript:if(this.value == '') this.value='0';\" alt=\"". $lang['tech'][$i] . $planetrow[$resource[$i]] ."\" onChange=\"shortInfo()\" onKeyUp=\"shortInfo()\" /></th>";
			}
			$page .= "</tr>";
		}
		$have_ships = true;
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
	$page .= "<input type=\"hidden\" name=\"planet\" value=\"". $planet ."\" />";
	$page .= "<input type=\"hidden\" name=\"planet_type\" value=\"". $planettype ."\" />";
	$page .= "<input type=\"hidden\" name=\"mission\" value=\"". $target_mission ."\" />";
	$page .= "<input type=\"hidden\" name=\"maxepedition\" value=\"". $EnvoiMaxExpedition ."\" />";
	$page .= "<input type=\"hidden\" name=\"curepedition\" value=\"". $ExpeditionEnCours ."\" />";
	$page .= "<input type=\"hidden\" name=\"target_mission\" value=\"". $target_mission ."\" />";
	$page .= "</form>";
	$page .= "</center>";

	display($page, $lang['fl_title']);

// Updated by Chlorel. 16 Jan 2008 (String extraction, bug corrections, code uniformisation
// Created by Perberos. All rights reversed (C) 2006
?>
