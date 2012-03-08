<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://wootook.org>
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
 */
function BuildFlyingFleetTable () {
	global $lang;

	$TableTPL     = gettemplate('admin/fleet_rows');
	$FlyingFleets = doquery ("SELECT * FROM {{table}} ORDER BY `fleet_end_time` ASC;", 'fleets');
	while ( $CurrentFleet = $FlyingFleets->fetch(PDO::FETCH_ASSOC) ) {
		$FleetOwner       = doquery("SELECT `username` FROM {{table}} WHERE `id` = '". $CurrentFleet['fleet_owner'] ."';", 'users', true);
		$TargetOwner      = doquery("SELECT `username` FROM {{table}} WHERE `id` = '". $CurrentFleet['fleet_target_owner'] ."';", 'users', true);
		$Bloc['Id']       = $CurrentFleet['fleet_id'];
		$Bloc['Mission']  = CreateFleetPopupedMissionLink ( $CurrentFleet, $lang['type_mission'][ $CurrentFleet['fleet_mission'] ], '' );
		$Bloc['Mission'] .= "<br>". (($CurrentFleet['fleet_mess'] == 1) ? "R" : "A" );

		$Bloc['Fleet']    = CreateFleetPopupedFleetLink ( $CurrentFleet, $lang['tech'][200], '' );
		$Bloc['St_Owner'] = "[". $CurrentFleet['fleet_owner'] ."]<br>". $FleetOwner['username'];
		$Bloc['St_Posit'] = "[".$CurrentFleet['fleet_start_galaxy'] .":". $CurrentFleet['fleet_start_system'] .":". $CurrentFleet['fleet_start_planet'] ."]<br>". ( ($CurrentFleet['fleet_start_type'] == 1) ? "[P]": (($CurrentFleet['fleet_start_type'] == 2) ? "D" : "L"  )) ."";
		$Bloc['St_Time']  = date('G:i:s d/n/Y', $CurrentFleet['fleet_start_time']);
		if (is_array($TargetOwner)) {
			$Bloc['En_Owner'] = "[". $CurrentFleet['fleet_target_owner'] ."]<br>". $TargetOwner['username'];
		} else {
			$Bloc['En_Owner'] = "";
		}
		$Bloc['En_Posit'] = "[".$CurrentFleet['fleet_end_galaxy'] .":". $CurrentFleet['fleet_end_system'] .":". $CurrentFleet['fleet_end_planet'] ."]<br>". ( ($CurrentFleet['fleet_end_type'] == 1) ? "[P]": (($CurrentFleet['fleet_end_type'] == 2) ? "D" : "L"  )) ."";
		if ($CurrentFleet['fleet_mission'] == 15) {
			$Bloc['Wa_Time']  = date('G:i:s d/n/Y', $CurrentFleet['fleet_stay_time']);
		} else {
			$Bloc['Wa_Time']  = "";
		}
		$Bloc['En_Time']  = date('G:i:s d/n/Y', $CurrentFleet['fleet_end_time']);

		$table .= parsetemplate( $TableTPL, $Bloc );
	}
	return $table;
}


?>