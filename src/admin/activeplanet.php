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
define('IN_ADMIN', true);
require_once dirname(dirname(__FILE__)) .'/application/bootstrap.php';

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();

if (!in_array($user['authlevel'], array(LEVEL_ADMIN, LEVEL_OPERATOR))) {
    message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
    exit(0);
}

includeLang('admin');

$parse          = $lang;
$parse['dpath'] = $dpath;
$parse['mf']    = $mf;

$PageTPL        = gettemplate('admin/activeplanet_body');
$AllActivPlanet = doquery("SELECT * FROM {{table}} WHERE `last_update` >= '". (time()-15 * 60) ."' ORDER BY `id` ASC", 'planets');
$Count          = 0;

while ($ActivPlanet = $AllActivPlanet->fetch(PDO::FETCH_BOTH)) {
    $parse['online_list'] .= "<tr>";
    $parse['online_list'] .= "<td class=b><center><b>". $ActivPlanet['name'] ."</b></center></td>";
    $parse['online_list'] .= "<td class=b><center><b>[". $ActivPlanet['galaxy'] .":". $ActivPlanet['system'] .":". $ActivPlanet['planet'] ."]</b></center></td>";
    $parse['online_list'] .= "<td class=m><center><b>". pretty_number($ActivPlanet['points'] / 1000) ."</b></center></td>";
    $parse['online_list'] .= "<td class=b><center><b>". pretty_time(time() - $ActivPlanet['last_update']) . "</b></center></td>";
    $parse['online_list'] .= "</tr>";
    $Count++;
}
$parse['online_list'] .= "<tr>";
$parse['online_list'] .= "<th class=\"b\" colspan=\"4\">". $lang['adm_pl_they'] ." ". $Count ." ". $lang['adm_pl_apla'] ."</th>";
$parse['online_list'] .= "</tr>";

$page = parsetemplate( $PageTPL    , $parse );
display( $page, $lang['adm_pl_title'], false, '', true );
