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
 * @param unknown_type $CurrentPlanet
 * @param unknown_type $CurrentUser
 */
function ShowBuildingQueue ( $CurrentPlanet, $CurrentUser ) {
	global $lang;

	$CurrentQueue  = $CurrentPlanet['b_building_id'];
	$QueueID       = 0;
	if ($CurrentQueue != 0) {
		// Queue de fabrication documentée ... Y a au moins 1 element a construire !
		$QueueArray    = explode ( ";", $CurrentQueue );
		// Compte le nombre d'elements
		$ActualCount   = count ( $QueueArray );
	} else {
		// Queue de fabrication vide
		$QueueArray    = "0";
		$ActualCount   = 0;
	}

	$ListIDRow    = "";
	if ($ActualCount != 0) {
		$PlanetID     = $CurrentPlanet['id'];
		for ($QueueID = 0; $QueueID < $ActualCount; $QueueID++) {
			// Chaque element de la liste de fabrication est un tableau de 5 données
			// [0] -> Le batiment
			// [1] -> Le niveau du batiment
			// [2] -> La durée de construction
			// [3] -> L'heure théorique de fin de construction
			// [4] -> type d'action
			$BuildArray   = explode (",", $QueueArray[$QueueID]);
			$BuildEndTime = floor($BuildArray[3]);
			$CurrentTime  = floor(time());
			if ($BuildEndTime >= $CurrentTime) {
				$ListID       = $QueueID + 1;
				$Element      = $BuildArray[0];
				$BuildLevel   = $BuildArray[1];
				$BuildMode    = $BuildArray[4];
				$BuildTime    = $BuildEndTime - time();
				$ElementTitle = $lang['tech'][$Element];

				if ($ListID > 0) {
					$ListIDRow .= "<tr>";
					if ($BuildMode == 'build') {
						$ListIDRow .= "	<td class=\"l\" colspan=\"2\">". $ListID .".: ". $ElementTitle ." ". $BuildLevel ."</td>";
					} else {
						$ListIDRow .= "	<td class=\"l\" colspan=\"2\">". $ListID .".: ". $ElementTitle ." ". $BuildLevel ." ". $lang['destroy'] ."</td>";
					}
					$ListIDRow .= "	<td class=\"k\">";
					if ($ListID == 1) {
						$ListIDRow .= "		<div id=\"blc\" class=\"z\">". $BuildTime ."<br>";
						$ListIDRow .= "		<a href=\"buildings.php?listid=". $ListID ."&amp;cmd=cancel&amp;planet=". $PlanetID ."\">". $lang['DelFirstQueue'] ."</a></div>";
						$ListIDRow .= "		<script language=\"JavaScript\">";
						$ListIDRow .= "			pp = \"". $BuildTime ."\";\n";      // temps necessaire (a compter de maintenant et sans ajouter time() )
						$ListIDRow .= "			pk = \"". $ListID ."\";\n";         // id index (dans la liste de construction)
						$ListIDRow .= "			pm = \"cancel\";\n";                // mot de controle
						$ListIDRow .= "			pl = \"". $PlanetID ."\";\n";       // id planete
						$ListIDRow .= "			t();\n";
						$ListIDRow .= "		</script>";
						$ListIDRow .= "		<strong color=\"lime\"><br><font color=\"lime\">". date("j/m H:i:s" ,$BuildEndTime) ."</font></strong>";
					} else {
						$ListIDRow .= "		<font color=\"red\">";
						$ListIDRow .= "		<a href=\"buildings.php?listid=". $ListID ."&amp;cmd=remove&amp;planet=". $PlanetID ."\">". $lang['DelFromQueue'] ."</a></font>";
					}
					$ListIDRow .= "	</td>";
					$ListIDRow .= "</tr>";
				}
			}
		}
	}

	$RetValue['lenght']    = $ActualCount;
	$RetValue['buildlist'] = $ListIDRow;

	return $RetValue;
}

?>