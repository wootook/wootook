<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
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
 * @param unknown_type $CurrentUser
 * @param unknown_type $CurrentPlanet
 * @param unknown_type $Element
 * @param unknown_type $Incremental
 * @param unknown_type $ForDestroy
 */
function IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, $Incremental = true, $ForDestroy = false) {
    global $pricelist, $resource;
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (IsVacationMode($CurrentUser)) {
        return false;
    }

    if ($Incremental) {
        $level  = ($CurrentPlanet[$resource[$Element]]) ? $CurrentPlanet[$resource[$Element]] : $CurrentUser[$resource[$Element]];
    }

    $array = array(
        Legacies_Empire::RESOURCE_METAL,
        Legacies_Empire::RESOURCE_CRISTAL,
        Legacies_Empire::RESOURCE_DEUTERIUM,
        'energy_max'
        );

    $cost = array();
    foreach ($array as $ResType) {
        if ($pricelist[$Element][$ResType] != 0) {
            if ($Incremental) {
                $cost[$ResType] = bcmul($pricelist[$Element][$ResType], bcpow($pricelist[$Element]['factor'], $level), 0);
            } else {
                $cost[$ResType] = $pricelist[$Element][$ResType];
            }

            if ($ForDestroy) {
                $cost[$ResType]  = bcdiv($cost[$ResType], 2, 0);
            }

            if (bccomp($cost[$ResType], $CurrentPlanet[$ResType]) > 0) {
                return false;
            }
        }
    }
    return true;
}
