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

    includeLang('galaxy');

    $CurrentPlanet = doquery("SELECT * FROM {{table}} WHERE `id` = '". $user['current_planet'] ."';", 'planets', true);
    $lunarow       = doquery("SELECT * FROM {{table}} WHERE `id` = '". $user['current_luna'] ."';", 'lunas', true);
    $galaxyrow     = doquery("SELECT * FROM {{table}} WHERE `id_planet` = '". $CurrentPlanet['id'] ."';", 'galaxy', true);

    $dpath         = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];
    $fleetmax      = $user['computer_tech'] + 1;
    $CurrentPlID   = $CurrentPlanet['id'];
    $CurrentMIP    = $CurrentPlanet['interplanetary_misil'];
    $CurrentRC     = $CurrentPlanet['recycler'];
    $CurrentSP     = $CurrentPlanet['spy_sonde'];
    $HavePhalanx   = $CurrentPlanet['phalanx'];
    $CurrentSystem = $CurrentPlanet['system'];
    $CurrentGalaxy = $CurrentPlanet['galaxy'];
    $CanDestroy    = $CurrentPlanet[$resource[213]] + $CurrentPlanet[$resource[214]];

    $maxfleet       = doquery("SELECT * FROM {{table}} WHERE `fleet_owner` = '". $user['id'] ."';", 'fleets');
    $maxfleet_count = mysql_num_rows($maxfleet);

    CheckPlanetUsedFields($CurrentPlanet);
    CheckPlanetUsedFields($lunarow);

    if (!isset($mode)) {
        if (isset($_GET['mode'])) {
            $mode = intval($_GET['mode']);
        } else {
            $mode = 0;
        }
    }

    $galaxy = 1;
    $system = 1;
    $planet = null;

    if ($mode == 0) {
        $galaxy = $CurrentGalaxy;
        $system = $CurrentSystem;
    } elseif ($mode == 1 || $mode == 3) {
        if (isset($_POST["galaxyLeft"])) {
            if (!isset($_POST["galaxy"]) || $_POST["galaxy"] <= 1 || $_POST["galaxy"] > MAX_GALAXY_IN_WORLD) {
                $galaxy = 1;
            } else {
                $galaxy = intval($_POST["galaxy"]) - 1;
            }
        } elseif (isset($_POST["galaxyRight"])) {
            if (!isset($_POST["galaxy"]) || $_POST["galaxy"] >= MAX_GALAXY_IN_WORLD) {
                $galaxy = MAX_GALAXY_IN_WORLD;
            } else {
                $galaxy = intval($_POST["galaxy"]) + 1;
            }
        } else if (!isset($_POST["galaxy"]) || $_POST["galaxy"] <= 1 || $_POST["galaxy"] > MAX_GALAXY_IN_WORLD) {
            $galaxy = 1;
        } else {
            $galaxy = intval($_POST["galaxy"]);
        }

        if (isset($_POST["systemLeft"])) {
            if (!isset($_POST["system"]) || $_POST["system"] <= 1 || $_POST["system"] > MAX_SYSTEM_IN_GALAXY) {
                $system = 1;
            } else {
                $system = intval($_POST["system"]) - 1;
            }
        } elseif (isset($_POST["systemRight"])) {
            if (!isset($_POST["system"]) || $_POST["system"] >= MAX_SYSTEM_IN_GALAXY) {
                $system = MAX_SYSTEM_IN_GALAXY;
            } else {
                $system = intval($_POST["system"]) + 1;
            }
        } else if (!isset($_POST["system"]) || $_POST["system"] <= 1 || $_POST["system"] > MAX_SYSTEM_IN_GALAXY) {
            $system = 1;
        } else {
            $system = intval($_POST["system"]);
        }
    } elseif ($mode == 2) {
        if (!isset($_POST["galaxy"]) || $_POST["galaxy"] <= 0) {
            $galaxy = 1;
        } else if ($_POST["galaxy"] >= MAX_GALAXY_IN_WORLD) {
            $galaxy = MAX_GALAXY_IN_WORLD;
        } else {
            $galaxy = intval($_POST["galaxy"]) + 1;
        }

        if (!isset($_POST["system"]) || $_POST["system"] <= 0) {
            $system = 1;
        } else if ($_POST["system"] >= MAX_SYSTEM_IN_GALAXY) {
            $system = MAX_SYSTEM_IN_GALAXY;
        } else {
            $system = intval($_POST["system"]) + 1;
        }

        if (!isset($_POST["planet"]) || $_POST["planet"] <= 0) {
            $planet = 1;
        } else if ($_POST["planet"] >= MAX_PLANET_IN_SYSTEM) {
            $planet = MAX_PLANET_IN_SYSTEM;
        } else {
            $planet = intval($_POST["planet"]) + 1;
        }
    }

    $planetcount = 0;
    $lunacount   = 0;

    $page = InsertGalaxyScripts($CurrentPlanet);

    $page .= "<body style=\"overflow: hidden;\" onUnload=\"\"><br><br>";
    $page .= ShowGalaxySelector ( $galaxy, $system );

    if ($mode == 2) {
        $page .= ShowGalaxyMISelector($galaxy, $system, $planet, $CurrentPlanet['id'], $CurrentMIP);
    }

    $page .= "<table width=569><tbody>";

    $page .= ShowGalaxyTitles($galaxy, $system);
    $page .= ShowGalaxyRows($galaxy, $system);
    $page .= ShowGalaxyFooter($galaxy, $system,  $CurrentMIP, $CurrentRC, $CurrentSP);

    $page .= "</tbody></table></div>";

    display ($page, $lang[''], false, '', false);

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Created by Perberos
// 1.1 - Modified by -MoF- (UGamela germany)
// 1.2 - 1er Nettoyage Chlorel ...
// 1.3 - 2eme Nettoyage Chlorel ... Mise en fonction et debuging complet
?>