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

$dpath = (!$userrow["dpath"]) ? DEFAULT_SKINPATH : $userrow["dpath"];


if(!is_numeric($_GET["a"]) || !$_GET["a"] ){ message("Ung&uuml;ltige Allianz-ID","Fehler");}

$allyrow = doquery("SELECT ally_name,ally_tag,ally_description,ally_web,ally_image FROM {{table}} WHERE id=".$_GET["a"],"alliance",true);

if(!$allyrow){ message("Alliance non trouv&eacute;e","Erreur");}

$count = doquery("SELECT COUNT(DISTINCT(id)) FROM {{table}} WHERE ally_id=".$_GET["a"].";","users",true);
$ally_member_scount = $count[0];

$page .="<table width=519><tr><td class=c colspan=2>Informations sur l'alliance</td></tr>";

	if($allyrow["ally_image"] != ""){
		$page .= "<tr><th colspan=2><img src=\"".$allyrow["ally_image"]."\"></td></tr>";
	}

	$page .= "<tr><th>Tag</th><th>".$allyrow["ally_tag"]."</th></tr><tr><th>Nom</th><th>".$allyrow["ally_name"]."</th></tr><tr><th>Membres</th><th>$ally_member_scount</th></tr>";

	if($allyrow["ally_description"] != ""){
		$page .= "<tr><th colspan=2 height=100>".$allyrow["ally_description"]."</th></tr>";
	}


	if($allyrow["ally_web"] != ""){
		$page .="<tr>
		<th>Site internet</th>
		<th><a href=\"".$allyrow["ally_web"]."\">".$allyrow["ally_web"]."</a></th>
		</tr>";
	}
	$page .= "</table>";

	display($page,"Information sur l'alliance [".$allyrow["ally_name"]."]",false);

// Created by Perberos. All rights reversed (C) 2006
?>