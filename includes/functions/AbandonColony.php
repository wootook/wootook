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

/**
 *
 * @deprecated
 * @param unknown_type $user
 * @param unknown_type $planetrow
 */
function AbandonColony($user,$planetrow) {
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);
       $destruyed = time() + 3600; //Temps avant la suppression dans la galaxie
       $DeleteMoon = false;
       if ($planetrow["planet_type"]==1){
       //Selectionne si il y a une lune sur la colonie a supprim�e
          $QryWithMoon  = "SELECT * FROM {{table}} ";
          $QryWithMoon .= "WHERE ";
          $QryWithMoon .= "`destruyed` = '0' AND ";
          $QryWithMoon .= "`galaxy` = '". $planetrow['galaxy'] ."' AND ";
          $QryWithMoon .= "`system` = '". $planetrow['system'] ."' AND ";
          $QryWithMoon .= "`lunapos` = '". $planetrow['planet'] ."' AND ";
          $QryWithMoon .= "`id_owner` = '". $user['id'] ."' ;";
          $IsMoon = doquery( $QryWithMoon , 'lunas',true);
          if($IsMoon){
          	//Envoi la demande de suppression de la lune associ� a la colonie
                $DeleteMoon = true; // borrar luna
            }

       //Mise en mode destruction d ela colonie
          $QryUpdatePlanet = "UPDATE {{table}} SET ";
          $QryUpdatePlanet .= "`destruyed` = '" . $destruyed . "', ";
          $QryUpdatePlanet .= "`id_owner` = '0' ";
          $QryUpdatePlanet .= "WHERE ";
          $QryUpdatePlanet .= "`id` = '" . $user['current_planet'] . "' LIMIT 1;";
          doquery($QryUpdatePlanet , 'planets');

          //Si on veut supprimer une lune
       }elseif($planetrow["planet_type"]==3){
          $DeleteMoon = true; //borrar luna
       }

       if ($DeleteMoon){
          $QryDeleteMoon = "DELETE FROM {{table}} ";
          $QryDeleteMoon .= "WHERE ";
          $QryDeleteMoon .= "`galaxy` = '". $planetrow['galaxy'] ."' AND ";
          $QryDeleteMoon .= "`system` = '". $planetrow['system'] ."' AND ";
          $QryDeleteMoon .= "`planet` = '". $planetrow['planet'] ."' AND ";
          $QryDeleteMoon .= "`planet_type` = '3' AND ";
          $QryDeleteMoon .= "`id_owner` = '". $user['id'] ."' ;";
          doquery($QryDeleteMoon , 'planets');

          $Qrydestructionlune  = "DELETE FROM {{table}} ";
          $Qrydestructionlune .= "WHERE ";
          $Qrydestructionlune .= "`galaxy` = '". $planetrow['galaxy'] ."' AND ";
          $Qrydestructionlune .= "`system` = '". $planetrow['system'] ."' AND ";
          $Qrydestructionlune .= "`lunapos` = '". $planetrow['planet'] ."' AND ";
          $Qrydestructionlune .= "`id_owner` = '". $user['id'] ."' ;";
          doquery( $Qrydestructionlune , 'lunas');

          $Qrydestructionlune2  = "UPDATE {{table}} SET ";
          $Qrydestructionlune2 .= "`id_luna` = '0' ";
          $Qrydestructionlune2 .= "WHERE ";
          $Qrydestructionlune2 .= "`galaxy` = '". $planetrow['galaxy'] ."' AND ";
          $Qrydestructionlune2 .= "`system` = '". $planetrow['system'] ."' AND ";
          $Qrydestructionlune2 .= "`planet` = '". $planetrow['planet'] ."' ;";
          doquery( $Qrydestructionlune2 , 'galaxy');

       }

    }

    function CheckFleets($planetrow){

       $QryFleet = "SELECT * FROM {{table}} WHERE ";
       $QryFleet .= "(`fleet_start_galaxy` = '".$planetrow["galaxy"]."' AND ";
       $QryFleet .= "`fleet_start_system` = '".$planetrow["system"]."' AND ";
       $QryFleet .= "`fleet_start_planet` = '".$planetrow["planet"]."'";
       if ($planetrow["planet_type"]==3){
          $QryFleet .= " AND `fleet_start_type` = '3'";
       }
       $QryFleet .= ") OR ";
       $QryFleet .= "(`fleet_end_galaxy` = '".$planetrow["galaxy"]."' AND ";
       $QryFleet .= "`fleet_end_system` = '".$planetrow["system"]."' AND ";
       $QryFleet .= "`fleet_end_planet` = '".$planetrow["planet"]."'";
       if ($planetrow["planet_type"]==3){
          $QryFleet .= " AND `fleet_end_type` = '3'";
       }
       $QryFleet .= " AND `fleet_mess` <> 1 ); ";
       $fleets = doquery($QryFleet, 'fleets',true);
       if($fleets){
          return true;
       }
       return false;
    }

    ?>