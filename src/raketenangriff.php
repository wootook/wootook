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
require_once dirname(__FILE__) .'/application/bootstrap.php';
$planet    = doquery("SELECT * FROM {{table}} WHERE `id` = '".$user['current_planet']."';", 'planets', true);
$iraks = $planet['interplanetary_misil'];



$g = intval($_GET['galaxy']);
$s = intval($_GET['system']);
$i = intval($_GET['planet']);
$anz = intval($_POST['SendMI']);
$pziel = $_POST['Target'];


$currentplanet = doquery("SELECT * FROM {{table}} WHERE id={$user['current_planet']}",'planets',true);

$tempvar1 = (($s-$currentplanet['system'])*-1);
$tempvar2 = ($user['impulse_motor_tech'] * 2) - 1;
$tempvar3 = doquery("SELECT * FROM {{table}} WHERE galaxy = ".$g." AND
			system = ".$s." AND
			planet = ".$i." AND
			planet_type = 1", 'planets');



if ($planet['silo'] < 4) {
	$error = 1;

}
elseif ($user['impulse_motor_tech'] == 0) {;
	$error = 1;

}
elseif ($tempvar1 >= $tempvar2 || $g != $currentplanet['galaxy']) {
	$error = 1;
}
elseif ($tempvar3->rowCount() != 1) {
	$error = 1;
}
elseif ($anz > $iraks) {
	$error = 1;
}
elseif ((!is_numeric($pziel) && $pziel != "all") OR ($pziel < 0 && $pziel > 7 && $pziel != "all")) {
	$error = 1;
}



if ($error == 1) {
	message('Du hast entweder zu wenig Interplanetarraketen, der Planet auf den zu schiessen willst existiert nicht oder du hast nicht die n&ouml;tige Reichweite oder Technik.', 'Fehler');
	exit();
}

$iraks_anzahl = $iraks;

if ($pziel == "all")
	$pziel = 0;
else
	$pziel = intval($pziel);



$planet = doquery("SELECT * FROM {{table}} WHERE galaxy = ".$g." AND
			system = ".$s." AND
			planet = ".$i." AND
			planet_type = 1", 'planets', true);

$ziel_id = $planet['id_owner'];

$select = doquery("SELECT * FROM {{table}} WHERE id = ".$ziel_id, 'users', true);




 $verteidiger_panzerung = $select['defence_tech'];
 $angreifer_waffen = $user['military_tech'];
 $primaerziel = $pziel;
 $iraks = $anz;
 $def =
		array(
			0 => $planet['misil_launcher'], // Raketenwerfer
			1 => $planet['small_laser'], // Leichtes Lasergesch�tz
			2 => $planet['big_laser'], // Schweres Lasergesch�tz
			3 => $planet['gauss_canyon'], // Gau�kanone
			4 => $planet['ionic_canyon'], // Ionengesch�tz
			5 => $planet['buster_canyon'], // Plasmawerfer
			6 => $planet['small_protection_shield'], // Kleine Schildkuppel
			7 => $planet['big_protection_shield'], // Gro�e Schildkuppel
			8 => $planet['interplanetary_misil'], // Interplanetarrakete
			9 => $planet['interceptor_misil'], // Abfangrakete

		);

 $lang =
	array(
        0 => "Lanceur Missile",
		1 => "Canon Magn&eacute;tique",
		2 => "Batterie Electromagn&eacute;tique",
		3 => "Canon de Gauss",
		4 => "Lanceur Ionique",
		5 => "Lanceur de plasma",
		6 => "Petit bouclier",
		7 => "Grand bouclier",
		8 => "Missiles Intercepteur",
		9 => "Missiles Interplanetaire",
		10 => "Missiles Intercepteur"

 	);







$flugzeit = round(((30 + (60 * $tempvar1)) * 2500) / Wootook::getGameConfig('game/speed/general'));



/*
include("./includes/raketenangriff.php");


$irak = raketenangriff($verteidiger_panzerung, $angreifer_waffen, $iraks, $def, $primaerziel);

 $ids = array(
		0 => 401,
		1 => 402,
		2 => 403,
		3 => 404,
		4 => 405,
		5 => 406,
		6 => 407,
		7 => 408,
		8 => 502,
		9 => 503
	);





foreach ($irak['verbleibt'] as $id => $anzahl) {
	if ($id < 10) {

		$x = $resource[$ids[$id]];

		doquery("UPDATE {{table}} SET ".$x." = '".$anzahl."' WHERE id = ".$ziel_id, 'planets');


	}


}
*/

doquery("INSERT INTO {{table}} SET
		`zeit` = '".(time() + $flugzeit)."',
		`galaxy` = '".$g."',
		`system` = '".$s."',
		`planet` = '".$i."',
		`galaxy_angreifer` = '".$currentplanet['galaxy']."',
		`system_angreifer` = '".$currentplanet['system']."',
		`planet_angreifer` = '".$currentplanet['planet']."',
		`owner` = '".$user['id']."',
		`zielid` = '".$ziel_id."',
		`anzahl` = '".$anz."',
		`primaer` = '".$primaerziel."'", 'iraks');


doquery("UPDATE {{table}} SET interplanetary_misil = '".($iraks_anzahl - $anz)."' WHERE id = '".$user['current_planet']."'", 'planets');

	$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

if ($anz == 1)
	$n = "";
else
	$n = "n";


?>
<html>
<head>
<title>Attaque par missiles interplanetaire</title>
<link rel="SHORTCUT ICON" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo $dpath ?>/formate.css" />
<meta http-equiv="refresh" content="3; URL=galaxy.php?mode=3&galaxy=<?php echo $g; ?>&system=<?php echo $s; ?>&target=<?php echo $i; ?>">


</head>
<body>
<br><br><br>
  <center>

<table border="0">
  <tbody><tr>
    <td>
      <table>
        <tbody>
        <tr>
         <td class="c" colspan="1">Attaque par missiles interplanetaire</td>
	</tr>
        <tr>
	<td class="l"><?php echo "<b>".$anz."</b> missiles interplanetaire ".$n." sont".$n." partit !"; ?>
        </tr>
       </tbody></table>
      </td>
      </tr>
     </tbody></table>

</form>


 </body></html>
<?php


// Copyright (c) 2007 by -= MoF =- for Deutsches UGamela Forum
// 05.12.2007 - 11:45
// Open Source

?>
