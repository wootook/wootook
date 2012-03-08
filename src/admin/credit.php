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

define('INSIDE' , true);
define('INSTALL' , false);
define('IN_ADMIN', true);
require_once dirname(dirname(__FILE__)) .'/application/bootstrap.php';
includeLang('credit');
$parse   = $lang;

if (in_array($user['authlevel'], array(LEVEL_ADMIN))) {
	if ($_POST['opt_save'] == "1") {
		// Extended copyright is activated?
		if (isset($_POST['ExtCopyFrame']) && $_POST['ExtCopyFrame'] == 'on') {
			$gameConfig['ExtCopyFrame'] = "1";
			$gameConfig['ExtCopyOwner'] = $_POST['ExtCopyOwner'];
			$gameConfig['ExtCopyFunct'] = $_POST['ExtCopyFunct'];
		} else {
			$gameConfig['ExtCopyFrame'] = "0";
			$gameConfig['ExtCopyOwner'] = "";
			$gameConfig['ExtCopyFunct'] = "";
		}

		// Update values
		doquery("UPDATE {{table}} SET `config_value` = '". $gameConfig['ExtCopyFrame'] ."' WHERE `config_name` = 'ExtCopyFrame';", 'config');
		doquery("UPDATE {{table}} SET `config_value` = '". $gameConfig['ExtCopyOwner'] ."' WHERE `config_name` = 'ExtCopyOwner';", 'config');
		doquery("UPDATE {{table}} SET `config_value` = '". $gameConfig['ExtCopyFunct'] ."' WHERE `config_name` = 'ExtCopyFunct';", 'config');

		AdminMessage ($lang['cred_done'], $lang['cred_ext']);

	} else {
		//View values
		$parse['ExtCopyFrame'] = ($gameConfig['ExtCopyFrame'] == 1) ? " checked = 'checked' ":"";
		$parse['ExtCopyOwnerVal'] = $gameConfig['ExtCopyOwner'];
		$parse['ExtCopyFunctVal'] = $gameConfig['ExtCopyFunct'];

		$BodyTPL = gettemplate('admin/credit_body');
		$page = parsetemplate($BodyTPL, $parse);
		display($page, $lang['cred_credit'], false);
	}

} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}

?>