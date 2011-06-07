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

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';

	// On récupère les informations du message et de l'envoyeur
	if (isset($_POST["msg"]) && isset($user['username'])) {
	   $nick = trim (str_replace ("+","plus",$user['username']));
	   $msg  = trim (str_replace ("+","plus",$_POST["msg"]));
	   $msg  = addslashes ($_POST["msg"]);
	   $nick = addslashes ($user['username']);
	}
	else {
	   $msg="";
	   $nick="";
	}

	// Ajout du message dans la database
	if ($msg!="" && $nick!="") {
	   $query = doquery("INSERT INTO {{table}}(user, message, timestamp) VALUES ('".$nick."', '".$msg."', '".time()."')", "chat");
	}

// Shoutbox by e-Zobar - Copyright XNova Team 2008
?>