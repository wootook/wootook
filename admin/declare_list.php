<?php
/**
 * Tis file is part of XNova:Legacies
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
define('IN_ADMIN', true);
require_once dirname(dirname(__FILE__)) .'/common.php';

    if (in_array($user['authlevel'], array(LEVEL_ADMIN, LEVEL_OPERATOR))) {
		includeLang('admin');
		if ($_GET['cmd'] == 'dele') {
			DeleteSelectedUser ( $_GET['user'] );
		}
		if ($_GET['cmd'] == 'sort') {
			$TypeSort = $_GET['type'];
		} else {
			$TypeSort = "id";
		}

		$PageTPL = gettemplate('admin/declarelist_body');
		$RowsTPL = gettemplate('admin/declarelist_rows');

		$query   = doquery("SELECT * FROM {{table}} ORDER BY `declarator` DESC", 'declared');

		$parse                 = $lang;
		$parse['adm_ul_table'] = "";
		$i                     = 0;
		$Color                 = "lime";
		while ($u = mysql_fetch_assoc ($query) ) {
			if ($PrevIP != "") {
				if ($PrevIP == $u['declarator']) {
					$Color = "red";
				} else {
					$Color = "lime";
				}
			}
			$Bloc['adm_ul_data_id']     = stripslashes($u['declarator_name']);
			$Bloc['adm_ul_data_name']   = stripslashes($u['declarator']);
			$Bloc['adm_ul_data_mail']   = stripslashes($u['declared_1']);
			$Bloc['adm_ul_data_adip']   = stripslashes($u['declared_2']);
			$Bloc['adm_ul_data_detai']  = stripslashes($u['declared_3']);
			$Bloc['adm_ul_data_regd']   = stripslashes($u['reason']);


			$parse['adm_ul_table']     .= parsetemplate( $RowsTPL, $Bloc );
			$i++;
		}
		$parse['adm_ul_count'] = $i;

		$page = parsetemplate( $PageTPL, $parse );
		display( $page, "Liste des joueurs ayant declare une IP collective", false, '', true);
	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

// Created by e-Zobar. All rights reversed (C) XNova Team 2008
?>