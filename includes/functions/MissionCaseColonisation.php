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
 * @param unknown_type $FleetRow
 */
function MissionCaseColonisation ( $FleetRow ) {
	global $lang, $resource;

	$statement = doquery ("SELECT count(*) FROM {{table}} WHERE `id_owner` = '". $FleetRow['fleet_owner'] ."' AND `planet_type` = '1'", 'planets');
	$iPlanetCount = $statement->fetch(PDO::FETCH_ASSOC);
	if ($FleetRow['fleet_mess'] == 0) {
		// Déjà, sommes nous a l'aller ??
		$statement2 = doquery ("SELECT count(*) FROM {{table}} WHERE `galaxy` = '". $FleetRow['fleet_end_galaxy']."' AND `system` = '". $FleetRow['fleet_end_system']."' AND `planet` = '". $FleetRow['fleet_end_planet']."';", 'galaxy');
		$iGalaxyPlace = $statement2->fetch(PDO::FETCH_ASSOC);
		$TargetAdress = sprintf ($lang['sys_adress_planet'], $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
		if ($iGalaxyPlace == 0) {
			// Y a personne qui s'y est mis avant que je ne debarque !
			if ($iPlanetCount >= MAX_PLAYER_PLANETS && $user['authlevel'] != LEVEL_ADMIN) {
				$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_maxcolo'] . MAX_PLAYER_PLANETS . $lang['sys_colo_planet'];
				SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
				doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
			} else {
			    $user = Wootook_Empire_Model_User::factory($FleetRow['fleet_owner']);
			    $user->createNewPlanet(
			        intval($FleetRow['fleet_end_galaxy']),
			        intval($FleetRow['fleet_end_system']),
			        intval($FleetRow['fleet_end_planet']),
			        Wootook_Empire_Model_Planet::TYPE_PLANET,
			        Wootook::__('Colony')
			        );

				if ( $NewOwnerPlanet == true ) {
					$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_allisok'];
					SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
					// Verifier ce que contient fleet_array (et le cas et cheant retirer un element '208'
					if ($FleetRow['fleet_amount'] == 1) {
						doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
					} else {
						$CurrentFleet = explode(";", $FleetRow['fleet_array']);
						$NewFleet     = "";
						foreach ($CurrentFleet as $Item => $Group) {
							if ($Group != '') {
								$Class = explode (",", $Group);
								if ($Class[0] == 208) {
									if ($Class[1] > 1) {
										$NewFleet  .= $Class[0].",".($Class[1] - 1).";";
									}
								} else {
									if ($Class[1] <> 0) {
									$NewFleet  .= $Class[0].",".$Class[1].";";
									}
								}
							}
						}
						$QryUpdateFleet  = "UPDATE {{table}} SET ";
						$QryUpdateFleet .= "`fleet_array` = '". $NewFleet ."', ";
						$QryUpdateFleet .= "`fleet_amount` = `fleet_amount` - 1, ";
						$QryUpdateFleet .= "`fleet_mess` = '1' ";
						$QryUpdateFleet .= "WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';";
						doquery( $QryUpdateFleet, 'fleets');
					}
				} else {
					$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_badpos'];
					SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
					doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				}
			}
		} else {
			// Pas de bol coiffé sur le poteau !
			$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_notfree'];
			SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_end_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
			// Mettre a jour la flotte pour qu'effectivement elle revienne !
			doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');

		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
		// Retour de flotte
		RestoreFleetToPlanet ( $FleetRow, true );
		doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
		}
	}
}

?>