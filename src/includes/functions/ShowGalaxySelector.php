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
 */
function ShowGalaxySelector ( $Galaxy, $System ) {
	global $lang;

	if ($Galaxy > Wootook::getGameConfig('engine/universe/galaxies')) {
		$Galaxy = Wootook::getGameConfig('engine/universe/galaxies');
	}
	if ($Galaxy < 1) {
		$Galaxy = 1;
	}
	if ($System > Wootook::getGameConfig('engine/universe/systems')) {
		$System = Wootook::getGameConfig('engine/universe/systems');
	}
	if ($System < 1) {
		$System = 1;
	}

	$Result  = "<form action=\"galaxy.php?mode=1\" method=\"post\" id=\"galaxy_form\">";
	$Result .= "<input type=\"hidden\" id=\"auto\" value=\"dr\" >";
	$Result .= "<table border=\"0\">";
	$Result .= "<tbody><tr><td>";

	$Result .= "<table><tbody><tr>";
	$Result .= "<td class=\"c\" colspan=\"3\">". $lang['Galaxy'] ."</td></tr><tr>";
	$Result .= "<td class=\"l\"><input name=\"galaxyLeft\" value=\"&lt;-\" onclick=\"galaxy_submit('galaxyLeft')\" type=\"button\"></td>";
	$Result .= "<td class=\"l\"><input name=\"galaxy\" value=\"". $Galaxy ."\" size=\"5\" maxlength=\"3\" tabindex=\"1\" type=\"text\"></td>";
	$Result .= "<td class=\"l\"><input name=\"galaxyRight\" value=\"-&gt;\" onclick=\"galaxy_submit('galaxyRight')\" type=\"button\"></td>";
	$Result .= "</tr></tbody></table>";

	$Result .= "</td><td>";

	$Result .= "<table><tbody><tr>";
	$Result .= "<td class=\"c\" colspan=\"3\">". $lang['Solar_system'] ."</td></tr><tr>";
	$Result .= "<td class=\"l\"><input name=\"systemLeft\" value=\"&lt;-\" onclick=\"galaxy_submit('systemLeft')\" type=\"button\"></td>";
	$Result .= "<td class=\"l\"><input name=\"system\" value=\"". $System ."\" size=\"5\" maxlength=\"3\" tabindex=\"2\" type=\"text\"></td>";
	$Result .= "<td class=\"l\"><input name=\"systemRight\" value=\"-&gt;\" onclick=\"galaxy_submit('systemRight')\" type=\"button\"></td>";
	$Result .= "</tr></tbody></table>";

	$Result .= "</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td class=\"l\" colspan=\"2\" align=\"center\"> <input value=\"". $lang['Afficher'] ."\" type=\"submit\"></td>";
	$Result .= "</tr>";
	$Result .= "</tbody></table>";
	$Result .= "</form>";

	return $Result;

}

?>