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
define('IN_ADMIN', true);
require_once dirname(dirname(__FILE__)) .'/common.php';

	if (in_array($user['authlevel'], array(LEVEL_ADMIN, LEVEL_OPERATOR, LEVEL_MODERATOR))) {
		if ($_POST && $mode == "change") {
			if (isset($_POST["tresc"]) && $_POST["tresc"] != '') {
				$gameConfig['tresc'] = $_POST['tresc'];
			}
			if (isset($_POST["temat"]) && $_POST["temat"] != '') {
				$gameConfig['temat'] = $_POST['temat'];
			}
			if ($user['authlevel'] == LEVEL_ADMIN) {
				$kolor = 'red';
				$ranga = 'Administrator';
			} elseif ($user['authlevel'] == LEVEL_OPERATOR) {
				$kolor = 'skyblue';
				$ranga = 'Operator';
			} elseif ($user['authlevel'] == LEVEL_MODERATOR) {
				$kolor = 'yellow';
				$ranga = 'Moderator';
			}
			if ($gameConfig['tresc'] != '' and $gameConfig['temat']) {
				$sq      = doquery("SELECT `id` FROM {{table}}", "users");
				$Time    = time();
				$From    = "<font color=\"". $kolor ."\">". $ranga ." ".$user['username']."</font>";
				$Subject = "<font color=\"". $kolor ."\">". $gameConfig['temat'] ."</font>";
				$Message = "<font color=\"". $kolor ."\"><b>". $gameConfig['tresc'] ."</b></font>";
				while ($u = $sq->fetch(PDO::FETCH_BOTH)) {
					SendSimpleMessage ( $u['id'], $user['id'], $Time, 97, $From, $Subject, $Message);
				}
				message("<font color=\"lime\">Wys�a�e� wiadomo�� do wszystkich graczy</font>", "Complete", "../overview." . PHPEXT, 3);
			}
		} else {
			$parse = $gameConfig;
			$parse['dpath'] = $dpath;
			$parse['debug'] = ($gameConfig['debug'] == 1) ? " checked='checked'/":'';
			$page .= parsetemplate(gettemplate('admin/messall_body'), $parse);
			display($page, '', false,'', true);
		}
	} else {
		message($lang['sys_noalloaw'], $lang['sys_noaccess']);
	}
?>