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
function GalaxyRowDebris ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowUser, $Galaxy, $System, $Planet, $PlanetType ) {
	global $lang, $dpath, $CurrentRC, $user, $pricelist;
	// Cdr
	$Result  = "<th style=\"white-space: nowrap;\" width=30>";
	if ($GalaxyRow) {
		if ($GalaxyRow["metal"] != 0 || $GalaxyRow["crystal"] != 0) {
			$RecNeeded = ceil(($GalaxyRow["metal"] + $GalaxyRow["crystal"]) / $pricelist[209]['capacity']);
			if ($RecNeeded < $CurrentRC) {
				$RecSended = $RecNeeded;
			} elseif ($RecNeeded >= $CurrentRC) {
				$RecSended = $CurrentRC;
			} else {
				$RecSended = $RecyclerCount;
			}
			$Result  = "<th style=\"";
			if       (($GalaxyRow["metal"] + $GalaxyRow["crystal"]) >= 10000000) {
				$Result .= "background-color: rgb(100, 0, 0);";
			} elseif (($GalaxyRow["metal"] + $GalaxyRow["crystal"]) >= 1000000) {
				$Result .= "background-color: rgb(100, 100, 0);";
			} elseif (($GalaxyRow["metal"] + $GalaxyRow["crystal"]) >= 100000) {
				$Result .= "background-color: rgb(0, 100, 0);";
			}
			$Result .= "background-image: none;\" width=30>";
			$Result .= "<a style=\"cursor: pointer;\"";
			$Result .= " onmouseover='return overlib(\"";
			$Result .= "<table width=240>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>";
			$Result .= $lang['Debris']." [".$Galaxy.":".$System.":".$Planet."]";
			$Result .= "</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th width=80>";
			$Result .= "<img src=". $dpath ."planeten/debris.jpg height=75 width=75 />";
			$Result .= "</th>";
			$Result .= "<th>";
			$Result .= "<table>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>".$lang['gl_ressource']."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['Metal']." </th><th>". number_format( $GalaxyRow['metal'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['Crystal']." </th><th>". number_format( $GalaxyRow['crystal'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<td class=c colspan=2>".$lang['gl_action']."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th colspan=2 align=left>";
			$Result .= "<a href= # onclick=&#039javascript:doit (8, ".$Galaxy.", ".$System.", ".$Planet.", ".$PlanetType.", ".$RecSended.");&#039 >". $lang['type_mission'][8] ."</a>";
			$Result .= "</tr>";
			$Result .= "</table>";
			$Result .= "</th>";
			$Result .= "</tr>";
			$Result .= "</table>\"";
//			$Result .= ", STICKY, MOUSEOFF, DELAY, ". ($user["settings_tooltiptime"] * 1000) .", CENTER, OFFSETX, -40, OFFSETY, -40 );'";
            $Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );'";
			$Result .= " onmouseout='return nd();'>";
			$Result .= "<img src=". $dpath ."planeten/debris.jpg height=22 width=22></a>";
		}
	}
	$Result .= "</th>";

	return $Result;
}

?>