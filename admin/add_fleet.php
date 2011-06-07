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

	if ($user['authlevel'] >= 1) {
		includeLang('admin/add_fleet');
		$mode = $_GET['mode'];

		if($mode != 'add') {
			$parse['ID']     = $lang['Id'];
			$parse['Cle']    = $lang['cle'];
			$parse['Clourd'] = $lang['clourd'];
			$parse['Pt']     = $lang['pt'];
			$parse['Gt']     = $lang['gt'];
			$parse['Cruise'] = $lang['cruise'];
			$parse['Vb']     = $lang['vb'];
			$parse['Colo']   = $lang['colo'];
			$parse['Rc']     = $lang['rc'];
			$parse['Spy']    = $lang['spy'];
			$parse['Bomb']   = $lang['bomb'];
			$parse['Solar']  = $lang['solar'];
			$parse['Des']    = $lang['des'];
			$parse['Rip']    = $lang['rip'];
			$parse['Traq']   = $lang['traq'];

		} elseif($mode == 'add') {
			$id     = $_POST['id'];
			$cle    = $_POST['cle'];
			$clourd = $_POST['clourd'];
			$pt     = $_POST['pt'];
			$gt     = $_POST['gt'];
			$cruise = $_POST['cruise'];
			$vb     = $_POST['vb'];
			$colo   = $_POST['colo'];
			$rc     = $_POST['rc'];
			$spy    = $_POST['spy'];
			$bomb   = $_POST['bomb'];
			$solar  = $_POST['solar'];
			$des    = $_POST['des'];
			$rip    = $_POST['rip'];
			$traq   = $_POST['traq'];

			$SqlAdd = "UPDATE {{table}} SET";
			$SqlAdd .= "`light_hunter` = '".$cle."+light_hunter', ";
			$SqlAdd .= "`heavy_hunter` = '".$clourd."+heavy_hunter', ";
			$SqlAdd .= "`small_ship_cargo` = '".$pt."+small_ship_cargo', ";
			$SqlAdd .= "`big_ship_cargo` = '".$gt."+big_ship_cargo', ";
			$SqlAdd .= "`crusher` = '".$cruise."+crusher', ";
			$SqlAdd .= "`battle_ship` = '".$vb."+battle_ship', ";
			$SqlAdd .= "`colonizer` = '".$colo."+colonizer', ";
			$SqlAdd .= "`recycler` = '".$rc."+recycler', ";
			$SqlAdd .= "`spy_sonde`= '".$spy."+spy_sonde', ";
			$SqlAdd .= "`bomber_ship` = '".$bomb."+bomber_ship', ";
			$SqlAdd .= "`solar_satelit` = '".$solar."+solar_satelit', ";
			$SqlAdd .= "`destructor` = '".$des."+destructor', ";
			$SqlAdd .= "`dearth_star` = '".$rip."+dearth_star', ";
			$SqlAdd .= "`battleship` = '".$traq."+battleship', ";
			$SqlAdd .= " WHERE `id` = '".$id."' LIMIT 1";
			doquery($SqlAdd, "planets");
			message('Ajout OK');
		}

		$page = parsetemplate(gettemplate('admin/add_fleet'), $parse);
		display( $page);

	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>