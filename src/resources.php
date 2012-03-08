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

function BuildRessourcePage($CurrentUser, $CurrentPlanet ) {
    global $lang;

    includeLang('resources');

    $RessBodyTPL = gettemplate('resources');
    $RessRowTPL  = gettemplate('resources_row');

    $request = Wootook::getRequest();
    if ($request->isPost()) {
        foreach ($request->getPost() as $buildingField => $value) {
            $ratioField = "{$buildingField}_porcent";
            if ($CurrentPlanet->hasData($ratioField)) {
                if ($value < 0 && $value > 100) {
                    continue;
                }

                $CurrentPlanet->setData($ratioField, $value / 10);
            }
        }
        $CurrentPlanet->save();
    }

    $parse  = $lang;

    $parse['production_level'] = 100;
    if ($CurrentPlanet['energy_max'] == 0 && $CurrentPlanet['energy_used'] > 0) {
        $post_porcent = 0;
    } elseif ($CurrentPlanet['energy_max'] > 0 && ($CurrentPlanet['energy_used'] + $CurrentPlanet['energy_max']) < 0 ) {
        $post_porcent = floor(($CurrentPlanet['energy_max']) / $CurrentPlanet['energy_used'] * 100);
    } else {
        $post_porcent = 100;
    }

    if ($post_porcent > 100) {
        $post_porcent = 100;
    }
    if ($post_porcent < 0) {
        $post_porcent = 0;
    }
    // -------------------------------------------------------------------------------------------------------
    $parse['resource_row']               = "";
    $BuildTemp                           = $CurrentPlanet[ 'temp_max' ];

    $types = Wootook_Empire_Helper_Config_Types::getSingleton();
    $productions = Wootook_Empire_Helper_Config_Production::getSingleton();
    $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();
    foreach ($types['prod'] as $element) {
        // $ProdID => $element
        // $ProdGrid => $productions
        if ($CurrentPlanet->getElement($element) > 0/* && isset($productions[$element])*/) {
            $building = $CurrentPlanet->getProductionElementInstance($element);

            $Field                               = $fields[$element] ."_porcent";
            $CurrRow                             = array();
            $CurrRow['name']                     = $fields[$element];
            $CurrRow['porcent']                  = $CurrentPlanet[$Field];
            $level = $CurrentPlanet->getElement($element);
            if ($CurrentPlanet->hasData($Field)) {
                for ($Option = 10; $Option >= 0; $Option--) {
                    $OptValue = $Option * 10;
                    if ($Option == $CurrRow['porcent']) {
                        $OptSelected    = " selected=selected";
                    } else {
                        $OptSelected    = "";
                    }
                    $CurrRow['option'] .= "<option value=\"".$OptValue."\"".$OptSelected.">".$OptValue."%</option>";
                }
                $productions = $building->getProductionRatios($level, $CurrentPlanet[$Field], $CurrentPlanet, $CurrentUser);
            } else {
                $productions = $building->getProductionRatios($level, 10, $CurrentPlanet, $CurrentUser);
            }

            $CurrRow['type']                     = $lang['tech'][$element];
            $CurrRow['level']                    = !$types->is($element, Legacies_Empire::TYPE_BUILDING) ? $lang['quantity'] : $lang['level'];
            $CurrRow['level_type']               = $level;
            $CurrRow['metal_type']               = colorNumber(Math::render($productions[Legacies_Empire::RESOURCE_METAL]));
            $CurrRow['crystal_type']             = colorNumber(Math::render($productions[Legacies_Empire::RESOURCE_CRISTAL]));
            $CurrRow['deuterium_type']           = colorNumber(Math::render($productions[Legacies_Empire::RESOURCE_DEUTERIUM]));
            $CurrRow['energy_type']              = colorNumber(Math::render($productions[Legacies_Empire::RESOURCE_ENERGY]));

            $parse['resource_row']              .= parsetemplate($RessRowTPL, $CurrRow );
        }
    }

    $parse['Production_of_resources_in_the_planet'] =
    str_replace('%s', $CurrentPlanet['name'], $lang['Production_of_resources_in_the_planet']);
    if       ($CurrentPlanet['energy_max'] == 0 &&
        $CurrentPlanet['energy_used'] > 0) {
        $parse['production_level'] = 0;
    } elseif ($CurrentPlanet['energy_max']  > 0 &&
        abs($CurrentPlanet['energy_used']) > $CurrentPlanet['energy_max']) {
        $parse['production_level'] = floor(($CurrentPlanet['energy_max']) / $CurrentPlanet['energy_used'] * 100);
    } elseif ($CurrentPlanet['energy_max'] == 0 &&
        abs($CurrentPlanet['energy_used']) > $CurrentPlanet['energy_max']) {
        $parse['production_level'] = 0;
    } else {
        $parse['production_level'] = 100;
    }
    if ($parse['production_level'] > 100) {
        $parse['production_level'] = 100;
    }

    if ($CurrentPlanet['planet_type'] == Wootook_Empire_Model_Planet::TYPE_PLANET) {
        $multiplier = Wootook::getGameConfig('game/resource/multiplier');
        $parse['metal_basic_income']     = Wootook::getGameConfig('resource/base-income/metal') * $multiplier;
        $parse['crystal_basic_income']   = Wootook::getGameConfig('resource/base-income/cristal') * $multiplier;
        $parse['deuterium_basic_income'] = Wootook::getGameConfig('resource/base-income/deuterium') * $multiplier;
        $parse['energy_basic_income']    = Wootook::getGameConfig('resource/base-income/energy') * $multiplier;
    } else {
        $parse['metal_basic_income']     = 0;
        $parse['crystal_basic_income']   = 0;
        $parse['deuterium_basic_income'] = 0;
        $parse['energy_basic_income']    = 0;
    }

    if ($CurrentPlanet['metal_max'] < $CurrentPlanet['metal']) {
        $parse['metal_max']         = "<font color=\"#ff0000\">";
    } else {
        $parse['metal_max']         = "<font color=\"#00ff00\">";
    }
    $parse['metal_max']            .= pretty_number($CurrentPlanet['metal_max'] / 1000) ." ". $lang['k']."</font>";

    if ($CurrentPlanet['cristal_max'] < $CurrentPlanet['cristal']) {
        $parse['crystal_max']       = "<font color=\"#ff0000\">";
    } else {
        $parse['crystal_max']       = "<font color=\"#00ff00\">";
    }
    $parse['crystal_max']          .= pretty_number($CurrentPlanet['cristal_max'] / 1000) ." ". $lang['k']."</font>";

    if ($CurrentPlanet['deuterium_max'] < $CurrentPlanet['deuterium']) {
        $parse['deuterium_max']     = "<font color=\"#ff0000\">";
    } else {
        $parse['deuterium_max']     = "<font color=\"#00ff00\">";
    }
    $parse['deuterium_max']        .= pretty_number($CurrentPlanet['deuterium_max'] / 1000) ." ". $lang['k']."</font>";

    $parse['metal_total']           = colorNumber( pretty_number( floor( ( $CurrentPlanet['metal_perhour']     * 0.01 * $parse['production_level'] ) + $parse['metal_basic_income'])));
    $parse['crystal_total']         = colorNumber( pretty_number( floor( ( $CurrentPlanet['cristal_perhour']   * 0.01 * $parse['production_level'] ) + $parse['crystal_basic_income'])));
    $parse['deuterium_total']       = colorNumber( pretty_number( floor( ( $CurrentPlanet['deuterium_perhour'] * 0.01 * $parse['production_level'] ) + $parse['deuterium_basic_income'])));
    $parse['energy_total']          = colorNumber( pretty_number( floor( ( $CurrentPlanet['energy_max'] + $parse['energy_basic_income']    ) + $CurrentPlanet['energy_used'] ) ) );

    $parse['daily_metal']           = floor($CurrentPlanet['metal_perhour']     * 24      * 0.01 * $parse['production_level'] + $parse['metal_basic_income']     * 24      );
    $parse['weekly_metal']          = floor($CurrentPlanet['metal_perhour']     * 24 * 7  * 0.01 * $parse['production_level'] + $parse['metal_basic_income']     * 24 * 7  );
    $parse['monthly_metal']         = floor($CurrentPlanet['metal_perhour']     * 24 * 30 * 0.01 * $parse['production_level'] + $parse['metal_basic_income']     * 24 * 30 );

    $parse['daily_crystal']         = floor($CurrentPlanet['cristal_perhour']   * 24      * 0.01 * $parse['production_level'] + $parse['crystal_basic_income']   * 24      );
    $parse['weekly_crystal']        = floor($CurrentPlanet['cristal_perhour']   * 24 * 7  * 0.01 * $parse['production_level'] + $parse['crystal_basic_income']   * 24 * 7  );
    $parse['monthly_crystal']       = floor($CurrentPlanet['cristal_perhour']   * 24 * 30 * 0.01 * $parse['production_level'] + $parse['crystal_basic_income']   * 24 * 30 );

    $parse['daily_deuterium']       = floor($CurrentPlanet['deuterium_perhour'] * 24      * 0.01 * $parse['production_level'] + $parse['deuterium_basic_income'] * 24      );
    $parse['weekly_deuterium']      = floor($CurrentPlanet['deuterium_perhour'] * 24 * 7  * 0.01 * $parse['production_level'] + $parse['deuterium_basic_income'] * 24 * 7  );
    $parse['monthly_deuterium']     = floor($CurrentPlanet['deuterium_perhour'] * 24 * 30 * 0.01 * $parse['production_level'] + $parse['deuterium_basic_income'] * 24 * 30 );

    $parse['daily_metal']           = colorNumber(pretty_number($parse['daily_metal']));
    $parse['weekly_metal']          = colorNumber(pretty_number($parse['weekly_metal']));
    $parse['monthly_metal']         = colorNumber(pretty_number($parse['monthly_metal']));

    $parse['daily_crystal']         = colorNumber(pretty_number($parse['daily_crystal']));
    $parse['weekly_crystal']        = colorNumber(pretty_number($parse['weekly_crystal']));
    $parse['monthly_crystal']       = colorNumber(pretty_number($parse['monthly_crystal']));

    $parse['daily_deuterium']       = colorNumber(pretty_number($parse['daily_deuterium']));
    $parse['weekly_deuterium']      = colorNumber(pretty_number($parse['weekly_deuterium']));
    $parse['monthly_deuterium']     = colorNumber(pretty_number($parse['monthly_deuterium']));

    $parse['metal_storage']         = floor($CurrentPlanet['metal']     / $CurrentPlanet['metal_max']     * 100) . $lang['o/o'];
    $parse['crystal_storage']       = floor($CurrentPlanet['cristal']   / $CurrentPlanet['cristal_max']   * 100) . $lang['o/o'];
    $parse['deuterium_storage']     = floor($CurrentPlanet['deuterium'] / $CurrentPlanet['deuterium_max'] * 100) . $lang['o/o'];
    $parse['metal_storage_bar']     = floor(($CurrentPlanet['metal']     / $CurrentPlanet['metal_max']     * 100) * 2.5);
    $parse['crystal_storage_bar']   = floor(($CurrentPlanet['cristal']   / $CurrentPlanet['cristal_max']   * 100) * 2.5);
    $parse['deuterium_storage_bar'] = floor(($CurrentPlanet['deuterium'] / $CurrentPlanet['deuterium_max'] * 100) * 2.5);

    if ($parse['metal_storage_bar'] > (100 * 2.5)) {
        $parse['metal_storage_bar'] = 250;
        $parse['metal_storage_barcolor'] = '#C00000';
    } elseif ($parse['metal_storage_bar'] > (80 * 2.5)) {
        $parse['metal_storage_barcolor'] = '#C0C000';
    } else {
        $parse['metal_storage_barcolor'] = '#00C000';
    }

    if ($parse['crystal_storage_bar'] > (100 * 2.5)) {
        $parse['crystal_storage_bar'] = 250;
        $parse['crystal_storage_barcolor'] = '#C00000';
    } elseif ($parse['crystal_storage_bar'] > (80 * 2.5)) {
        $parse['crystal_storage_barcolor'] = '#C0C000';
    } else {
        $parse['crystal_storage_barcolor'] = '#00C000';
    }

    if ($parse['deuterium_storage_bar'] > (100 * 2.5)) {
        $parse['deuterium_storage_bar'] = 250;
        $parse['deuterium_storage_barcolor'] = '#C00000';
    } elseif ($parse['deuterium_storage_bar'] > (80 * 2.5)) {
        $parse['deuterium_storage_barcolor'] = '#C0C000';
    } else {
        $parse['deuterium_storage_barcolor'] = '#00C000';
    }

    $parse['production_level_bar'] = $parse['production_level'] * 2.5;
    $parse['production_level']     = "{$parse['production_level']}%";
    $parse['production_level_barcolor'] = '#00ff00';

    $QryUpdatePlanet  = "UPDATE {{table}} SET ";
    $QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."' ";
    $QryUpdatePlanet .= $SubQry;
    $QryUpdatePlanet .= "WHERE ";
    $QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."';";
    doquery( $QryUpdatePlanet, 'planets');

    $page = parsetemplate( $RessBodyTPL, $parse );

    return $page;
}

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$planet = $user->getCurrentPlanet();

$Page = BuildRessourcePage($user, $planet);
display($Page, $lang['Resources']);

// -----------------------------------------------------------------------------------------------------------
// History version
// 1.0 - Passage en fonction pour utilisation Wootook
?>
