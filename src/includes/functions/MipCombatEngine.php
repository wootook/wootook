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
 * @param unknown_type $NbreMip
 * @param unknown_type $IDAversaire
 */
function MipAttack ($NbreMip, $IDAversaire) {
    $TechnoArme = doquery("SELECT * FROM {{table}} WHERE `id`='" . $user['id'] . "'", "users");
    $InfoAdversaire = doquery("SELECT * FROM {{table}} WHERE `id`='" . $IDAversaire . "'", "planets", true);

    $PuissanceAttaque = ($NbreMip * 12000) * (1.05 * $TechnoArme['military_tech']);

/*

401 => 'Lanceur de missiles',
402 => 'Artillerie laser l&eacute;g&egrave;re',
403 => 'Artillerie laser lourde',
404 => 'Canon de Gauss',
405 => 'Artillerie &agrave; ions',
406 => 'Lanceur de plasma',
407 => 'Petit bouclier',
408 => 'Grand bouclier',

*/

    $TableauDeDefense = array(
		401 => array ('shield' => 20),
        402 => array ('shield' => 25),
        403 => array ('shield' => 100),
        404 => array ('shield' => 200),
        405 => array ('shield' => 500),
        406 => array ('shield' => 300),
        407 => array ('shield' => 2000),
        408 => array ('shield' => 2000)
    );

    $DefenseAdversaire = array(
		401 => ($InfoAdversaire['misil_launcher']),
        402 => ($InfoAdversaire['small_laser']),
        403 => ($InfoAdversaire['big_laser']),
        404 => ($InfoAdversaire['gauss_canyon']),
        405 => ($InfoAdversaire['ionic_canyon']),
        406 => ($InfoAdversaire['buster_canyon']),
        407 => ($InfoAdversaire['small_protection_shield']),
        408 => ($InfoAdversaire['big_protection_shield'])
	);

    while ($PuissanceAttaque > 20) {
        $RandomDefense = rand(401, 408);

        $SelectionDefense = $DefenseAdversaire[$RandomDefense];
        if ($SelectionDefense > 0) {
            if ($PuissanceAttaque > ($SelectionDefense * $TableauDeDefense[$RandomDefense])) {
                $PuissanceAttaque = $PuissanceAttaque - ($SelectionDefense * $TableauDeDefense[$RandomDefense]);
                $DefenseAdversaire[$RandomDefense] = $DefenseAdversaire[$RandomDefense] - 1;
            }
        }
    }

    $SqlDefenseur = "UPDATE {{table}} SET ";
    $SqlDefenseur .= "`small_laser`='".$DefenseAdversaire[402]."', ";
    $SqlDefenseur .= "`big_laser`='".$DefenseAdversaire[403]."', ";
    $SqlDefenseur .= "`gauss_canyon`='".$DefenseAdversaire[404]."', ";
    $SqlDefenseur .= "`ionic_canyon`='".$DefenseAdversaire[405]."', ";
    $SqlDefenseur .= "`buster_canyon`='".$DefenseAdversaire[406]."', ";
    $SqlDefenseur .= "`small_protection_shield`='".$DefenseAdversaire[407]."', ";
    $SqlDefenseur .= "`big_protection_shield`='".$DefenseAdversaire[408]."' ";
    $SqlDefenseur .= " WHERE `id`='".$IDAversaire."'";

    doquery($SqlDefenseur, 'planets');

}

?>