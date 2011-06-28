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
function ElementBuildListQueue ( $CurrentUser, $CurrentPlanet ) {
// Jamais appelé pour le moment donc totalement modifiable !

    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);
/*
alter table `ogame`.`game_planets`
change `name` `name` varchar (255) NULL COLLATE latin1_general_ci,
change `b_building_id` `b_building_id` text NULL COLLATE latin1_general_ci,
change `b_tech_id` `b_tech_id` text NULL COLLATE latin1_general_ci,
change `b_hangar_id` `b_hangar_id` text NULL COLLATE latin1_general_ci,
change `image` `image` varchar (32) DEFAULT 'normaltempplanet01' NOT NULL COLLATE latin1_general_ci,
change `b_building_queue` `b_building_queue` text NULL COLLATE latin1_general_ci,
change `unbau` `unbau` varchar (100) NULL COLLATE latin1_general_ci;

*/
//	global $lang, $pricelist;
//
//	// Array del b_hangar_id
//	$b_building_id = explode(';', $CurrentPlanet['b_building_queue']);
//
//	$a = $b = $c = "";
//	foreach($b_hangar_id as $n => $array) {
//		if ($array != '') {
//			$array = explode(',', $array);
//			// calculamos el tiempo
//			$time = GetBuildingTime($user, $CurrentPlanet, $array[0]);
//			$totaltime += $time * $array[1];
//			$c .= "$time,";
//			$b .= "'{$lang['tech'][$array[0]]}',";
//			$a .= "{$array[1]},";
//		}
//	}
//
//	$parse = $lang;
//	$parse['a'] = $a;
//	$parse['b'] = $b;
//	$parse['c'] = $c;
//	$parse['b_hangar_id_plus'] = $CurrentPlanet['b_hangar'];
//
//	$parse['pretty_time_b_hangar'] = pretty_time($totaltime - $CurrentPlanet['b_hangar']); // //$CurrentPlanet['last_update']
//
//	$text .= parsetemplate(gettemplate('buildings_script'), $parse);
//
//	return $text;
}

?>