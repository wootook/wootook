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
 * @deprecated
 * @param unknown_type $CurrentPlanet
 * @param unknown_type $CurrentUser
 */
function CheckPlanetBuildingQueue ( &$CurrentPlanet, &$CurrentUser ) {
	global $lang, $resource;

	// Table des batiments donnant droit de l'experience minier
	$XPBuildings  = array(  1,  2,  3, 22, 23, 24);

	$RetValue     = false;
	if ($CurrentPlanet['b_building_id'] != 0) {
		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		if ($CurrentQueue != 0) {
			$QueueArray    = explode ( ";", $CurrentQueue );
			$ActualCount   = count ( $QueueArray );
		}

		$BuildArray   = explode (",", $QueueArray[0]);
		$BuildEndTime = floor($BuildArray[3]);
		$BuildMode    = $BuildArray[4];
		$Element      = $BuildArray[0];
		array_shift ( $QueueArray );

		if ($BuildMode == 'destroy') {
			$ForDestroy = true;
		} else {
			$ForDestroy = false;
		}

		if ($BuildEndTime <= time()) {
			// Mise a jours des points
			$Needed                        = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
			$Units                         = $Needed['metal'] + $Needed['crystal'] + $Needed['deuterium'];
			if ($ForDestroy == false) {
				// Mise a jours de l'XP Minier
				if (in_array($Element, $XPBuildings)) {
					$AjoutXP                        = $Units / 1000;
					$CurrentUser['xpminier']       += $AjoutXP;
				}
			} else {
				// Mise a jours de l'XP Minier
				if (in_array($Element, $XPBuildings)) {
					$AjoutXP                        = ($Units * 3) / 1000;
					$CurrentUser['xpminier']       -= $AjoutXP;
				}
			}

			$current = intval($CurrentPlanet['field_current']);
			$max     = intval($CurrentPlanet['field_max']);
			// Pour une lune
			if ($CurrentPlanet['planet_type'] == 3) {
				if ($Element == 41) {
					// Base Lunaire
					$current += 1;
					$max     += FIELDS_BY_MOONBASIS_LEVEL;
					$CurrentPlanet[$resource[$Element]]++;
				} elseif ($Element != 0) {
					if ($ForDestroy == false) {
						$current += 1;
						$CurrentPlanet[$resource[$Element]]++;
					} else {
						$current -= 1;
						$CurrentPlanet[$resource[$Element]]--;
					}
				}
			} elseif ($CurrentPlanet['planet_type'] == 1) {
				if ($ForDestroy == false) {
					$current += 1;
					$CurrentPlanet[$resource[$Element]]++;
				} else {
					$current -= 1;
					$CurrentPlanet[$resource[$Element]]--;
				}
			}
			if (count ( $QueueArray ) == 0) {
				$NewQueue = 0;
			} else {
				$NewQueue = implode (";", $QueueArray );
			}
			$CurrentPlanet['b_building']    = 0;
			$CurrentPlanet['b_building_id'] = $NewQueue;
			$CurrentPlanet['field_current'] = $current;
			$CurrentPlanet['field_max']     = $max;

			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`".$resource[$Element]."` = '".$CurrentPlanet[$resource[$Element]]."', ";
			// Mise a 0 de l'heure de fin de construction ...
			// Ca va activer la mise en place du batiment suivant de la queue
			$QryUpdatePlanet .= "`b_building` = '". $CurrentPlanet['b_building'] ."' , ";
			$QryUpdatePlanet .= "`b_building_id` = '". $CurrentPlanet['b_building_id'] ."' , ";
			$QryUpdatePlanet .= "`field_current` = '" . $CurrentPlanet['field_current'] . "', ";
			$QryUpdatePlanet .= "`field_max` = '" . $CurrentPlanet['field_max'] . "' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '" . $CurrentPlanet['id'] . "';";
			doquery( $QryUpdatePlanet, 'planets');

			$QryUpdateUser    = "UPDATE {{table}} SET ";
			$QryUpdateUser   .= "`xpminier` = '".$CurrentUser['xpminier']."' ";
			$QryUpdateUser   .= "WHERE ";
			$QryUpdateUser   .= "`id` = '" . $CurrentUser['id'] . "';";
			doquery( $QryUpdateUser, 'users');

			$RetValue = true;
		} else {
			$RetValue = false;
		}
	} else {
		$CurrentPlanet['b_building']    = 0;
		$CurrentPlanet['b_building_id'] = 0;

		$QryUpdatePlanet  = "UPDATE {{table}} SET ";
		$QryUpdatePlanet .= "`b_building` = '". $CurrentPlanet['b_building'] ."' , ";
		$QryUpdatePlanet .= "`b_building_id` = '". $CurrentPlanet['b_building_id'] ."' ";
		$QryUpdatePlanet .= "WHERE ";
		$QryUpdatePlanet .= "`id` = '" . $CurrentPlanet['id'] . "';";
		doquery( $QryUpdatePlanet, 'planets');

		$RetValue = false;
	}

	return $RetValue;
}

?>