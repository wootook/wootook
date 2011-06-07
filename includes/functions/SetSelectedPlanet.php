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
 * @param unknown_type $CurrentUser
 */
function SetSelectedPlanet ( &$CurrentUser )
{
    if (!isset($_GET['cp']) || !isset($_GET['re'])) {
        return;
    }
	$SelectPlanet  = $_GET['cp'];
	$RestorePlanet = $_GET['re'];

	if (isset($SelectPlanet)      &&
		is_numeric($SelectPlanet) &&
		isset($RestorePlanet)     &&
		$RestorePlanet == 0) {
		$IsPlanetMine   = doquery("SELECT `id` FROM {{table}} WHERE `id` = '". $SelectPlanet ."' AND `id_owner` = '". $CurrentUser['id'] ."';", 'planets', true);
		if ($IsPlanetMine) {
			// Ouaip elle est a moi ... Donc ... on met la met comme planete courrante
			$CurrentUser['current_planet'] = $SelectPlanet;
			// Puis tant qu'a faire ... On l'enregistre aussi sait on jamais
			doquery("UPDATE {{table}} SET `current_planet` = '". $SelectPlanet ."' WHERE `id` = '".$CurrentUser['id']."';", 'users');
		}
	}
}

?>
