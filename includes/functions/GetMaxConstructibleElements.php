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
 * @param unknown_type $Element
 * @param unknown_type $Ressources
 */
function GetMaxConstructibleElements ($Element, $Ressources) {
	global $pricelist;
	// On test les 4 Type de ressource pour voir si au moins on sait en construire 1
	if ($pricelist[$Element]['metal'] != 0) {
		$ResType_1_Needed = $pricelist[$Element]['metal'];
		$Buildable        = floor($Ressources["metal"] / $ResType_1_Needed);
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['crystal'] != 0) {
		$ResType_2_Needed = $pricelist[$Element]['crystal'];
		$Buildable        = floor($Ressources["crystal"] / $ResType_2_Needed);
	}
	if (!isset($MaxElements)) {
		$MaxElements      = $Buildable;
	} elseif ($MaxElements > $Buildable) {
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['deuterium'] != 0) {
		$ResType_3_Needed = $pricelist[$Element]['deuterium'];
		$Buildable        = floor($Ressources["deuterium"] / $ResType_3_Needed);
	}
	if (!isset($MaxElements)) {
		$MaxElements      = $Buildable;
	} elseif ($MaxElements > $Buildable) {
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['energy'] != 0) {
		$ResType_4_Needed = $pricelist[$Element]['energy'];
		$Buildable        = floor($Ressources["energy_max"] / $ResType_4_Needed);
	}
	if ($Buildable < 1) {
		$MaxElements      = 0;
	}

	return $MaxElements;
}
// Verion History
// - 1.0 Version initiale (creation)
// - 1.1 Correction bug ressources n�gatives ...
// - 1.2 Correction bug quand pas de m�tal
?>