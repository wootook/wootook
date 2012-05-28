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
function GalaxyRowPlanet ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowUser, $Galaxy, $System, $Planet, $PlanetType ) {
	global $lang, $dpath, $user, $HavePhalanx, $CurrentSystem, $CurrentGalaxy;

	if (empty($GalaxyRowPlanet)) {
	    return '<th width="30"></th>';
	}
	// Planete (Image)
	$Result  = "<th width=30>";

	$GalaxyRowUser = doquery("SELECT * FROM {{table}} WHERE id='".$GalaxyRowPlanet['id_owner']."';", 'users', true);
	if ($GalaxyRow && $GalaxyRowPlanet["destruyed"] == 0 && $GalaxyRow["id_planet"] != 0) {
		if ($HavePhalanx <> 0) {
			if ($GalaxyRowUser['id'] != $user['id']) {
				if ($GalaxyRowPlanet["galaxy"] == $CurrentGalaxy) {
					$Range = GetPhalanxRange ( $HavePhalanx );
					if ($SystemLimitMin < 1) {
						$SystemLimitMin = 1;
					}
					$SystemLimitMax = $CurrentSystem + $Range;
					if ($System <= $SystemLimitMax) {
						if ($System >= $SystemLimitMin) {
							$PhalanxTypeLink = "<a href=# onclick=fenster(&#039;phalanx.php?galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$PlanetType."&#039;) >".$lang['gl_phalanx']."</a><br />";
						} else {
							$PhalanxTypeLink = "";
						}
					} else {
						$PhalanxTypeLink = "";
					}
				} else {
					$PhalanxTypeLink = "";
				}
			} else {
				$PhalanxTypeLink = "";
			}
		} else {
			$PhalanxTypeLink = "";
		}

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
		$MissionType3Link = "<a href=fleet.php?galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$PlanetType."&target_mission=3>". $lang['type_mission'][3] ."</a>";

		$Result .= "<a style=\"cursor: pointer;\"";
		$Result .= " onmouseover='return overlib(\"";
		$Result .= "<table width=240>";
		$Result .= "<tr>";
		$Result .= "<td class=c colspan=2>";
		$Result .= $lang['gl_planet'] ." ". stripslashes($GalaxyRowPlanet["name"]) ." [".$Galaxy.":".$System.":".$Planet."]";
		$Result .= "</td>";
		$Result .= "</tr>";
		$Result .= "<tr>";
		$Result .= "<th width=80>";
		$Result .= "<img src=". $dpath ."graphics/planeten/small/s_". $GalaxyRowPlanet["image"] .".jpg height=75 width=75 />";
		$Result .= "</th>";
		$Result .= "<th align=left>";
		$Result .= $MissionType6Link;
		$Result .= $PhalanxTypeLink;
		$Result .= $MissionType1Link;
		$Result .= $MissionType5Link;
		$Result .= $MissionType4Link;
		$Result .= $MissionType3Link;
		$Result .= "</th>";
		$Result .= "</tr>";
		$Result .= "</table>\"";
//		$Result .= ", STICKY, MOUSEOFF, DELAY, ". ($user["settings_tooltiptime"] * 1000) .", CENTER, OFFSETX, -40, OFFSETY, -40 );'";
		$Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );'";
		$Result .= " onmouseout='return nd();'>";
		$Result .= "<img src=".	$dpath ."graphics/planeten/small/s_". $GalaxyRowPlanet["image"] .".jpg height=30 width=30>";
//		$Result .= $GalaxyRowPlanet["name"];
		$Result .= "</a>";
	}
	$Result .= "</th>";

	return $Result;
}

?>
