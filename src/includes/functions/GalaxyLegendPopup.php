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
 */
function GalaxyLegendPopup () {
	global $lang;

	$Result  = '<a href="#" onclick="return false;" style="cursor: pointer;"';
	$Result .= " onmouseover='return overlib(\"";

	$Result .= "<table width=240>";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=2>".$lang['Legend']."</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Strong_player']."</td><td><span class=strong>".$lang['strong_player_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Weak_player']."</td><td><span class=noob>".$lang['weak_player_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Way_vacation']."</td><td><span class=vacation>".$lang['vacation_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Pendent_user']."</td><td><span class=banned>".$lang['banned_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Inactive_7_days']."</td><td><span class=inactive>".$lang['inactif_7_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>".$lang['Inactive_28_days']."</td><td><span class=longinactive>".$lang['inactif_28_shortcut']."</span></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>Admin</td><td><font color=red><blink>A</blink></font></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>Operator</td><td><font color=lime><blink>O</blink></font></td>";
	$Result .= "</tr><tr>";
	$Result .= "<td width=220>Moderator</td><td><font color=skyblue><blink>M</blink></font></td>";
	$Result .= "</tr>";
	$Result .= "</table>";
	$Result .= "\");' onmouseout='return nd();'>";
	$Result .= $lang['Legend']."</a>";




	return $Result;
}

?>