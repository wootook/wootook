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

$CombatCaps = Wootook_Empire_Helper_Config_Combat::getSingleton();

$AtFleet = array ( 203 => 2 );
$AtTechn = array ( 109 => 3, 110 => 5, 111 => 3 );
$AtCount = 1;

$DfFleet = array ( 402 => 9 );
$DfTechn = array ( 109 => 7, 110 => 7, 111 => 5 );
$DfCount = 1;


// Données en entrée :
// $AttackFleet -> array de tableaux des flottes attaquantes
// $AttackTech  -> array de tableaux des technologies des attaquants ainsi que leur id
// $AttackCount -> Nbre d'attaquants  ( de 1 a 5 )
// $TargetFleet -> array de tableaux des flottes defenseurs
// $TargetTech  -> array de tableaux des technologies des defenseurs ainsi que leur id
// $TargetCount -> Nbre de defenseurs ( de 1 a 5 )
//
function FleetCombat ( $AttackFleet, $AttackTech, $AttackCount, $TargetFleet, $TargetTech, $TargetCount ) {

}

function GetWeaponsPerType ( $TypeArray, $Tech ) {
	global $capacity;
	// Calcul de la force d'Attaque
	if (!is_null($TypeArray)) {
		foreach($TypeArray as $Type => $Count) {
			$Attack[$Type]      = round ($capacity[$Type]['attack'] + (($capacity[$Type]['attack'] * $Tech['109']) / 10));
			$Units['attack']   += $Count * $Attack[$Type];
		}
	}

}

function GetShiedsPerType ( $TypeArray, $Tech ) {
	global $capacity;
	// Calcul des points de Bouclier
	if (!is_null($TypeArray)) {
		foreach($TypeArray as $Type => $Count) {
			$Shield[$Type]      = round ($capacity[$Type]['shield'] + (($capacity[$Type]['shield'] * $Tech['110']) / 10));
			$Units['shield']   += $Count * $Shield[$Type];
		}
	}

}

function GetHullPerType ( $TypeArray, $Tech ) {
	global $pricelist;
	// Calcul des points de Coque
	if (!is_null($TypeArray)) {
		$Units['metal']     = 0;
		$Units['crystal']   = 0;
		$Units['deuterium'] = 0;
		foreach($TypeArray as $Type => $Count) {
			$Hull[$Type]         = ($pricelist[$Type]['metal'] + $pricelist[$Type]['crystal']) + ((($pricelist[$Type]['metal'] + $pricelist[$Type]['crystal']) * $Tech['111']) / 10);
			$Units['metal']     += $Count * $pricelist[$Type]['metal'];
			$Units['crystal']   += $Count * $pricelist[$Type]['crystal'];
			$Units['deuterium'] += $Count * $pricelist[$Type]['deuterium'];
		}
	}

}

?>