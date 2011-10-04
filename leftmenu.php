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

function ShowLeftMenu ( $Level , $Template = 'left_menu') {
	global $lang, $user, $dpath, $gameConfig;

	includeLang('leftmenu');

	$MenuTPL                  = gettemplate( $Template );
	$InfoTPL                  = gettemplate( 'serv_infos' );
	$parse                    = $lang;
	$parse['lm_tx_serv']      = $gameConfig['resource_multiplier'];
	$parse['lm_tx_game']      = $gameConfig['game_speed'] / 2500;
	$parse['lm_tx_fleet']     = $gameConfig['fleet_speed'] / 2500;
	$parse['lm_tx_queue']     = MAX_FLEET_OR_DEFS_PER_ROW;
	$SubFrame                 = parsetemplate( $InfoTPL, $parse );
	$parse['server_info']     = $SubFrame;
	$parse['WootookRelease']    = VERSION;
	$parse['dpath']           = $dpath;
	$parse['forum_url']       = $gameConfig['forum_url'];
	$parse['mf']              = "Hauptframe";
	$rank                     = doquery("SELECT `total_rank` FROM {{table}} WHERE `stat_code` = '1' AND `stat_type` = '1' AND `id_owner` = '". $user['id'] ."';",'statpoints',true);
	$parse['user_rank']       = $rank['total_rank'];
	if ($Level > 0) {
		$parse['ADMIN_LINK']  = "
		<tr>
			<td colspan=\"2\"><div><a href=\"admin/leftmenu.php\"><font color=\"lime\">".$lang['user_level'][$Level]."</font></a></div></td>
		</tr>";
	} else {
		$parse['ADMIN_LINK']  = "";
	}
	//Lien suppl�mentaire d�termin� dans le panel admin
	if ($gameConfig['link_enable'] == 1) {
		$parse['added_link']  = "
		<tr>
			<td colspan=\"2\"><div><a href=\"".$gameConfig['link_url']."\" target=\"_blank\">".stripslashes($gameConfig['link_name'])."</a></div></td>
		</tr>";
	} else {
		$parse['added_link']  = "";
	}

	//Maintenant on v�rifie si les annonces sont activ�es ou non
	if ($gameConfig['enable_announces'] == 1) {
		$parse['announce_link']  = "
		<tr>
			<td colspan=\"2\"><div><a href=\"annonce.php\" target=\"Hauptframe\">Annonces</a></div></td>
		</tr>";
	} else {
		$parse['announce_link']  = "";
	}

		//Maintenant le marchand
	if ($gameConfig['enable_marchand'] == 1) {
		$parse['marchand_link']  = "
			<td colspan=\"2\"><div><a href=\"marchand.php\" target=\"Hauptframe\">Marchand</a></div></td>";
	} else {
		$parse['marchand_link']  = "";
	}
			//Maintenant les notes
	if ($gameConfig['enable_notes'] == 1) {
		$parse['notes_link']  = "
		<tr>
			<td colspan=\"2\"><div><a href=\"#\" onClick=\"f(\'notes.php\', \'Report\');\" accesskey=\"n\">Notes</a></div></td>
		</tr>";
	} else {
		$parse['notes_link']  = "";
	}
	$parse['servername']   = $gameConfig['game_name'];
	$Menu                  = parsetemplate( $MenuTPL, $parse);

	return $Menu;
}
	$Menu = ShowLeftMenu ( $user['authlevel'] );
	display ( $Menu, "Menu", '', false );

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Passage en fonction pour Wootook version future
// 1.1 - Modification pour gestion Admin / Game OP / Modo
?>
