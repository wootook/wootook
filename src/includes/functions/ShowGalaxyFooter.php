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
 * @param unknown_type $CurrentMIP
 * @param unknown_type $CurrentRC
 * @param unknown_type $CurrentSP
 */
function ShowGalaxyFooter ( $Galaxy, $System,  $CurrentMIP, $CurrentRC, $CurrentSP) {
	global $lang, $maxfleet_count, $fleetmax, $planetcount;

	$Result  = "";
	if ($planetcount == 1) {
		$PlanetCountMessage = $planetcount ." ". $lang['gf_cntmone'];
	} elseif ($planetcount == 0) {
		$PlanetCountMessage = $lang['gf_cntmnone'];
	} else {
		$PlanetCountMessage = $planetcount." " . $lang['gf_cntmsome'];
	}
	$LegendPopup = GalaxyLegendPopup ();
	$Recyclers   = pretty_number($CurrentRC);
	$SpyProbes   = pretty_number($CurrentSP);

	$Result .= "\n";
	$Result .= "<tr>";
	$Result .= "<th width=\"30\">16</th>";
	$Result .= "<th colspan=7>";
	$Result .= "<a href=fleet.php?galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=16;planettype=1&amp;target_mission=15>". $lang['gf_unknowsp'] ."</a>";
	$Result .= "</th>";
	$Result .= "</tr>";

	$Result .= "\n";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=6>( ".$PlanetCountMessage." )</td>";
	$Result .= "<td class=c colspan=2>". $LegendPopup ."</td>";
	$Result .= "</tr>";

	$Result .= "\n";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=3><span id=\"missiles\">". $CurrentMIP ."</span> ". $lang['gf_mi_title'] ."</td>";
	$Result .= "<td class=c colspan=3><span id=\"slots\">". $maxfleet_count ."</span>/". $fleetmax ." ". $lang['gf_fleetslt'] ."</td>";
	$Result .= "<td class=c colspan=2>";
	$Result .= "<span id=\"recyclers\">". $Recyclers ."</span> ". $lang['gf_rc_title'] ."<br>";
	$Result .= "<span id=\"probes\">". $SpyProbes ."</span> ". $lang['gf_sp_title'] ."</td>";
	$Result .= "</tr>";

	$Result .= "\n";
	$Result .= "<tr style=\"display: none;\" id=\"fleetstatusrow\">";
	$Result .= "<th class=c colspan=8><!--<div id=\"fleetstatus\"></div>-->";
	$Result .= "<table style=\"font-weight: bold\" width=\"100%\" id=\"fleetstatustable\">";
	$Result .= "<!-- will be filled with content later on while processing ajax replys -->";
//	$Result .= "<tr style=\"display: none; align:left\" id=\"fleetstatusrow\">";
//	$Result .= "<th colspan=8><div style=\"align:left\" id=\"fleetstatus\"></div></th>";
//	$Result .= "</tr>";
	$Result .= "</table>";
	$Result .= "</th>";
	$Result .= "\n";
	$Result .= "</tr>";
/*
<tr style=\"display: none;\" id=\"fleetstatusrow\"><th colspan="8"><!--<div id="fleetstatus"></div>-->
<table style="font-weight: bold;" width=100% id="fleetstatustable">
<!-- will be filled with content later on while processing ajax replys -->
</table>
</th>
</tr>
*/
	return $Result;
}

?>