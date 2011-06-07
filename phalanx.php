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

	includeLang('overview');
	includeLang('phalanx');

	$PageTPL     = gettemplate('phalanx_body');
	$PhalanxMoon = doquery ("SELECT * FROM {{table}} WHERE `id` = '". $user['current_planet'] ."';", 'planets', true);

	if ( $PhalanxMoon['planet_type'] == 3) {
		$parse                     = $lang;

		$parse['phl_pl_galaxy']    = $PhalanxMoon['galaxy'];
		$parse['phl_pl_system']    = $PhalanxMoon['system'];
		$parse['phl_pl_place']     = $PhalanxMoon['planet'];
		$parse['phl_pl_name']      = $user['username'];

		if ( $PhalanxMoon['deuterium'] > 10000 ) {
			doquery ("UPDATE {{table}} SET `deuterium` = `deuterium` - '10000' WHERE `id` = '". $user['current_planet'] ."';", 'planets');
			$parse['phl_er_deuter'] = "";
			$DoScan                 = true;
		} else {
			$parse['phl_er_deuter'] = $lang['phl_no_deuter'];
			$DoScan                 = false;
		}

		if ($DoScan == true) {
			$Galaxy  = $_GET["galaxy"];
			$System  = $_GET["system"];
			$Planet  = $_GET["planet"];
			$PlType  = $_GET["planettype"];

			$TargetInfo = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '". $Galaxy ."' AND `system` = '". $System ."' AND `planet` = '". $Planet ."' AND `planet_type` = '". $PlType ."';", 'planets', true);
			$TargetName = $TargetInfo['name'];

			$QryLookFleets  = "SELECT * ";
			$QryLookFleets .= "FROM {{table}} ";
			$QryLookFleets .= "WHERE ( ( ";
			$QryLookFleets .= "`fleet_start_galaxy` = '". $Galaxy ."' AND ";
			$QryLookFleets .= "`fleet_start_system` = '". $System ."' AND ";
			$QryLookFleets .= "`fleet_start_planet` = '". $Planet ."' AND ";
			$QryLookFleets .= "`fleet_start_type` = '". $PlType ."' ";
			$QryLookFleets .= ") OR ( ";
			$QryLookFleets .= "`fleet_end_galaxy` = '". $Galaxy ."' AND ";
			$QryLookFleets .= "`fleet_end_system` = '". $System ."' AND ";
			$QryLookFleets .= "`fleet_end_planet` = '". $Planet ."' AND ";
			$QryLookFleets .= "`fleet_end_type` = '". $PlType ."' ";
			$QryLookFleets .= ") ) ";
			$QryLookFleets .= "ORDER BY `fleet_start_time`;";

			$FleetToTarget  = doquery( $QryLookFleets, 'fleets' );

			if (mysql_num_rows($FleetToTarget) <> 0 ) {
				while ($FleetRow = mysql_fetch_array($FleetToTarget)) {
					$Record++;

					// Discrimination de l'heure
					$StartTime   = $FleetRow['fleet_start_time'];
					$StayTime    = $FleetRow['fleet_end_stay'];
					$EndTime     = $FleetRow['fleet_end_time'];

					// Flotte hostile ? ou pas ??
					if ($FleetRow['fleet_owner'] == $TargetInfo['id_owner']) {
						$FleetType = true;
					} else {
						$FleetType = false;
					}

					// Masquage des ressources transport�es
					$FleetRow['fleet_resource_metal']     = 0;
					$FleetRow['fleet_resource_crystal']   = 0;
					$FleetRow['fleet_resource_deuterium'] = 0;

					$Label = "fs";
					if ($StartTime > time()) {
						$fpage[$StartTime] = BuildFleetEventTable ( $FleetRow, 0, $FleetType, $Label, $Record );
					}

					if ($FleetRow['fleet_mission'] <> 4) {
						$Label = "ft";
						if ($StayTime > time()) {
							$fpage[$StayTime] = BuildFleetEventTable ( $FleetRow, 1, $FleetType, $Label, $Record );
						}

						if ($FleetType == true) {
							// On n'affiche les flottes en retour que pour les flottes du possesseur de la planete
							$Label = "fe";
							if ($EndTime > time()) {
								$fpage[$EndTime]  = BuildFleetEventTable ( $FleetRow, 2, $FleetType, $Label, $Record );
							}
						}
					}
				} // End While
			}

			if (count($fpage) > 0) {
				ksort($fpage);
				foreach ($fpage as $FleetTime => $FleetContent) {
					$Fleets .= $FleetContent ."\n";
				}
			}
		}

		$parse['phl_fleets_table'] = $Fleets;
		$page = parsetemplate( $PageTPL, $parse );
	}

	display ($page, $lang['sys_phalanx'], false, '', false);

?>
