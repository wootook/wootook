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
 * @param unknown_type $lunarow
 */
function CheckAbandonMoonState ($lunarow) {
	if (($lunarow['destruyed'] + 172800) <= time() && $lunarow['destruyed'] != 0) {
		$query = doquery("DELETE FROM {{table}} WHERE id = '" . $lunarow['id'] . "'", "lunas");
	}
}

/**
 * Suppression complete d'une planete
 *
 * @deprecated
 */
function CheckAbandonPlanetState (&$planet) {
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);
	if ($planet['destruyed'] <= time()) {
		$id = $planet['id'];
		doquery("DELETE FROM {{table}} WHERE id={$id}", 'planets');
		doquery("DELETE FROM {{table}} WHERE id_planet={$id}", 'galaxy');
	}
}


?>