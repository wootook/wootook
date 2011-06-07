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
 * @param unknown_type $CurrentPlanet
 */
function ElementBuildListBox ( $CurrentUser, $CurrentPlanet ) {
	global $lang, $pricelist;

//	// Array del b_hangar_id
//	$ElementQueue = explode(';', $CurrentPlanet['b_hangar_id']);
//	$NbrePerType  = "";
//	$NamePerType  = "";
//	$TimePerType  = "";
//
//	foreach($ElementQueue as $ElementLine => $Element) {
//		if ($Element != '') {
//			$Element = explode(',', $Element);
//			$ElementTime  = GetBuildingTime( $CurrentUser, $CurrentPlanet, $Element[0] );
//			$QueueTime   += $ElementTime * $Element[1];
//			$TimePerType .= "".$ElementTime.",";
//			$NamePerType .= "'". html_entity_decode($lang['tech'][$Element[0]]) ."',";
//			$NbrePerType .= "".$Element[1].",";
//		}
//	}
//
//	$parse = $lang;
//	$parse['a'] = $NbrePerType;
//	$parse['b'] = $NamePerType;
//	$parse['c'] = $TimePerType;
//	$parse['b_hangar_id_plus'] = $CurrentPlanet['b_hangar'];
//
//	$parse['pretty_time_b_hangar'] = pretty_time($QueueTime - $CurrentPlanet['b_hangar']);
//
//	$text .= parsetemplate(gettemplate('buildings_script'), $parse);
//
//	return $text;
}

?>