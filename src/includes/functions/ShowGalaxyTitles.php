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
function ShowGalaxyTitles ( $Galaxy, $System ) {
	global $lang;

	$Result  = "\n";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=8>".$lang['Solar_system']." ".$Galaxy.":".$System."</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td class=c>".$lang['Pos']."</td>";
	$Result .= "<td class=c>".$lang['Planet']."</td>";
	$Result .= "<td class=c>".$lang['Name']."</td>";
	$Result .= "<td class=c>".$lang['Moon']."</td>";
	$Result .= "<td class=c>".$lang['Debris']."</td>";
	$Result .= "<td class=c>".$lang['Player']."</td>";
	$Result .= "<td class=c>".$lang['Alliance']."</td>";
	$Result .= "<td class=c>".$lang['Actions']."</td>";
	$Result .= "</tr>";

	return $Result;
}

?>