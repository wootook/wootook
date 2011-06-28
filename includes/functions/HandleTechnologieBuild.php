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
 * @param unknown_type $CurrentPlanet
 * @param unknown_type $CurrentUser
 */
function HandleTechnologieBuild ( &$CurrentPlanet, &$CurrentUser ) {
	global $resource;
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

	if ($CurrentUser['b_tech_planet'] != 0) {
		// Y a une technologie en cours sur une de mes colonies
		if ($CurrentUser['b_tech_planet'] != $CurrentPlanet['id']) {
			// Et ce n'est pas sur celle ci !!
			$WorkingPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". $CurrentUser['b_tech_planet'] ."';", 'planets', true);
		}

		if ($WorkingPlanet) {
			$ThePlanet = $WorkingPlanet;
		} else {
			$ThePlanet = $CurrentPlanet;
		}

		if ($ThePlanet['b_tech']    <= time() &&
			$ThePlanet['b_tech_id'] != 0) {
			// La recherche en cours est terminée ...
			$CurrentUser[$resource[$ThePlanet['b_tech_id']]]++;
			// Mise a jour de la planete sur laquelle la technologie a été recherchée
			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`b_tech` = '0', ";
			$QryUpdatePlanet .= "`b_tech_id` = '0' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '". $ThePlanet['id'] ."';";
			doquery( $QryUpdatePlanet, 'planets');

			// Mes a jour de la techno sur l'enregistrement Utilisateur
			// Et tant qu'a faire des stats points
			$QryUpdateUser    = "UPDATE {{table}} SET ";
			$QryUpdateUser   .= "`".$resource[$ThePlanet['b_tech_id']]."` = '". $CurrentUser[$resource[$ThePlanet['b_tech_id']]] ."', ";
			$QryUpdateUser   .= "`b_tech_planet` = '0' ";
			$QryUpdateUser   .= "WHERE ";
			$QryUpdateUser   .= "`id` = '". $CurrentUser['id'] ."';";
			doquery( $QryUpdateUser, 'users');
			$ThePlanet["b_tech_id"] = 0;
			if (isset($WorkingPlanet)) {
				$WorkingPlanet = $ThePlanet;
			} else {
				$CurrentPlanet = $ThePlanet;
			}
			$Result['WorkOn'] = "";
			$Result['OnWork'] = false;

		} elseif ($ThePlanet["b_tech_id"] == 0) {
			// Il n'y a rien a l'ouest ...
			// Pas de Technologie en cours devait y avoir un bug lors de la derniere connexion
			// On met l'enregistrement informant d'une techno en cours de recherche a jours
			doquery("UPDATE {{table}} SET `b_tech_planet` = '0'  WHERE `id` = '". $CurrentUser['id'] ."';", 'users');
			$Result['WorkOn'] = "";
			$Result['OnWork'] = false;

		} else {
			// Bin on bosse toujours ici ... Alors ne nous derangez pas !!!
			$Result['WorkOn'] = $ThePlanet;
			$Result['OnWork'] = true;
		}
	} else {
		$Result['WorkOn'] = "";
		$Result['OnWork'] = false;
	}

	return $Result;
}

// History revision
// 1.0 - mise en forme modularisation version initiale
// 1.1 - Correction retour de fonction (retourne un tableau a la place d'un flag)
?>