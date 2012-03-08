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
 * @param unknown_type $seconds
 */
function RevisionTime ($seconds) {
	$days      = floor($seconds / 86400);
	$hours     = (floor(($seconds % 86400) / 3600));
	$minutes   = floor(($seconds % 3600) / 60);
	$secs      = $seconds % 60;
	$month_len = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$year      = 1970;
	$done      = 0;
	$month_id  = 1;
	while ($days > $month_lenght) {
		$month_lenght  = ($month_id == 2 ? ($year % 4 == 0 ? 29 : $month_len[$month_id]) : $month_len[$month_id]);
		$days         -= $month_lenght;
		if ($month_id > 12) {
			$month_id = 1;
			$year++;
		} else
			$month_id++;
	}
	$days++;
	$days    = ($days < 10 ? "0" . $days : $days);
	$month   = ($month_id < 10 ? "0" . $month_id : $month_id);
	$hours   = ($hours < 10 ? "0" . $hours : $hours);
	$minutes = ($minutes < 10 ? "0" . $minutes : $minutes);
	$secs    = ($secs < 10 ? "0" . $secs : $secs);
	$ret     = ($seconds > 0 ? "$year-$month-$days<br>GMT $hours:$minutes:$secs" : "");
	return $ret;
}

?>