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
 * @param unknown_type $CurrentUser
 */
    function IsVacationMode($CurrentUser){
       global $gameConfig;
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

       if($CurrentUser['urlaubs_modus'] == 1){
       $query = doquery("SELECT * FROM {{table}} WHERE id_owner = '{$CurrentUser['id']}'", 'planets');
       while ($id = $query->fetch(PDO::FETCH_BOTH)){
          doquery("UPDATE {{table}} SET
                   metal_perhour = '".$gameConfig['metal_basic_income']."',
                   crystal_perhour = '".$gameConfig['crystal_basic_income']."',
                   deuterium_perhour = '".$gameConfig['deuterium_basic_income']."',
                   metal_mine_porcent = '0',
                   crystal_mine_porcent = '0',
                   deuterium_sintetizer_porcent = '0',
                   solar_plant_porcent = '0',
                   fusion_plant_porcent = '0',
                   solar_satelit_porcent = '0'
                 WHERE id = '{$id['id']}' AND `planet_type` = '1' ", 'planets');
          }
          return true;
       }
       return false;
    }
    ?>
