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
 * @param unknown_type $FleetRow
 */
function MissionCaseSpy($FleetRow)
{
    global $lang;

    $resource = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

    if ($FleetRow['fleet_start_time'] <= time()) {
// refactored parts
// {{{
        $planetCollection = new Legacies_Core_Collection(array('planet' => 'planets'), 'Legacies_Empire_Model_Planet');
        $planetCollection
            ->where('galaxy=:galaxy')
            ->where('system=:system')
            ->where('planet=:planet')
            ->where('type=:type')
            ->limit(1)
            ->load(array(
                'galaxy'      => $FleetRow['fleet_end_galaxy'],
                'system'      => $FleetRow['fleet_end_system'],
                'planet'      => $FleetRow['fleet_end_planet'],
                'planet_type' => $FleetRow['fleet_end_type']
                ))
        ;
        $TargetPlanet = $planetCollection->current();

        /*
         * Update planet resources and constructions
         */
        Wootook::dispatchEvent('planet.update', array(
            'planet' => $TargetPlanet
            ));
        $TargetPlanet->save();

        $TargetUser = $targetPlanet->getUser();
        $CurrentUser = Legacies_Empire_Model_User::factory($FleetRow['fleet_owner']);

        $TargetUserID = $TargetUser->getId();
        $CurrentUserID = $CurrentUser->getId();

        $TargetTechno = array(
            'military_tech' => $TargetUser->getData('military_tech'),
            'defence_tech'  => $TargetUser->getData('defence_tech'),
            'shield_tech'   => $TargetUser->getData('shield_tech')
            );

        $CurrentTechno = array(
            'military_tech' => $CurrentUser->getData('military_tech'),
            'defence_tech'  => $CurrentUser->getData('defence_tech'),
            'shield_tech'   => $CurrentUser->getData('shield_tech')
            );
// }}}

        $fleet               = explode(";", $FleetRow['fleet_array']);
        $fquery              = "";
        foreach ($fleet as $a => $b) {
            if ($b != '') {
                $a = explode(",", $b);
                $fquery .= "{$resource[$a[0]]}={$resource[$a[0]]} + {$a[1]}, \n";
                if ($FleetRow["fleet_mess"] != "1") {
                    if ($a[0] == "210") {
                        $LS    = $a[1];
                        $QryTargetGalaxy  = "SELECT * FROM {{table}} WHERE ";
                        $QryTargetGalaxy .= "`galaxy` = '". $FleetRow['fleet_end_galaxy'] ."' AND ";
                        $QryTargetGalaxy .= "`system` = '". $FleetRow['fleet_end_system'] ."' AND ";
                        $QryTargetGalaxy .= "`planet` = '". $FleetRow['fleet_end_planet'] ."';";
                        $TargetGalaxy     = doquery( $QryTargetGalaxy, 'galaxy', true);
                        $CristalDebris    = $TargetGalaxy['crystal'];
                        $SpyToolDebris    = $LS * 300;

                        $MaterialsInfo    = SpyTarget ( $TargetPlanet, 0, $lang['sys_spy_maretials'] );
                        $Materials        = $MaterialsInfo['String'];

                        $PlanetFleetInfo  = SpyTarget ( $TargetPlanet, 1, $lang['sys_spy_fleet'] );
                        $PlanetFleet      = $Materials;
                        $PlanetFleet     .= $PlanetFleetInfo['String'];

                        $PlanetDefenInfo  = SpyTarget ( $TargetPlanet, 2, $lang['sys_spy_defenses'] );
                        $PlanetDefense    = $PlanetFleet;
                        $PlanetDefense   .= $PlanetDefenInfo['String'];

                        $PlanetBuildInfo  = SpyTarget ( $TargetPlanet, 3, $lang['tech'][0] );
                        $PlanetBuildings  = $PlanetDefense;
                        $PlanetBuildings .= $PlanetBuildInfo['String'];

                        $TargetTechnInfo  = SpyTarget ( $TargetUser, 4, $lang['tech'][100] );
                        $TargetTechnos    = $PlanetBuildings;
                        $TargetTechnos   .= $TargetTechnInfo['String'];

                        $TargetForce      = ($PlanetFleetInfo['Count'] * $LS) / 4;

                        if ($TargetForce > 100) {
                            $TargetForce = 100;
                        }
                        $TargetChances = rand(0, $TargetForce);
                        $SpyerChances  = rand(0, 100);
                        if ($TargetChances >= $SpyerChances) {
                            $DestProba = sprintf( $lang['sys_mess_spy_lostproba'], $TargetChances);
                        } elseif ($TargetChances < $SpyerChances) {
                            $DestProba = "<font color=\"red\">".$lang['sys_mess_spy_destroyed']."</font>";
                        }
                        $AttackLink = "<center>";
                        $AttackLink .= "<a href=\"fleet.php?galaxy=". $FleetRow['fleet_end_galaxy'] ."&system=". $FleetRow['fleet_end_system'] ."";
                        $AttackLink .= "&planet=".$FleetRow['fleet_end_planet']."";
                        $AttackLink .= "&target_mission=1";
                        $AttackLink .= " \">". $lang['type_mission'][1] ."";
                        $AttackLink .= "</a></center>";


                        $MessageEnd = "<center>".$DestProba."</center>";

                        $pT = ($TargetSpyLvl - $CurrentSpyLvl);
                        $pW = ($CurrentSpyLvl - $TargetSpyLvl);
                        if ($TargetSpyLvl > $CurrentSpyLvl) {
                            $ST = ($LS - pow($pT, 2));
                        }
                        if ($CurrentSpyLvl > $TargetSpyLvl) {
                            $ST = ($LS + pow($pW, 2));
                        }
                        if ($TargetSpyLvl == $CurrentSpyLvl) {
                            $ST = $CurrentSpyLvl;
                        }
                        if ($ST <= "1") {
                            $SpyMessage = $Materials."<br />".$AttackLink.$MessageEnd;
                        }
                        if ($ST == "2") {
                            $SpyMessage = $PlanetFleet."<br />".$AttackLink.$MessageEnd;
                        }
                        if ($ST == "4" or $ST == "3") {
                            $SpyMessage = $PlanetDefense."<br />".$AttackLink.$MessageEnd;
                        }
                        if ($ST == "5" or $ST == "6") {
                            $SpyMessage = $PlanetBuildings."<br />".$AttackLink.$MessageEnd;
                        }
                        if ($ST >= "7") {
                            $SpyMessage = $TargetTechnos."<br />".$AttackLink.$MessageEnd;
                        }

                        SendSimpleMessage ( $CurrentUserID, '', $FleetRow['fleet_start_time'], 0, $lang['sys_mess_qg'], $lang['sys_mess_spy_report'], $SpyMessage);

                        $TargetMessage  = $lang['sys_mess_spy_ennemyfleet'] ." ". $CurrentPlanet['name'];
                        $TargetMessage .= "<a href=\"galaxy.php?mode=3&galaxy=". $CurrentPlanet["galaxy"] ."&system=". $CurrentPlanet["system"] ."\">";
                        $TargetMessage .= "[". $CurrentPlanet["galaxy"] .":". $CurrentPlanet["system"] .":". $CurrentPlanet["planet"] ."]</a> ";
                        $TargetMessage .= $lang['sys_mess_spy_seen_at'] ." ". $TargetPlanet['name'];
                        $TargetMessage .= " [". $TargetPlanet["galaxy"] .":". $TargetPlanet["system"] .":". $TargetPlanet["planet"] ."].";

                        SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 0, $lang['sys_mess_spy_control'], $lang['sys_mess_spy_activity'], $TargetMessage);

                    }
                    if ($TargetChances >= $SpyerChances) {
                        $QryUpdateGalaxy  = "UPDATE {{table}} SET ";
                        $QryUpdateGalaxy .= "`crystal` = `crystal` + '". (0 + $SpyToolDebris) ."' ";
                        $QryUpdateGalaxy .= "WHERE `id_planet` = '". $TargetPlanet['id'] ."';";
                        doquery( $QryUpdateGalaxy, 'galaxy');

                        doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
                    } else {
                        doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
                    }
                }
            } else {
                // Retour de sondes
                if ($FleetRow['fleet_end_time'] <= time()) {
                    RestoreFleetToPlanet ( $FleetRow, true );
                    doquery("DELETE FROM {{table}} WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
                }
            }
        }
    }
}
