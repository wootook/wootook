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

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';


$Id                  = $_GET['techid'];
$PageTPL             = gettemplate('techtree_details');
$RowsTPL             = gettemplate('techtree_details_rows');

$parse               = $lang;
$parse['te_dt_id']   = $Id;
$parse['te_dt_name'] = $lang['tech'][$Id];
$Liste = "";

if ($Id == 12) {
    $Liste .= "<tr><th>".$lang['tech']['31']." (".$lang['level']." 1)</th></tr>";
    $Liste .= "<tr><td class=\"c\">2</td><tr>";
    $Liste .= "<tr><th>".$lang['tech']['3']." (".$lang['level']." 5)</th></tr>";
    $Liste .= "<tr><th>".$lang['tech']['113']." (".$lang['level']." 3) <a href=\"techtreedetails.php?tech=113\">[i]</a></th></tr>";
}

$parse['Liste'] = $Liste;
$page = parsetemplate($PageTPL, $parse);

display ($page, $lang['Tech'], false, '', false);

?>