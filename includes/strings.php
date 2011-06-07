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

function colorNumber($n, $s = '') {
	if ($n > 0) {
		if ($s != '') {
			$s = colorGreen($s);
		} else {
			$s = colorGreen($n);
		}
	} elseif ($n < 0) {
		if ($s != '') {
			$s = colorRed($s);
		} else {
			$s = colorRed($n);
		}
	} else {
		if ($s != '') {
			$s = $s;
		} else {
			$s = $n;
		}
	}
	return $s;
}

function colorRed($n) {
	return '<font color="#FF0000">' . $n . '</font>';
}

function colorGreen($n) {
	return '<font color="#00FF00">' . $n . '</font>';
}

function pretty_number($n, $floor = true) {
	if ($floor) {
		$n = floor($n);
	}
	return number_format($n, 0, ",", ".");
}