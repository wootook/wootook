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

/**
 *
 * @deprecated
 * @param unknown_type $Galaxy
 * @param unknown_type $System
 */
function ShowGalaxyRows ($Galaxy, $System) {
	global $lang, $planetcount, $CurrentRC, $dpath, $user;

	$Result = "";
	for ($Planet = 1; $Planet <= Wootook::getGameConfig('engine/universe/positions'); $Planet++) {

		$GalaxyRowPlanet = array();
		$GalaxyRowMoon = array();
		$GalaxyRowPlayer = array();
		$GalaxyRowAlly = array();

		$GalaxyRow = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '".$Galaxy."' AND `system` = '".$System."' AND `planet` = '".$Planet."';", 'galaxy', true);

		$Result .= "\n";
		$Result .= "<tr>"; // Depart de ligne
		if ($GalaxyRow) {
			// Il existe des choses sur cette ligne de planete
			if ($GalaxyRow["id_planet"] != 0) {
				$GalaxyRowPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". $GalaxyRow["id_planet"] ."';", 'planets', true);

				if ($GalaxyRowPlanet['destruyed'] != 0 AND
					$GalaxyRowPlanet['id_owner'] != '' AND
					$GalaxyRow["id_planet"] != '') {
					CheckAbandonPlanetState ($GalaxyRowPlanet);
				} else {
					$planetcount++;
					$GalaxyRowPlayer = doquery("SELECT * FROM {{table}} WHERE `id` = '". $GalaxyRowPlanet["id_owner"] ."';", 'users', true);
				}

				if ($GalaxyRow["id_luna"] != 0) {
					$GalaxyRowMoon   = doquery("SELECT * FROM {{table}} WHERE `id` = '". $GalaxyRow["id_luna"] ."';", 'lunas', true);
					if ($GalaxyRowMoon["destruyed"] != 0) {
						CheckAbandonMoonState ($GalaxyRowMoon);
					}
				}
				$GalaxyRowPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". $GalaxyRow["id_planet"] ."';", 'planets', true);
				if ($GalaxyRowPlanet['id_owner'] <> 0) {
					$GalaxyRowUser     = doquery("SELECT * FROM {{table}} WHERE `id` = '". $GalaxyRowPlanet['id_owner'] ."';", 'users', true);
				} else {
					$GalaxyRowUser     = array();
				}
			}
		}
		$Result .= "\n";
		$Result .= GalaxyRowPos        ( $Planet, $GalaxyRow );
		$Result .= "\n";
		$Result .= GalaxyRowPlanet     ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 1 );
		$Result .= "\n";
		$Result .= GalaxyRowPlanetName ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 1 );
		$Result .= "\n";
		$Result .= GalaxyRowMoon       ( $GalaxyRow, $GalaxyRowMoon  , $GalaxyRowPlayer, $Galaxy, $System, $Planet, 3 );
		$Result .= "\n";
		$Result .= GalaxyRowDebris     ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 2 );
		$Result .= "\n";
		$Result .= GalaxyRowUser       ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 0 );
		$Result .= "\n";
		$Result .= GalaxyRowAlly       ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 0 );
		$Result .= "\n";
		$Result .= GalaxyRowActions    ( $GalaxyRow, $GalaxyRowPlanet, $GalaxyRowPlayer, $Galaxy, $System, $Planet, 0 );
		$Result .= "\n";
		$Result .= "</tr>";
	}

	return $Result;
}

?>