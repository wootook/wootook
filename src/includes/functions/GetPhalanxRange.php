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
 * @param unknown_type $PhalanxLevel
 */
function GetPhalanxRange ( $PhalanxLevel ) {
	// Niveau                       1  2  3  4  5  6  7  = lvl
	// Portée ajouté                0  3  5  7  9 11 13  = (lvl * 2) - 1
	// Phalanx en nbre de systemes  0  3  8 15 24 35 48  =
	$PhalanxRange = 0;
	if ($PhalanxLevel > 1) {
		for ($Level = 2; $Level < $PhalanxLevel + 1; $Level++) {
			$lvl           = ($Level * 2) - 1;
			$PhalanxRange += $lvl;
		}
	}
	return $PhalanxRange;
}

?>