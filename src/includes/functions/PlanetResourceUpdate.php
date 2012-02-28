<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
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
 * @param array $CurrentUser
 * @param array $CurrentPlanet
 * @param int $UpdateTime
 */
function PlanetResourceUpdate($CurrentUser, &$CurrentPlanet, $UpdateTime)
{
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);
    $planet = Legacies_Empire_Model_User::factory($CurrentPlanet['id']);

    /*
     * Update planet resources and constructions
     */
    Wootook::dispatchEvent('planet.update', array(
        'planet' => $planet,
        'time'   => $UpdateTime
        ));
    $planet->save();
}
