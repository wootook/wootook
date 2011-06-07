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
require_once dirname(__FILE__) .'/common.php';
	includeLang('fleet');

	$fleetid = $_POST['fleetid'];

	if (!is_numeric($fleetid) || empty($fleetid)) {
		header("Location: overview.php");
		exit();
	}

	$query = doquery("SELECT * FROM {{table}} WHERE fleet_id = '" . $fleetid . "'", 'fleets');

	if (mysql_num_rows($query) != 1) {
		message('Cette flotte n\'existe pas (ou plus)!', 'Erreur');
	}

	$daten = mysql_fetch_array($query);

	if ($daten['fleet_start_time'] <= time() || $daten['fleet_end_time'] < time() || $daten['fleet_mess'] == 1) {
		message('Votre flotte est d�j� sur le chemin du retour!', 'Erreur');
	}

	if (!isset($_POST['send'])) {
		SetSelectedPlanet ( $user );

		$planetrow = doquery("SELECT * FROM {{table}} WHERE `id` = '".$user['current_planet']."';", 'planets', true);
		$galaxyrow = doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$planetrow['id']."';", 'galaxy', true);
		$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];
		$maxfleet = doquery("SELECT COUNT(fleet_owner) as ilosc FROM {{table}} WHERE fleet_owner='{$user['id']}'", 'fleets', true);
		$maxfleet_count = $maxfleet["ilosc"];

		CheckPlanetUsedFields($planetrow);

		$fleet = doquery("SELECT * FROM {{table}} WHERE fleet_id = '" . $fleetid . "'", 'fleets', true);

		if (empty($fleet['fleet_group'])) {
			$rand = mt_rand(100000, 999999999);

		doquery(
		"INSERT INTO {{table}} SET
		`name` = 'KV" . $rand . "',
		`teilnehmer` = '" . $user['id'] . "',
		`flotten` = '" . $fleetid . "',
		`ankunft` = '" . $fleet['fleet_start_time'] . "',
		`galaxy` = '" . $fleet['fleet_start_galaxy'] . "',
		`system` = '" . $fleet['fleet_start_system'] . "',
		`planet` = '" . $fleet['fleet_start_planet'] . "',
		`eingeladen` = '" . $user['id'] . "'
		",
		'aks');

		$aks = doquery(
		"SELECT * FROM {{table}} WHERE
		`name` = 'KV" . $rand . "' AND
		`teilnehmer` = '" . $user['id'] . "' AND
		`flotten` = '" . $fleetid . "' AND
		`ankunft` = '" . $fleet['fleet_start_time'] . "' AND
		`galaxy` = '" . $fleet['fleet_start_galaxy'] . "' AND
		`system` = '" . $fleet['fleet_start_system'] . "' AND
		`planet` = '" . $fleet['fleet_start_planet'] . "' AND
		`eingeladen` = '" . $user['id'] . "'
		", 'aks', true);

		doquery(
		"UPDATE {{table}} SET
		fleet_group = '" . $aks['id'] . "'
		WHERE
		fleet_id = '" . $fleetid . "'", 'fleets');
	} else {
		$aks = doquery(
		"SELECT * FROM {{table}} WHERE
		id = '" . $fleet['fleet_group'] . "'"
		, 'aks');

		if (mysql_num_rows($aks) != 1) {
			message('AKS nicht gefunden!', 'Fehler');
		}
		$aks = mysql_num_rows($aks);
	}

	$missiontype = array(1 => 'Attaquer',
		2 => 'Zerst&ouml;ren',
		3 => 'Transporter',
		4 => 'Stationner',
		5 => 'Halten',
		6 => 'Espionner',
		7 => 'Coloniser',
		8 => 'Recycler',
		9 => 'Coloniser',
		);

	$speed = array(10 => 100,
		9 => 90,
		8 => 80,
		7 => 70,
		6 => 60,
		5 => 50,
		4 => 40,
		3 => 30,
		2 => 20,
		1 => 10,
		);

	if (!$galaxy) {
		$galaxy = $planetrow['galaxy'];
	}
	if (!$system) {
		$system = $planetrow['system'];
	}
	if (!$planet) {
		$planet = $planetrow['planet'];
	}
	if (!$planettype) {
		$planettype = $planetrow['planet_type'];
	}
	$ile = '' . ++$user[$resource[108]] . '';
	$page = '<script language="JavaScript" src="scripts/flotten.js"></script>
<script language="JavaScript" src="scripts/ocnt.js"></script>
  <center>
    <table width="519" border="0" cellpadding="0" cellspacing="1">
      <tr height="20">
        <td colspan="9" class="c">Flotte (max. ' . $ile . ')</td>
      </tr>
      <tr height="20">
        <th>ID</th>
        <th>Mission</th>
        <th> Nombre</th>
	<!--<th>Absendezeit</th>-->
        <th>Depart</th>
        <th>Arriv&eacute;e (cible)</th>
        <th>Objectif</th>
        <th>Arriv&eacute;e (retour)</th>
        <th>Retour �</th>
        <th>Ordre</th>
      </tr>';
	/*
	  Here must show the fleet movings of owner player.
	*/

	$fq = doquery("SELECT * FROM {{table}} WHERE fleet_owner={$user[id]}", 'fleets');

	$i = 0;
	while ($f = mysql_fetch_array($fq)) {
		$i++;

		$page .= "<tr height=20><th>$i</th><th>";

		$page .= "<a title=\"\">{$missiontype[$f[fleet_mission]]}</a>";
		if (($f['fleet_start_time'] + 1) == $f['fleet_end_time'])
			$page .= " <a title=\"R&uuml;ckweg\">(F)</a>";
		$page .= "</th><th><a title=\"";
		/*
		  Se debe hacer una lista de las tropas
		*/
		$fleet = explode(";", $f['fleet_array']);
		$e = 0;
		foreach($fleet as $a => $b) {
			if ($b != '') {
				$e++;
				$a = explode(",", $b);
				$page .= "{$lang['tech']{$a[0]}}: {$a[1]}\n";
				if ($e > 1) {
					$page .= "\t";
				}
			}
		}
		$page .= "\">" . pretty_number($f[fleet_amount]) . "</a></th>";
		// $page .= "<th>".gmdate("d. M Y H:i:s",$f['fleet_start_time'])."</th>";
		$page .= "<th>[{$f[fleet_start_galaxy]}:{$f[fleet_start_system]}:{$f[fleet_start_planet]}]</th>";
		$page .= "<th>" . gmdate("d. M Y H:i:s", $f['fleet_start_time']) . "</th>";
		$page .= "<th>[{$f[fleet_end_galaxy]}:{$f[fleet_end_system]}:{$f[fleet_end_planet]}]</th>";
		$page .= "<th>" . gmdate("d. M Y H:i:s", $f['fleet_end_time']) . "</th>";
		$page .= " </form>";

		$page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>" . pretty_time(floor($f['fleet_end_time'] + 1 - time())) . "</font></th><th>";

		if ($f['fleet_mess'] == 0) {
			$page .= "     <form action=\"fleetback.php\" method=\"post\">
      <input name=\"zawracanie\" value=" . $f['fleet_id'] . " type=hidden>
         <input value=\" Retour \" type=\"submit\">
       </form></th>";
		} else $page .= "&nbsp;</th>";

		$page .= "</div></font>
            </tr>";
	}

	if ($i == 0) {
		$page .= "<th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th>";
	}
	if ($ile == $maxfleet_count) {
		$maxflot = '<tr height="20"><th colspan="9"><font color="red">Nombre de slot de flotte maximum atteint!</font></th></tr>';
	}
	$page .= '
		' . $maxflot . '</table>
	  </center>
    <table width="519" border="0" cellpadding="0" cellspacing="1">
   <tr height="20">
     <td class="c" colspan="2">	Association de flotte KV50502025</td>
   </tr>

   <form action="verband.php" method="POST">
   <input type="hidden" name="fleet_id value="' . $fleetid . '" />
   <input type="hidden" name="changename" value="49021" />
   <tr height="20">

  <td class="c" colspan="2">VModifiez le nom de l\'Association</td>
   </tr>
   <tr>
    <th colspan="2"><input name="groupname" value="KV50502025" /> <br /> <input type="submit" value="OK" /></th>
   </tr>
   </form>

   <tr>
    <th>
     <table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr height="20">
       <td class="c">Invit�s participants</td>
       <td class="c">Inviter des participants</td>
      </tr>
      <tr>

       <th width="50%">
        <select size="5">
                   <option>Thunder Storm</option>
                 </select>
       </th>

	    <form action="index.php?page=flotten1&session=388eb542c1d0" method="POST">
	<input type="hidden" name="order_union" value="49021" />
       <input type="hidden" name="adduser" value="49021" />

       <td><input name="addtogroup" /> <br /><input type="submit" value="OK" /></td>
    </form>
             </tr>
     </table>
    </th>
   </tr>
   <tr>

   </tr>

  </table>
	  <center>
		<form action="floten1.php" method="post">
		<table width="519" border="0" cellpadding="0" cellspacing="1">
		  <tr height="20">
			<td colspan="4" class="c">Nouveau march�: Choix de la flotte</td>
		  </tr>
		  <tr height="20">
			<th>Nom du vaisseau</th>
			<th>Nombre</th>';
	// <!--    <th>Gesch.</th> -->
	$page .= '
			<th>-</th>
			<th>-</th>
		  </tr>';
	if (!$planetrow) {
		message('WTF! FEHLER!', 'ERROR');
	} //uno nunca sabe xD
	$galaxy = intval($_GET['galaxy']);
	$system = intval($_GET['system']);
	$planet = intval($_GET['planet']);
	$planettype = intval($_GET['planettype']);
	$target_mission = intval($_GET['target_mission']);

	foreach($reslist['fleet'] as $n => $i) {
		if ($planetrow[$resource[$i]] > 0) {
			if ($i == 202 or $i == 203 or $i == 204 or $i == 209 or $i == 210) {
				$pricelist[$i]['speed'] = $pricelist[$i]['speed'] + (($pricelist[$i]['speed'] * $user['combustion_tech']) * 0.1);
			}
			if ($i == 205 or $i == 206 or $i == 208 or $i == 211) {
				$pricelist[$i]['speed'] = $pricelist[$i]['speed'] + (($pricelist[$i]['speed'] * $user['impulse_motor_tech']) * 0.2);
			}
			if ($i == 207 or $i == 213 or $i == 214 or $i == 215 or $i == 216) {
				$pricelist[$i]['speed'] = $pricelist[$i]['speed'] + (($pricelist[$i]['speed'] * $user['hyperspace_motor_tech']) * 0.3);
			}
			$page .= '<tr height="20">
			<th><a title="Geschwindigkeit: ' . $pricelist[$i]['speed'] . '">' . $lang['tech'][$i] . '</a></th>
			<th>' . pretty_number($planetrow[$resource[$i]]) . '
			  <input type="hidden" name="maxship' . $i . '" value="' . $planetrow[$resource[$i]] . '"/></th>

			<input type="hidden" name="consumption' . $i . '" value="' . $pricelist[$i]['consumption'] . '"/>

			<input type="hidden" name="speed' . $i . '" value="' . $pricelist[$i]['speed'] . '" />
			<input type="hidden" name="galaxy" value="' . $galaxy . '"/>

			<input type="hidden" name="system" value="' . $system . '"/>
			<input type="hidden" name="planet" value="' . $planet . '"/>
			<input type="hidden" name="planet_type" value="' . $planettype . '"/>
			<input type="hidden" name="mission" value="' . $target_mission . '"/>
			</th>
			<input type="hidden" name="capacity' . $i . '" value="' . $pricelist[$i]['capacity'] . '" />
			</th>';
			if ($i == 212) {
				$page .= '<th></th><th></th></tr>';
			} else {
				$page .= '<th><a href="javascript:maxShip(\'ship' . $i . '\'); shortInfo();">max</a> </th>
				<th><input name="ship' . $i . '" size="10" value="0" onfocus="javascript:if(this.value == \'0\') this.value=\'\';" onblur="javascript:if(this.value == \'\') this.value=\'0\';" alt="' . $lang['tech'][$i] . $planetrow[$resource[$i]] . '"  onChange="shortInfo()" onKeyUp="shortInfo()"/></th>
				</tr>';
				$aaaaaaa = $pricelist[$i]['consumption'];
			}
			$have_ships = true;
		}
	}

	if (!$have_ships) {
		$page .= '<tr height="20">
		<th colspan="4">Aucun vaisseau</th>
		</tr>
		<tr height="20">
		<th colspan="4">
		<input type="button" value="OK" enabled/></th>
		</tr>
		</table>
		</center>
		</form>';
	} else {
		$page .= '
		  <tr height="20">
			<th colspan="2"><a href="javascript:noShips();shortInfo();noResources();" >Aucun Vaisseaux</a></th>
			<th colspan="2"><a href="javascript:maxShips();shortInfo();" >Tout les vaisseaux</a></th>
		  </tr>';

		$przydalej = '<tr height="20"><th colspan="4"><input type="submit" value="OK" /></th></tr>';
		if ($ile == $maxfleet_count) {
			$przydalej = '';
		}
		$page .= '
		' . $przydalej . '
		<tr><th colspan="4">
		<br><center></center><br>
		</th></tr>
		</table>
	  </center>
	</form>';
	}
} else {
}

display($page, "Flotten");

?>