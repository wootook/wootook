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

/**
 *
 * @deprecated
 * @param unknown_type $GalaxyRow
 * @param unknown_type $GalaxyRowPlanet
 * @param unknown_type $GalaxyRowUser
 * @param unknown_type $Galaxy
 * @param unknown_type $System
 * @param unknown_type $Planet
 * @param unknown_type $PlanetType
 */
function GalaxyRowMoon($GalaxyRow, $GalaxyRowPlanet, $GalaxyRowUser, $Galaxy, $System, $Planet, $PlanetType ) {
	global $lang, $user, $dpath, $HavePhalanx, $CurrentSystem, $CurrentGalaxy, $CanDestroy;

	if (empty($GalaxyRowPlanet)) {
	    return '<th width="30"></th>';
	}

	// Lune
	$Result  = "<th style=\"white-space: nowrap;\" width=30>";
	if ($GalaxyRowUser['id'] != $user['id']) {
		$MissionType6Link = "<a href=# onclick=&#039javascript:doit(6, ".$Galaxy.", ".$System.", ".$Planet.", ".$PlanetType.", ".$user["spio_anz"].");&#039 >". $lang['type_mission'][6] ."</a><br /><br />";
	} elseif ($GalaxyRowUser['id'] == $user['id']) {
		$MissionType6Link = "";
	}
	if ($GalaxyRowUser['id'] != $user['id']) {
		$MissionType1Link = "<a href=fleet.php?galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."&amp;target_mission=1>". $lang['type_mission'][1] ."</a><br />";
	} elseif ($GalaxyRowUser['id'] == $user['id']) {
		$MissionType1Link = "";
	}

	if ($GalaxyRowUser['id'] != $user['id']) {
		$MissionType5Link = "<a href=fleet.php?galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=5>". $lang['type_mission'][5] ."</a><br />";
	} elseif ($GalaxyRowUser['id'] == $user['id']) {
		$MissionType5Link = "";
	}
	if ($GalaxyRowUser['id'] == $user['id']) {
		$MissionType4Link = "<a href=fleet.php?galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=4>". $lang['type_mission'][4] ."</a><br />";
	} elseif ($GalaxyRowUser['id'] != $user['id']) {
		$MissionType4Link = "";
	}

	if ($GalaxyRowUser['id'] != $user['id']) {
		if ($CanDestroy > 0) {
			$MissionType9Link = "<a href=fleet.php?galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=9>". $lang['type_mission'][9] ."</a>";
		} else {
			$MissionType9Link = "";
		}
	} elseif ($GalaxyRowUser['id'] == $user['id']) {
		$MissionType9Link = "";
	}

	$MissionType3Link = "<a href=fleet.php?galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=3>". $lang['type_mission'][3] ."</a><br />";

	if ($GalaxyRow && (!isset($GalaxyRowPlanet["destruyed"]) || $GalaxyRowPlanet["destruyed"] == 0) && $GalaxyRow["id_luna"] != 0) {
		$Result .= "<a style=\"cursor: pointer;\"";
		$Result .= " onmouseover='return overlib(\"";
		$Result .= "<table width=240>";
		$Result .= "<tr>";
		$Result .= "<td class=c colspan=2>";
		$Result .= $lang['Moon'].": ".$GalaxyRowPlanet["name"]." [".$Galaxy.":".$System.":".$Planet."]";
		$Result .= "</td>";
		$Result .= "</tr><tr>";
		$Result .= "<th width=80>";
		$Result .= "<img src=". $dpath ."planeten/mond.jpg height=75 width=75 />";
		$Result .= "</th>";
		$Result .= "<th>";
		$Result .= "<table>";
		$Result .= "<tr>";
		$Result .= "<td class=c colspan=2>".$lang['caracters']."</td>";
		$Result .= "</tr><tr>";
		$Result .= "<th>".$lang['diameter']."</th>";
		$Result .= "<th>". number_format($GalaxyRowPlanet['diameter'], 0, '', '.') ."</th>";
		$Result .= "</tr><tr>";
		$Result .= "<th>".$lang['temperature']."</th><th>". number_format($GalaxyRowPlanet['temp_min'], 0, '', '.') ."</th>";
		$Result .= "</tr><tr>";
		$Result .= "<td class=c colspan=2>".$lang['Actions']."</td>";
		$Result .= "</tr><tr>";
		$Result .= "<th colspan=2 align=center>";
		$Result .= $MissionType6Link;
		$Result .= $MissionType3Link;
		$Result .= $MissionType4Link;
		$Result .= $MissionType1Link;
		$Result .= $MissionType5Link;
		$Result .= $MissionType9Link;
		$Result .= "</tr>";
		$Result .= "</table>";
		$Result .= "</th>";
		$Result .= "</tr>";
		$Result .= "</table>\"";
//        $Result .= ", STICKY, MOUSEOFF, DELAY, ". ($user["settings_tooltiptime"] * 1000) .", CENTER, OFFSETX, -40, OFFSETY, -40 );'";
		$Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );'";
		$Result .= " onmouseout='return nd();'>";
		$Result .= "<img src=". $dpath ."planeten/small/s_mond.jpg height=22 width=22>";
		$Result .= "</a>";
	}
	$Result .= "</th>";

	return $Result;
}

?>