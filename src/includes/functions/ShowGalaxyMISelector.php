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
 * @param unknown_type $Galaxy
 * @param unknown_type $System
 * @param unknown_type $Planet
 * @param unknown_type $Current
 * @param unknown_type $MICount
 */
function ShowGalaxyMISelector ( $Galaxy, $System, $Planet, $Current, $MICount ) {
	global $lang;

	$Result  = "<form action=\"raketenangriff.php?c=".$Current."&mode=2&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."\" method=\"POST\">";
	$Result .= "<tr>";
	$Result .= "<table border=\"0\">";
	$Result .= "<tr>";
	$Result .= "<td class=\"c\" colspan=\"2\">";
	$Result .= $lang['gm_launch'] ." [".$Galaxy.":".$System.":".$Planet."]";
	$Result .= "</td>";
	$Result .= "</tr>";
	$Result .= "<tr>";
	$String  = sprintf($lang['gm_restmi'], $MICount);
	$Result .= "<td class=\"c\">".$String." <input type=\"text\" name=\"SendMI\" size=\"2\" maxlength=\"7\" /></td>";
	$Result .= "<td class=\"c\">".$lang['gm_target']." <select name=\"Target\">";
	$Result .= "<option value=\"all\" selected>".$lang['gm_all']."</option>";
	$Result .= "<option value=\"0\">".$lang['tech'][401]."</option>";
	$Result .= "<option value=\"1\">".$lang['tech'][402]."</option>";
	$Result .= "<option value=\"2\">".$lang['tech'][403]."</option>";
	$Result .= "<option value=\"3\">".$lang['tech'][404]."</option>";
	$Result .= "<option value=\"4\">".$lang['tech'][405]."</option>";
	$Result .= "<option value=\"5\">".$lang['tech'][406]."</option>";
	$Result .= "<option value=\"6\">".$lang['tech'][407]."</option>";
	$Result .= "<option value=\"7\">".$lang['tech'][408]."</option>";
	$Result .= "</select>";
	$Result .= "</td>";
	$Result .= "</tr>";
	$Result .= "<tr>";
	$Result .= "<td class=\"c\" colspan=\"2\"><input type=\"submit\" name=\"aktion\" value=\"".$lang['gm_send']."\"></td>";
	$Result .= "</tr>";
	$Result .= "</table>";
	$Result .= "</form>";

	return $Result;
}

?>