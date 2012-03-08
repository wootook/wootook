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
 * @param unknown_type $planet
 */
function FlyingFleetHandler($planet) {
    global $resource;

    $sql =<<<SQL_EOF
LOCK TABLE
     {{table}}lunas WRITE,
     {{table}}rw WRITE,
     {{table}}errors WRITE,
     {{table}}messages WRITE,
     {{table}}fleets WRITE,
     {{table}}planets WRITE,
     {{table}}galaxy WRITE,
     {{table}}users WRITE
SQL_EOF;
    //doquery($sql, ''); // FIXME: use transactions

    foreach ($planet->getFleetCollection(Wootook::now()) as $CurrentFleet) {
        switch ($CurrentFleet["fleet_mission"]) {
            case Legacies_Empire::ID_MISSION_ATTACK:
                // Attaquer
                MissionCaseAttack ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_TRANSPORT:
                // Transporter
                MissionCaseTransport ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_STATION:
                // Stationner
                MissionCaseStay ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_STATION_ALLY:
                // Stationner chez un Allié
                MissionCaseStayAlly ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_SPY:
                // Flotte d'espionnage
                MissionCaseSpy ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_SETTLE_COLONY:
                // Coloniser
                MissionCaseColonisation ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_RECYCLE:
                // Recyclage
                MissionCaseRecycling ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_DESTROY:
                // Detruire ??? dans le code ogame c'est 9 !!
                MissionCaseDestruction ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_EXPEDITION:
                // Expeditions
                MissionCaseExpedition ( $CurrentFleet );
                break;

            case Legacies_Empire::ID_MISSION_GROUP_ATTACK: // TODO: implement mission type
            case Legacies_Empire::ID_MISSION_MISSILES:     // TODO: implement mission type
            default:
                $CurrentFleet->delete();
                break;
        }
    }

    //doquery("UNLOCK TABLES", ""); // FIXME: use transactions
}