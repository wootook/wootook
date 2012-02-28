<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
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
function GalaxyRowUser ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowUser, $Galaxy, $System, $Planet, $PlanetType ) {
	global $lang, $user;

	// Joueur
	$Result  = "<th width=150>";
	if ($GalaxyRowUser && $GalaxyRowPlanet["destruyed"] == 0) {
		$NoobProt      = doquery("SELECT * FROM {{table}} WHERE `config_name` = 'noobprotection';", 'config', true);
		$NoobTime      = doquery("SELECT * FROM {{table}} WHERE `config_name` = 'noobprotectiontime';", 'config', true);
		$NoobMulti     = doquery("SELECT * FROM {{table}} WHERE `config_name` = 'noobprotectionmulti';", 'config', true);
		$UserPoints    = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);
		$User2Points   = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $GalaxyRowUser['id'] ."';", 'statpoints', true);
		$CurrentPoints = $UserPoints['total_points'];
		$RowUserPoints = $User2Points['total_points'];
		$CurrentLevel  = $CurrentPoints * $NoobMulti['config_value'];
		$RowUserLevel  = $RowUserPoints * $NoobMulti['config_value'];
		if       ($GalaxyRowUser['bana'] == 1 AND
				  $GalaxyRowUser['urlaubs_modus'] == 1) {
			$Systemtatus2 = $lang['vacation_shortcut']." <a href=\"banned.php\"><span class=\"banned\">".$lang['banned_shortcut']."</span></a>";
			$Systemtatus  = "<span class=\"vacation\">";
		} elseif ($GalaxyRowUser['bana'] == 1) {
			$Systemtatus2 = "<a href=\"banned.php\"><span class=\"banned\">".$lang['banned_shortcut']."</span></a>";
			$Systemtatus  = "";
		} elseif ($GalaxyRowUser['urlaubs_modus'] == 1) {
			$Systemtatus2 = "<span class=\"vacation\">".$lang['vacation_shortcut']."</span>";
			$Systemtatus  = "<span class=\"vacation\">";
		} elseif ($GalaxyRowUser['onlinetime'] < (time()-60 * 60 * 24 * 7) AND
				  $GalaxyRowUser['onlinetime'] > (time()-60 * 60 * 24 * 28)) {
			$Systemtatus2 = "<span class=\"inactive\">".$lang['inactif_7_shortcut']."</span>";
			$Systemtatus  = "<span class=\"inactive\">";
		} elseif ($GalaxyRowUser['onlinetime'] < (time()-60 * 60 * 24 * 28)) {
			$Systemtatus2 = "<span class=\"inactive\">".$lang['inactif_7_shortcut']."</span><span class=\"longinactive\"> ".$lang['inactif_28_shortcut']."</span>";
			$Systemtatus  = "<span class=\"longinactive\">";
		} elseif ($RowUserLevel < $CurrentPoints AND
				  $NoobProt['config_value'] == 1 AND
				  $NoobTime['config_value'] * 1000 > $RowUserPoints) {
			$Systemtatus2 = "<span class=\"noob\">".$lang['weak_player_shortcut']."</span>";
			$Systemtatus  = "<span class=\"noob\">";
		} elseif ($RowUserPoints > $CurrentLevel AND
				  $NoobProt['config_value'] == 1 AND
				  $NoobTime['config_value'] * 1000 > $CurrentPoints) {
			$Systemtatus2 = $lang['strong_player_shortcut'];
			$Systemtatus  = "<span class=\"strong\">";
		} else {
			$Systemtatus2 = "";
			$Systemtatus  = "";
		}
		$Systemtatus4 = $User2Points['total_rank'];
		if ($Systemtatus2 != '') {
			$Systemtatus6 = "<font color=\"white\">(</font>";
			$Systemtatus7 = "<font color=\"white\">)</font>";
		}
		if ($Systemtatus2 == '') {
			$Systemtatus6 = "";
			$Systemtatus7 = "";
		}
		$admin = "";
		if ($GalaxyRowUser['authlevel'] == LEVEL_ADMIN) {
			$admin = "<font color=\"red\"><blink>A</blink></font>";
		} else if ($GalaxyRowUser['authlevel'] == LEVEL_OPERATOR) {
			$admin = "<font color=\"lime\"><blink>O</blink></font>";
		} else if ($GalaxyRowUser['authlevel'] == LEVEL_MODERATOR) {
			$admin = "<font color=\"skyblue\"><blink>M</blink></font>";
		}
		$Systemtart = $User2Points['total_rank'];
		if (strlen($Systemtart) < 3) {
			$Systemtart = 1;
		} else {
			$Systemtart = (floor( $User2Points['total_rank'] / 100 ) * 100) + 1;
		}
		$Result .= "<a style=\"cursor: pointer;\"";
		$Result .= " onmouseover='return overlib(\"";
		$Result .= "<table width=190>";
		$Result .= "<tr>";
		$Result .= "<td class=c colspan=2>".$lang['Player']." ".$GalaxyRowUser['username']." ".$lang['Place']." ".$Systemtatus4."</td>";
		$Result .= "</tr><tr>";
		if ($GalaxyRowUser['id'] != $user['id']) {
			$Result .= "<td><a href=messages.php?mode=write&id=".$GalaxyRowUser['id'].">".$lang['gl_sendmess']."</a></td>";
			$Result .= "</tr><tr>";
			$Result .= "<td><a href=buddy.php?a=2&u=".$GalaxyRowUser['id'].">".$lang['gl_buddyreq']."</a></td>";
			$Result .= "</tr><tr>";
		}
		$Result .= "<td><a href=stat.php?who=player&start=".$Systemtart.">".$lang['gl_stats']."</a></td>";
		$Result .= "</tr>";
		$Result .= "</table>\"";
		$Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );'";
		$Result .= " onmouseout='return nd();'>";
		$Result .= $Systemtatus;
		$Result .= $GalaxyRowUser["username"]."</span>";
		$Result .= $Systemtatus6;
		$Result .= $Systemtatus;
		$Result .= $Systemtatus2;
		$Result .= $Systemtatus7." ".$admin;
		$Result .= "</span></a>";
	}
	$Result .= "</th>";

	return $Result;
}

?>