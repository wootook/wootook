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
 * @param unknown_type $Owner
 * @param unknown_type $Sender
 * @param unknown_type $Time
 * @param unknown_type $Type
 * @param unknown_type $From
 * @param unknown_type $Subject
 * @param unknown_type $Message
 */
function SendSimpleMessage ( $Owner, $Sender, $Time, $Type, $From, $Subject, $Message) {
	global $messfields;

	if ($Time == '') {
		$Time = time();
	}

	$QryInsertMessage  = "INSERT INTO {{table}} SET ";
	$QryInsertMessage .= "`message_owner` = '". $Owner ."', ";
	$QryInsertMessage .= "`message_sender` = '". $Sender ."', ";
	$QryInsertMessage .= "`message_time` = '" . $Time . "', ";
	$QryInsertMessage .= "`message_type` = '". $Type ."', ";
	$QryInsertMessage .= "`message_from` = '". addslashes( $From ) ."', ";
	$QryInsertMessage .= "`message_subject` = '". addslashes( $Subject ) ."', ";
	$QryInsertMessage .= "`message_text` = '". addslashes( $Message ) ."';";
	doquery( $QryInsertMessage, 'messages');

	$QryUpdateUser  = "UPDATE {{table}} SET ";
	$QryUpdateUser .= "`".$messfields[$Type]."` = `".$messfields[$Type]."` + 1, ";
	$QryUpdateUser .= "`".$messfields[100]."` = `".$messfields[100]."` + 1 ";
	$QryUpdateUser .= "WHERE ";
	$QryUpdateUser .= "`id` = '". $Owner ."';";
	doquery( $QryUpdateUser, 'users');

}

// Revision history :
// 1.0 - Initial release (mise en fonction generique)
// 1.1 - Ajout gestion des messages par type pour le module de messages
// 1.2 - Correction bug (addslashes pour les zone texte pouvant contenir une apostrophe)
// 1.3 - Correction bug (integration de la variable $Time pour afficher l'heure exacte de l'evenement pour les flottes)

?>