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
 * @param unknown_type $Galaxy
 * @param unknown_type $System
 * @param unknown_type $Planet
 * @param unknown_type $Owner
 * @param unknown_type $MoonID
 * @param unknown_type $MoonName
 * @param unknown_type $Chance
 */
function CreateOneMoonRecord ( $Galaxy, $System, $Planet, $Owner, $MoonID, $MoonName, $Chance ) {
	global $lang;

	$PlanetName            = "";

	$QryGetMoonPlanetData  = "SELECT * FROM {{table}} ";
	$QryGetMoonPlanetData .= "WHERE ";
	$QryGetMoonPlanetData .= "`galaxy` = '". $Galaxy ."' AND ";
	$QryGetMoonPlanetData .= "`system` = '". $System ."' AND ";
	$QryGetMoonPlanetData .= "`planet` = '". $Planet ."';";
	$MoonPlanet = doquery ( $QryGetMoonPlanetData, 'planets', true);

	$QryGetMoonGalaxyData  = "SELECT * FROM {{table}} ";
	$QryGetMoonGalaxyData .= "WHERE ";
	$QryGetMoonGalaxyData .= "`galaxy` = '". $Galaxy ."' AND ";
	$QryGetMoonGalaxyData .= "`system` = '". $System ."' AND ";
	$QryGetMoonGalaxyData .= "`planet` = '". $Planet ."';";
	$MoonGalaxy = doquery ( $QryGetMoonGalaxyData, 'galaxy', true);

	if ($MoonGalaxy['id_luna'] == 0) {
		if ($MoonPlanet['id'] != 0) {
			$SizeMin                = 2000 + ( $Chance * 100 );
			$SizeMax                = 6000 + ( $Chance * 200 );

			$PlanetName             = $MoonPlanet['name'];

			$maxtemp                = $MoonPlanet['temp_max'] - rand(10, 45);
			$mintemp                = $MoonPlanet['temp_min'] - rand(10, 45);
			$size                   = rand ($SizeMin, $SizeMax);

			$QryInsertMoonInLunas   = "INSERT INTO {{table}} SET ";
			$QryInsertMoonInLunas  .= "`name` = '". ( ($MoonName == '') ? $lang['sys_moon'] : $MoonName ) ."', ";
			$QryInsertMoonInLunas  .= "`galaxy` = '".   $Galaxy  ."', ";
			$QryInsertMoonInLunas  .= "`system` = '".   $System  ."', ";
			$QryInsertMoonInLunas  .= "`lunapos` = '".  $Planet  ."', ";
			$QryInsertMoonInLunas  .= "`id_owner` = '". $Owner   ."', ";
			$QryInsertMoonInLunas  .= "`temp_max` = '". $maxtemp ."', ";
			$QryInsertMoonInLunas  .= "`temp_min` = '". $mintemp ."', ";
			$QryInsertMoonInLunas  .= "`diameter` = '". $size    ."', ";
			$QryInsertMoonInLunas  .= "`id_luna` = '".  $MoonID  ."';";
			doquery( $QryInsertMoonInLunas , 'lunas' );

			$QryGetMoonIdFromLunas  = "SELECT * FROM {{table}} ";
			$QryGetMoonIdFromLunas .= "WHERE ";
			$QryGetMoonIdFromLunas .= "`galaxy` = '".  $Galaxy ."' AND ";
			$QryGetMoonIdFromLunas .= "`system` = '".  $System ."' AND ";
			$QryGetMoonIdFromLunas .= "`lunapos` = '". $Planet ."';";
			$lunarow = doquery( $QryGetMoonIdFromLunas , 'lunas', true);

			$QryUpdateMoonInGalaxy  = "UPDATE {{table}} SET ";
			$QryUpdateMoonInGalaxy .= "`id_luna` = '". $lunarow['id'] ."', ";
			$QryUpdateMoonInGalaxy .= "`luna` = '0' ";
			$QryUpdateMoonInGalaxy .= "WHERE ";
			$QryUpdateMoonInGalaxy .= "`galaxy` = '". $Galaxy ."' AND ";
			$QryUpdateMoonInGalaxy .= "`system` = '". $System ."' AND ";
			$QryUpdateMoonInGalaxy .= "`planet` = '". $Planet ."';";
			doquery( $QryUpdateMoonInGalaxy , 'galaxy');

			$QryInsertMoonInPlanet  = "INSERT INTO {{table}} SET ";
			$QryInsertMoonInPlanet .= "`name` = '" .$lang['sys_moon'] ."', ";
			$QryInsertMoonInPlanet .= "`id_owner` = '". $Owner ."', ";
			$QryInsertMoonInPlanet .= "`galaxy` = '". $Galaxy ."', ";
			$QryInsertMoonInPlanet .= "`system` = '". $System ."', ";
			$QryInsertMoonInPlanet .= "`planet` = '". $Planet ."', ";
			$QryInsertMoonInPlanet .= "`last_update` = '". time() ."', ";
			$QryInsertMoonInPlanet .= "`planet_type` = '3', ";
			$QryInsertMoonInPlanet .= "`image` = 'mond', ";
			$QryInsertMoonInPlanet .= "`diameter` = '". $size ."', ";
			$QryInsertMoonInPlanet .= "`field_max` = '1', ";
			$QryInsertMoonInPlanet .= "`temp_min` = '". $maxtemp ."', ";
			$QryInsertMoonInPlanet .= "`temp_max` = '". $mintemp ."', ";
			$QryInsertMoonInPlanet .= "`metal` = '0', ";
			$QryInsertMoonInPlanet .= "`metal_perhour` = '0', ";
			$QryInsertMoonInPlanet .= "`metal_max` = '".BASE_STORAGE_SIZE."', ";
			$QryInsertMoonInPlanet .= "`crystal` = '0', ";
			$QryInsertMoonInPlanet .= "`crystal_perhour` = '0', ";
			$QryInsertMoonInPlanet .= "`crystal_max` = '".BASE_STORAGE_SIZE."', ";
			$QryInsertMoonInPlanet .= "`deuterium` = '0', ";
			$QryInsertMoonInPlanet .= "`deuterium_perhour` = '0', ";
			$QryInsertMoonInPlanet .= "`deuterium_max` = '".BASE_STORAGE_SIZE."';";
			doquery( $QryInsertMoonInPlanet , 'planets');
		}
	}

	return $PlanetName;
}

?>