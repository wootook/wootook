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
 * @param unknown_type $Title
 * @param unknown_type $Message
 * @param unknown_type $Goto
 * @param unknown_type $Button
 * @param unknown_type $TwoLines
 */
function MessageForm ($Title, $Message, $Goto = '', $Button = ' ok ', $TwoLines = false) {
	$Form  = "<center>";
	$Form .= "<form action=\"". $Goto ."\" method=\"post\">";
	$Form .= "<table width=\"519\">";
	$Form .= "<tr>";
		$Form .= "<td class=\"c\" colspan=\"2\">". $Title ."</td>";
	$Form .= "</tr><tr>";
	if ($TwoLines == true) {
		$Form .= "<th colspan=\"2\">". $Message ."</th>";
		$Form .= "</tr><tr>";
		$Form .= "<th colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"". $Button ."\"></th>";
	} else {
		$Form .= "<th colspan=\"2\">". $Message ."<input type=\"submit\" value=\"". $Button ."\"></th>";
	}
	$Form .= "</tr>";
	$Form .= "</table>";
	$Form .= "</form>";
	$Form .= "</center>";

	return $Form;
}
// Release History
// - 1.0 Mise en fonction, Documentation
?>