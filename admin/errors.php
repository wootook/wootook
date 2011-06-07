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

includeLang('admin');
$parse = $lang;

	if (in_array($user['authlevel'], array(LEVEL_ADMIN))) {

		// Supprimer les erreurs
		extract($_GET);
		if (isset($delete)) {
			doquery("DELETE FROM {{table}} WHERE `error_id`=$delete", 'errors');
		} elseif ($deleteall == 'yes') {
			doquery("TRUNCATE TABLE {{table}}", 'errors');
		}

		// Afficher les erreurs
		$query = doquery("SELECT * FROM {{table}}", 'errors');
		$i = 0;
		while ($u = mysql_fetch_array($query)) {
			$i++;
			$parse['errors_list'] .= "
			<tr><td width=\"25\" class=n>". $u['error_id'] ."</td>
			<td width=\"170\" class=n>". $u['error_type'] ."</td>
			<td width=\"230\" class=n>". date('d/m/Y h:i:s', $u['error_time']) ."</td>
			<td width=\"95\" class=n><a href=\"?delete=". $u['error_id'] ."\"><img src=\"../images/r1.png\"></a></td></tr>
			<tr><td colspan=\"4\" class=b>".  nl2br($u['error_text'])."</td></tr>";
		}
		$parse['errors_list'] .= "<tr>
			<th class=b colspan=5>". $i ." ". $lang['adm_er_nbs'] ."</th>
		</tr>";

		display(parsetemplate(gettemplate('admin/errors_body'), $parse), "Bledy", false, '', true);
	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>
