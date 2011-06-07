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
 * @param unknown_type $QueueID
 */
function RemoveBuildingFromQueue ( &$CurrentPlanet, $CurrentUser, $QueueID ) {

	if ($QueueID > 1) {
		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		if ($CurrentQueue != 0) {
			$QueueArray    = explode ( ";", $CurrentQueue );
			$ActualCount   = count ( $QueueArray );
			$ListIDArray   = explode ( ",", $QueueArray[$QueueID - 2] );
			$BuildEndTime  = $ListIDArray[3];
			$ListIDArray   = explode ( ",", $QueueArray[$QueueID - 1] );
			$Element = $ListIDArray[0];
			for ($ID = $QueueID; $ID < $ActualCount; $ID++ ) {
				$ListIDArray          = explode ( ",", $QueueArray[$ID] );
				if ($Element == $ListIDArray[0])
				{
					$ListIDArray[1]		 -= 1;
					$ListIDArray[2]		  = GetBuildingTimeLevel($CurrentUser, $CurrentPlanet, $ListIDArray[0], $ListIDArray[1]);
				}
				$BuildEndTime        += $ListIDArray[2];
				$ListIDArray[3]       = $BuildEndTime;
				$QueueArray[$ID - 1]  = implode ( ",", $ListIDArray );
			}
			unset ($QueueArray[$ActualCount - 1]);
			$NewQueue     = implode ( ";", $QueueArray );
		}
		$CurrentPlanet['b_building_id'] = $NewQueue;
	}

	return $QueueID;

}
?>