<?php
/**
 * Tis file is part of XNova:Legacies
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
define('IN_ADMIN', true);
require_once dirname(dirname(__FILE__)) .'/common.php';

	if (in_array($user['authlevel'], array(LEVEL_ADMIN, LEVEL_OPERATOR, LEVEL_MODERATOR))) {
		includeLang('admin');

		if ($_GET['cmd'] == 'sort') {
			$TypeSort = $_GET['type'];
		} else {
			$TypeSort = "id";
		}

		$PageTPL  = gettemplate('admin/overview_body');
		$RowsTPL  = gettemplate('admin/overview_rows');

		$parse                      = $lang;
		$parse['dpath']             = $dpath;
		$parse['mf']                = $mf;
		$parse['adm_ov_data_yourv'] = colorRed(VERSION);

		$Last15Mins = doquery("SELECT * FROM {{table}} WHERE `onlinetime` >= '". (time() - 15 * 60) ."' ORDER BY `". $TypeSort ."` ASC;", 'users');
		$Count      = 0;
		$Color      = "lime";
		while ( $TheUser = mysql_fetch_array($Last15Mins) ) {
			if ($PrevIP != "") {
				if ($PrevIP == $TheUser['user_lastip']) {
					$Color = "red";
				} else {
					$Color = "lime";
				}
			}

			$UserPoints = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $TheUser['id'] . "';", 'statpoints', true);
			$Bloc['dpath']               = $dpath;
			$Bloc['adm_ov_altpm']        = $lang['adm_ov_altpm'];
			$Bloc['adm_ov_wrtpm']        = $lang['adm_ov_wrtpm'];
			$Bloc['adm_ov_data_id']      = $TheUser['id'];
			$Bloc['adm_ov_data_name']    = $TheUser['username'];
			$Bloc['adm_ov_data_agen']    = $TheUser['user_agent'];
			$Bloc['current_page']    = $TheUser['current_page'];
			$Bloc['usr_s_id']    = $TheUser['id'];

			$Bloc['adm_ov_data_clip']    = $Color;
			$Bloc['adm_ov_data_adip']    = $TheUser['user_lastip'];
			$Bloc['adm_ov_data_ally']    = $TheUser['ally_name'];
			$Bloc['adm_ov_data_point']   = pretty_number ( $UserPoints['total_points'] );
			$Bloc['adm_ov_data_activ']   = pretty_time ( time() - $TheUser['onlinetime'] );
			$Bloc['adm_ov_data_pict']    = "m.gif";
			$PrevIP                      = $TheUser['user_lastip'];

			//Tweaks vue g�n�rale
						$Bloc['usr_email']    = $TheUser['email'];
									$Bloc['usr_xp_raid']    = $TheUser['xpraid'];
									$Bloc['usr_xp_min']    = $TheUser['xpminier'];

									if ($TheUser['urlaubs_modus'] == 1) {
											$Bloc['state_vacancy']  = "<img src=\"../images/true.png\" >";
									} else {
											$Bloc['state_vacancy']  = "<img src=\"../images/false.png\">";
									}

									if ($TheUser['bana'] == 1) {
											$Bloc['is_banned']  = "<img src=\"../images/banned.png\" >";
									} else {
											$Bloc['is_banned']  = $lang['is_banned_lang'];
									}
									$Bloc['usr_planet_gal']    = $TheUser['galaxy'];
									$Bloc['usr_planet_sys']    = $TheUser['system'];
									$Bloc['usr_planet_pos']    = $TheUser['planet'];


			$parse['adm_ov_data_table'] .= parsetemplate( $RowsTPL, $Bloc );
			$Count++;
		}

		$parse['adm_ov_data_count']  = $Count;
		$Page = parsetemplate($PageTPL, $parse);

		display ( $Page, $lang['sys_overview'], false, '', true);
	} else {
		AdminMessage ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>
