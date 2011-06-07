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

		$QrySelectPlanet  = "SELECT `id`, `id_owner`, `b_hangar`, `b_hangar_id` ";
		$QrySelectPlanet .= "FROM {{table}} ";
		$QrySelectPlanet .= "WHERE ";
		$QrySelectPlanet .= "`b_hangar_id` != '0';";
		$AffectedPlanets  = doquery ($QrySelectPlanet, 'planets');
		$DeletedQueues    = 0;
		while ( $ActualPlanet = mysql_fetch_assoc($AffectedPlanets) ) {
			$HangarQueue = explode (";", $ActualPlanet['b_hangar_id']);
			$bDelQueue   = false;
			if (count($HangarQueue)) {
				for ( $Queue = 0; $Queue < count($HangarQueue); $Queue++) {
					$InQueue = explode (",", $HangarQueue[$Queue]);
					if ($InQueue[1] > MAX_FLEET_OR_DEFS_PER_ROW) {
						$bDelQueue = true;
					}
				}
			}
			if ($bDelQueue) {
				$QryUpdatePlanet  = "UPDATE {{table}} ";
				$QryUpdatePlanet .= "SET ";
				$QryUpdatePlanet .= "`b_hangar` = '0', ";
				$QryUpdatePlanet .= "`b_hangar_id` = '0' ";
				$QryUpdatePlanet .= "WHERE ";
				$QryUpdatePlanet .= "`id` = '".$ActualPlanet['id']."';";
				doquery ($QryUpdatePlanet, 'planets');
				$DeletedQueues += 1;
			}
		}
		if ($DeletedQueues > 0) {
			$QuitMessage = $lang['adm_cleaned']." ". $DeletedQueues;
		} else {
			$QuitMessage = $lang['adm_done'];
		}

		AdminMessage ($QuitMessage, $lang['adm_cleaner_title']);

	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>