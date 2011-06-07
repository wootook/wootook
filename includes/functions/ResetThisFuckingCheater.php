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
 * @param unknown_type $UserID
 */
function ResetThisFuckingCheater ( $UserID ) {
	$TheUser        = doquery ("SELECT * FROM {{table}} WHERE `id` = '". $UserID ."';", 'users', true);
	$UserPlanet     = doquery ("SELECT `name` FROM {{table}} WHERE `id` = '". $TheUser['id_planet']."';", 'planets', true);
	DeleteSelectedUser ( $UserID );
	if ($UserPlanet['name'] != "") {
		// Creation de l'utilisateur
		$QryInsertUser  = "INSERT INTO {{table}} SET ";
		$QryInsertUser .= "`id` = '".            $TheUser['id']            ."', ";
		$QryInsertUser .= "`username` = '".      $TheUser['username']      ."', ";
		$QryInsertUser .= "`email` = '".         $TheUser['email']         ."', ";
		$QryInsertUser .= "`email_2` = '".       $TheUser['email_2']       ."', ";
		$QryInsertUser .= "`sex` = '".           $TheUser['sex']           ."', ";
		$QryInsertUser .= "`id_planet` = '0', ";
		$QryInsertUser .= "`authlevel` = '".     $TheUser['authlevel']     ."', ";
		$QryInsertUser .= "`dpath` = '".         $TheUser['dpath']         ."', ";
		$QryInsertUser .= "`galaxy` = '".        $TheUser['galaxy']        ."', ";
		$QryInsertUser .= "`system` = '".        $TheUser['system']        ."', ";
		$QryInsertUser .= "`planet` = '".        $TheUser['planet']        ."', ";
		$QryInsertUser .= "`register_time` = '". $TheUser['register_time'] ."', ";
		$QryInsertUser .= "`password` = '".      $TheUser['password']      ."';";
		doquery( $QryInsertUser, 'users');

		// On cherche le numero d'enregistrement de l'utilisateur fraichement cr??
		$NewUser        = doquery("SELECT `id` FROM {{table}} WHERE `username` = '". $TheUser['username'] ."' LIMIT 1;", 'users', true);

		CreateOnePlanetRecord ($TheUser['galaxy'], $TheUser['system'], $TheUser['planet'], $NewUser['id'], $UserPlanet['name'], true);
		// Recherche de la reference de la nouvelle planete (qui est unique normalement !
		$PlanetID       = doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $NewUser['id'] ."' LIMIT 1;", 'planets', true);

		// Mise a jour de l'enregistrement utilisateur avec les infos de sa planete mere
		$QryUpdateUser  = "UPDATE {{table}} SET ";
		$QryUpdateUser .= "`id_planet` = '".      $PlanetID['id'] ."', ";
		$QryUpdateUser .= "`current_planet` = '". $PlanetID['id'] ."' ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '".             $NewUser['id']  ."';";
		doquery( $QryUpdateUser, 'users');
	}

	return;
}

?>