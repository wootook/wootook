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

function ShowOfficierPage ( &$CurrentUser ) {
	global $lang, $resource, $reslist, $_GET;

	includeLang('officier');

	// Vérification que le joueur n'a pas un nombre de points négatif
	if ($CurrentUser['rpg_points'] < 0) {
		doquery("UPDATE {{table}} SET `rpg_points` = '0' WHERE `id` = '". $CurrentUser['id'] ."';", 'users');
	}

	// Si recrutement d'un officier
	if ($_GET['mode'] == 2) {
		if ($CurrentUser['rpg_points'] > 0) {
			$Selected    = $_GET['offi'];
			if ( in_array($Selected, $reslist['officier']) ) {
				$Result = IsOfficierAccessible ( $CurrentUser, $Selected );
				if ( $Result == 1 ) {
					$CurrentUser[$resource[$Selected]] += 1;
					$CurrentUser['rpg_points']         -= 1;
					if       ($Selected == 610) {
						$CurrentUser['spy_tech']      += 5;
					} elseif ($Selected == 611) {
						$CurrentUser['computer_tech'] += 3;
					}

					$QryUpdateUser  = "UPDATE {{table}} SET ";
					$QryUpdateUser .= "`rpg_points` = '". $CurrentUser['rpg_points'] ."', ";
					$QryUpdateUser .= "`spy_tech` = '". $CurrentUser['spy_tech'] ."', ";
					$QryUpdateUser .= "`computer_tech` = '". $CurrentUser['computer_tech'] ."', ";
					$QryUpdateUser .= "`".$resource[$Selected]."` = '". $CurrentUser[$resource[$Selected]] ."' ";
					$QryUpdateUser .= "WHERE ";
					$QryUpdateUser .= "`id` = '". $CurrentUser['id'] ."';";
					doquery( $QryUpdateUser, 'users' );
					$Message = $lang['OffiRecrute'];
				} elseif ( $Result == -1 ) {
					$Message = $lang['Maxlvl'];
				} elseif ( $Result == 0 ) {
					$Message = $lang['Noob'];
				}
			}
		} else {
			$Message = $lang['NoPoints'];
		}
		$MessTPL        = gettemplate('message_body');
		$parse['title'] = $lang['Officier'];
		$parse['mes']   = $Message;

		$page           = parsetemplate( $MessTPL, $parse);
	} else {
		// Pas de recrutement d'officier
		$PageTPL = gettemplate('officier_body');
		$RowsTPL = gettemplate('officier_rows');
		$parse['off_points']   = $lang['off_points'];
		$parse['alv_points']   = $CurrentUser['rpg_points'];
		$parse['disp_off_tbl'] = "";
		for ( $Officier = 601; $Officier <= 615; $Officier++ ) {
			$Result = IsOfficierAccessible ( $CurrentUser, $Officier );
			if ( $Result != 0 ) {
				$bloc['off_id']       = $Officier;
				$bloc['off_tx_lvl']   = $lang['off_tx_lvl'];
				$bloc['off_lvl']      = $CurrentUser[$resource[$Officier]];
				$bloc['off_desc']     = $lang['Desc'][$Officier];
				if ($Result == 1) {
					$bloc['off_link'] = "<a href=\"officier.php?mode=2&offi=".$Officier."\"><font color=\"#00ff00\">". $lang['link'][$Officier]."</font>";
				} else {
					$bloc['off_link'] = $lang['Maxlvl'];
				}
				$parse['disp_off_tbl'] .= parsetemplate( $RowsTPL, $bloc );
			}
		}
		$page           = parsetemplate( $PageTPL, $parse);
	}

	return $page;
}

	$page = ShowOfficierPage ( $user );
	display($page, $lang['officier']);

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Version originelle (Tom1991)
// 1.1 - Réécriture Chlorel pour integration complete dans XNova
?>