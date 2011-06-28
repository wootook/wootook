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
function SetNextQueueElementOnTop ( &$CurrentPlanet, $CurrentUser ) {
	global $lang, $resource;
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

	// Garde fou ... Si le temps de construction n'est pas 0 on ne fait rien !!!
	if ($CurrentPlanet['b_building'] == 0) {
		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		if ($CurrentQueue != 0) {
			$QueueArray = explode ( ";", $CurrentQueue );
			$Loop       = true;
			while ($Loop == true) {
				$ListIDArray         = explode ( ",", $QueueArray[0] );
				$Element             = $ListIDArray[0];
				$Level               = $ListIDArray[1];
				$BuildTime           = $ListIDArray[2];
				$BuildEndTime        = $ListIDArray[3];
				$BuildMode           = $ListIDArray[4]; // pour savoir si on construit ou detruit
				$HaveNoMoreLevel     = false;

				if ($BuildMode == 'destroy') {
					$ForDestroy = true;
				} else {
					$ForDestroy = false;
				}
				$HaveRessources = IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
				if ($ForDestroy) {
					if ($CurrentPlanet[$resource[$Element]] == 0) {
						$HaveRessources  = false;
						$HaveNoMoreLevel = true;
					}
				}
				if ( $HaveRessources == true ) {
					$Needed                        = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
					$CurrentPlanet['metal']       -= $Needed['metal'];
					$CurrentPlanet['crystal']     -= $Needed['crystal'];
					$CurrentPlanet['deuterium']   -= $Needed['deuterium'];
					$CurrentTime                   = time();
					$BuildEndTime                  = $BuildEndTime;
					$NewQueue                      = implode ( ";", $QueueArray );
					if ($NewQueue == "") {
						$NewQueue                      = '0';
					}
					$Loop                          = false;
				} else {
					$ElementName = $lang['tech'][$Element];
					if ($HaveNoMoreLevel == true) {
						$Message     = sprintf ($lang['sys_nomore_level'], $ElementName );
					} else {
						$Needed      = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
						$Message     = sprintf ($lang['sys_notenough_money'], $ElementName,
												pretty_number ($CurrentPlanet['metal']), $lang['Metal'],
												pretty_number ($CurrentPlanet['crystal']), $lang['Crystal'],
												pretty_number ($CurrentPlanet['deuterium']), $lang['Deuterium'],
												pretty_number ($Needed['metal']), $lang['Metal'],
												pretty_number ($Needed['crystal']), $lang['Crystal'],
												pretty_number ($Needed['deuterium']), $lang['Deuterium']);
					}

					SendSimpleMessage ( $CurrentUser['id'], '', '', 99, $lang['sys_buildlist'], $lang['sys_buildlist_fail'], $Message);

					array_shift( $QueueArray );
					$ActualCount         = count ( $QueueArray );
					if ( $ActualCount == 0 ) {
						$BuildEndTime  = '0';
						$NewQueue      = '0';
						$Loop          = false;
					}
				}
			} // while
		} else {
			$BuildEndTime  = '0';
			$NewQueue      = '0';
		}

		// Ecriture de la mise a jour dans la BDD
		$CurrentPlanet['b_building']    = $BuildEndTime;
		$CurrentPlanet['b_building_id'] = $NewQueue;

		$QryUpdatePlanet  = "UPDATE {{table}} SET ";
		$QryUpdatePlanet .= "`metal` = '".         $CurrentPlanet['metal']         ."' , ";
		$QryUpdatePlanet .= "`crystal` = '".       $CurrentPlanet['crystal']       ."' , ";
		$QryUpdatePlanet .= "`deuterium` = '".     $CurrentPlanet['deuterium']     ."' , ";
		$QryUpdatePlanet .= "`b_building` = '".    $CurrentPlanet['b_building']    ."' , ";
		$QryUpdatePlanet .= "`b_building_id` = '". $CurrentPlanet['b_building_id'] ."' ";
		$QryUpdatePlanet .= "WHERE ";
		$QryUpdatePlanet .= "`id` = '" .           $CurrentPlanet['id']            . "';";
		doquery( $QryUpdatePlanet, 'planets');

	}

	return;
}

?>