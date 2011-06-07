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
// Recup des variables
$Attaquant = $_GET['current'];
$NbreMip   = $_POST['SendMI'];

$Galaxy    = $_GET['galaxy'];
$System    = $_GET['system'];
$Planet    = $_GET['planet'];

$PlaneteAttaquant = doquery("SELECT * FROM {{table}} WHERE `id`='" . $Attaquant . "'", "planets", true);
$PlaneteAdverse   = doquery("SELECT * FROM {{table}} WHERE galaxy = " . $Galaxy . " AND system = " . $System . " AND planet = " . $Planet . "", "planets", true);

$MipAttaquant = $PlaneteAttaquant['interplanetary_misil'];
if ($MipAttaquant < $NbreMip) {
    message('Vous ne poss&eacute;dez pas assez de Missilles !', 'Erreur');
}

$AntiMipAdverse = $PlaneteAdverse['interceptor_misil'];
$MipRestant     = $NbreMip - $AntiMipAdverse;
$AntiMipRestant = $$AntiMipAdverse - $NbreMip;

echo $MipRestant;
echo $AntiMipRestant;
// L'attaquant se fait exploser tout ses MIP
if ($MipRestant <= 0) {
    doquery("UPDATE {{table}} SET `interplanetary_misil`='0' WHERE `id`='" . $Attaquant . "'", "planets");
    doquery("UPDATE {{table}} SET `interceptor_misil`='" . $AntiMipRestant . "' WHERE `id`='" . $PlaneteAdverse['id_owner'] . "'", "planets");
    // Message � l'attaquant
    $Owner    = $user['id'];
    $Sender   = "0";
    $Time     = time();
    $Type     = 3;
    $From     = "Quartier G&eacute;n&eacute;ral";
    $Subject  = "Rapport d'attaque par MIP";
    $Message  = "Malheureusement tout vos missiles interplan&eacute;taire ont &eacute;t&eacute; d&eacute;truits par le syst&egrave;me de d&eacute;fense adverse.";
    SendSimpleMessage($Owner, $Sender, $Time, $Type, $From, $Subject, $Message);

    // Message a l'attaqu�
    $Owner2   = $PlaneteAdverse['id_owner'];
    $Message2 = "Vous avez d&eacute;truit " . $NbreMip . " Missiles Interplan&eacute;taire adverse. <br>Il vous reste " . $AntiMipRestant . " Missiles d'interception";
    SendSimpleMessage($Owner2, $Sender, $Time, $Type, $From, $Subject, $Message2);
}

if($MipRestant > 0){
	$Id = $PlaneteAdverse['id'];
	MipAttack($NbreMip, $Id);
}

?>