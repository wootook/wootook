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
 * @deprecated
 * @param unknown_type $FleetRow
 */
function MissionCaseRecycling ($FleetRow) {
	global $pricelist, $lang;

	if ($FleetRow["fleet_mess"] == "0") {
		if ($FleetRow['fleet_start_time'] <= time()) {
			$QrySelectGalaxy  = "SELECT * FROM {{table}} ";
			$QrySelectGalaxy .= "WHERE ";
			$QrySelectGalaxy .= "`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
			$QrySelectGalaxy .= "`system` = '".$FleetRow['fleet_end_system']."' AND ";
			$QrySelectGalaxy .= "`planet` = '".$FleetRow['fleet_end_planet']."' ";
			$QrySelectGalaxy .= "LIMIT 1;";
			$TargetGalaxy     = doquery( $QrySelectGalaxy, 'galaxy', true);

			$FleetRecord         = explode(";", $FleetRow['fleet_array']);
			$RecyclerCapacity    = 0;
			$OtherFleetCapacity  = 0;
			foreach ($FleetRecord as $Item => $Group) {
				if ($Group != '') {
					$Class        = explode (",", $Group);
					if ($Class[0] == 209) {
						$RecyclerCapacity   += $pricelist[$Class[0]]["capacity"] * $Class[1];
					} else {
						$OtherFleetCapacity += $pricelist[$Class[0]]["capacity"] * $Class[1];
					}
				}
			}

			$IncomingFleetGoods = $FleetRow["fleet_resource_metal"] + $FleetRow["fleet_resource_crystal"] + $FleetRow["fleet_resource_deuterium"];
			if ($IncomingFleetGoods > $OtherFleetCapacity) {
				$RecyclerCapacity -= ($IncomingFleetGoods - $OtherFleetCapacity);
			}

			if (($TargetGalaxy["metal"] + $TargetGalaxy["crystal"]) <= $RecyclerCapacity) {
				$RecycledGoods["metal"]   = $TargetGalaxy["metal"];
				$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
			} else {
				if (($TargetGalaxy["metal"]   > $RecyclerCapacity / 2) AND
					($TargetGalaxy["crystal"] > $RecyclerCapacity / 2)) {
					$RecycledGoods["metal"]   = $RecyclerCapacity / 2;
					$RecycledGoods["crystal"] = $RecyclerCapacity / 2;
				} else {
					if ($TargetGalaxy["metal"] > $TargetGalaxy["crystal"]) {
						$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
						if ($TargetGalaxy["metal"] > ($RecyclerCapacity - $RecycledGoods["crystal"])) {
							$RecycledGoods["metal"] = $RecyclerCapacity - $RecycledGoods["crystal"];
						} else {
							$RecycledGoods["metal"] = $TargetGalaxy["metal"];
						}
					} else {
						$RecycledGoods["metal"] = $TargetGalaxy["metal"];
						if ($TargetGalaxy["crystal"] > ($RecyclerCapacity - $RecycledGoods["metal"])) {
							$RecycledGoods["crystal"] = $RecyclerCapacity - $RecycledGoods["metal"];
						} else {
							$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
						}
					}
				}
			}
			$NewCargo['Metal']     = $FleetRow["fleet_resource_metal"]   + $RecycledGoods["metal"];
			$NewCargo['Crystal']   = $FleetRow["fleet_resource_crystal"] + $RecycledGoods["crystal"];
			$NewCargo['Deuterium'] = $FleetRow["fleet_resource_deuterium"];

			$QryUpdateGalaxy  = "UPDATE {{table}} SET ";
			$QryUpdateGalaxy .= "`metal` = `metal` - '".$RecycledGoods["metal"]."', ";
			$QryUpdateGalaxy .= "`crystal` = `crystal` - '".$RecycledGoods["crystal"]."' ";
			$QryUpdateGalaxy .= "WHERE ";
			$QryUpdateGalaxy .= "`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
			$QryUpdateGalaxy .= "`system` = '".$FleetRow['fleet_end_system']."' AND ";
			$QryUpdateGalaxy .= "`planet` = '".$FleetRow['fleet_end_planet']."' ";
			$QryUpdateGalaxy .= "LIMIT 1;";
			doquery( $QryUpdateGalaxy, 'galaxy');

			$Message = sprintf($lang['sys_recy_gotten'], pretty_number($RecycledGoods["metal"]), $lang['Metal'], pretty_number($RecycledGoods["crystal"]), $lang['Crystal']);
			SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 4, $lang['sys_mess_spy_control'], $lang['sys_recy_report'], $Message);
			doquery("UPDATE {{table}} SET `mnl_exploit` = `mnl_exploit` + '1' WHERE `id` = '".$FleetRow['fleet_owner']."'", 'users');

			$QryUpdateFleet  = "UPDATE {{table}} SET ";
            $QryUpdateFleet .= "`fleet_resource_metal` = '".$NewCargo['Metal']."', ";
			$QryUpdateFleet .= "`fleet_resource_crystal` = '".$NewCargo['Crystal']."', ";
			$QryUpdateFleet .= "`fleet_resource_deuterium` = '".$NewCargo['Deuterium']."', ";
			$QryUpdateFleet .= "`fleet_mess` = '1' ";
            $QryUpdateFleet .= "WHERE ";
			$QryUpdateFleet .= "`fleet_id` = '".$FleetRow['fleet_id']."' ";
            $QryUpdateFleet .= "LIMIT 1;";
			doquery( $QryUpdateFleet, 'fleets');
		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
			// Mettre le message de retour de flotte
			$Message         = sprintf( $lang['sys_tran_mess_owner'],
						$TargetName, GetTargetAdressLink($FleetRow, ''),
						pretty_number($FleetRow['fleet_resource_metal']), $lang['Metal'],
						pretty_number($FleetRow['fleet_resource_crystal']), $lang['Crystal'],
						pretty_number($FleetRow['fleet_resource_deuterium']), $lang['Deuterium'] );
			SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_end_time'], 4, $lang['sys_mess_spy_control'], $lang['sys_mess_fleetback'], $Message);

			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
		}
	}
}

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 Mise en module initiale

?>