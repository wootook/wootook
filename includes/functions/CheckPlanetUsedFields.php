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
 * @param unknown_type $planet
 */
function CheckPlanetUsedFields ( &$planet ) {
	global $resource;

	// Tous les batiments
	$cfc  = $planet[$resource[1]]  + $planet[$resource[2]]  + $planet[$resource[3]] ;
	$cfc += $planet[$resource[4]]  + $planet[$resource[12]] + $planet[$resource[14]];
	$cfc += $planet[$resource[15]] + $planet[$resource[21]] + $planet[$resource[22]];
	$cfc += $planet[$resource[23]] + $planet[$resource[24]] + $planet[$resource[31]];
	$cfc += $planet[$resource[33]] + $planet[$resource[34]] + $planet[$resource[44]];

	// Si on se trouve sur une lune ... Y a des choses a ajouter aussi
	if ($planet['planet_type'] == '3') {
		$cfc += $planet[$resource[41]] + $planet[$resource[42]] + $planet[$resource[43]];
	}

	// Mise a jour du nombre de case dans la BDD si incorrect
	if ($planet['field_current'] != $cfc) {
		$planet['field_current'] = $cfc;
		doquery("UPDATE {{table}} SET field_current=$cfc WHERE id={$planet['id']}", 'planets');
	}
}

?>