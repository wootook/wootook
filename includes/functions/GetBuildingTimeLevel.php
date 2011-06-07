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

/**
 *
 * @deprecated
 * @param unknown_type $user
 * @param unknown_type $planet
 * @param unknown_type $Element
 * @param unknown_type $level
 */
function GetBuildingTimeLevel ($user, $planet, $Element, $level) {
	global $pricelist, $resource, $reslist, $gameConfig;

	$level -= 1;

	if       (in_array($Element, $reslist['build'])) {
		// Pour un batiment ...
		$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
		$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
		$time         = ((($cost_crystal) + ($cost_metal)) / $gameConfig['game_speed']) * (1 / ($planet[$resource['14']] + 1)) * pow(0.5, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - (($user['rpg_constructeur']) * 0.1)));
	} elseif (in_array($Element, $reslist['tech'])) {
		// Pour une recherche
		$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
		$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
		$intergal_lab = $user[$resource[123]];
		if       ( $intergal_lab < "1" ) {
			$lablevel = $planet[$resource['31']];
		} elseif ( $intergal_lab >= "1" ) {
			$empire = doquery("SELECT * FROM {{table}} WHERE id_owner='". $user[id] ."';", 'planets');
			$NbLabs = 0;
			while ($colonie = mysql_fetch_array($empire)) {
				$techlevel[$NbLabs] = $colonie[$resource['31']];
				$NbLabs++;
			}
			if ($intergal_lab >= "1") {
				$lablevel = 0;
				for ($lab = 1; $lab <= $intergal_lab; $lab++) {
					asort($techlevel);
					$lablevel += $techlevel[$lab - 1];
				}
			}
		}
		$time         = (($cost_metal + $cost_crystal) / $gameConfig['game_speed']) / (($lablevel + 1) * 2);
		$time         = floor(($time * 60 * 60) * (1 - (($user['rpg_scientifique']) * 0.1)));
	} elseif (in_array($Element, $reslist['defense'])) {
		// Pour les defenses ou la flotte 'tarif fixe' dur�e adapt�e a u niveau nanite et usine robot
		$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / $gameConfig['game_speed']) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - (($user['rpg_defenseur'])   * 0.375)));
	} elseif (in_array($Element, $reslist['fleet'])) {
		$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / $gameConfig['game_speed']) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - (($user['rpg_technocrate']) * 0.05)));
	}


	return $time;
}

?>