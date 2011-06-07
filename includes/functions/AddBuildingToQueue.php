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
 * @param unknown_type $Element
 * @param unknown_type $AddMode
 */
function AddBuildingToQueue ( &$CurrentPlanet, $CurrentUser, $Element, $AddMode = true) {
	global $lang, $resource;

	$CurrentQueue  = $CurrentPlanet['b_building_id'];
	if ($CurrentQueue != 0) {
		$QueueArray    = explode ( ";", $CurrentQueue );
		$ActualCount   = count ( $QueueArray );
	} else {
		$QueueArray    = "";
		$ActualCount   = 0;
	}

	if ($AddMode == true) {
		$BuildMode = 'build';
	} else {
		$BuildMode = 'destroy';
	}

	if ( $ActualCount < MAX_BUILDING_QUEUE_SIZE ) {
		$QueueID      = $ActualCount + 1;
	} else {
		$QueueID      = false;
	}

	if ( $QueueID != false ) {
		// Faut verifier si l'Element que l'on veut integrer est deja dans le tableau !
		if ($QueueID > 1) {
			$InArray = 0;
			for ( $QueueElement = 0; $QueueElement < $ActualCount; $QueueElement++ ) {
				$QueueSubArray = explode ( ",", $QueueArray[$QueueElement] );
				if ($QueueSubArray[0] == $Element) {
					$InArray++;
				}
			}
		} else {
			$InArray = 0;
		}

		if ($InArray != 0) {
			$ActualLevel  = $CurrentPlanet[$resource[$Element]];
			if ($AddMode == true) {
				$BuildLevel   = $ActualLevel + 1 + $InArray;
				$CurrentPlanet[$resource[$Element]] += $InArray;
				$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
				$CurrentPlanet[$resource[$Element]] -= $InArray;
			} else {
				$BuildLevel   = $ActualLevel - 1 + $InArray;
				$CurrentPlanet[$resource[$Element]] -= $InArray;
				$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
				$CurrentPlanet[$resource[$Element]] += $InArray;
			}
		} else {
			$ActualLevel  = $CurrentPlanet[$resource[$Element]];
			if ($AddMode == true) {
				$BuildLevel   = $ActualLevel + 1;
				$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
			} else {
				$BuildLevel   = $ActualLevel - 1;
				$BuildTime    = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element) / 2;
			}
		}

		if ($QueueID == 1) {
			$BuildEndTime = time() + $BuildTime;
		} else {
			$PrevBuild = explode (",", $QueueArray[$ActualCount - 1]);
			$BuildEndTime = $PrevBuild[3] + $BuildTime;
		}
		$QueueArray[$ActualCount]       = $Element .",". $BuildLevel .",". $BuildTime .",". $BuildEndTime .",". $BuildMode;
		$NewQueue                       = implode ( ";", $QueueArray );
		$CurrentPlanet['b_building_id'] = $NewQueue;
	}
	return $QueueID;
}

?>