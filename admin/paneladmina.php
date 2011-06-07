<?php
/**
 * Tis file is part of XNova:Legacies
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
define('IN_ADMIN', true);

require_once dirname(dirname(__FILE__)) .'/common.php';

	if (in_array($user['authlevel'], array(LEVEL_ADMIN, LEVEL_OPERATOR, LEVEL_MODERATOR))) {

    includeLang('admin/adminpanel');

    $PanelMainTPL = gettemplate('admin/admin_panel_main');

    $parse                  = $lang;
    $parse['adm_sub_form1'] = "";
    $parse['adm_sub_form2'] = "";
    $parse['adm_sub_form3'] = "";

    // Afficher les templates
    if (isset($_GET['result'])) {

        switch ($_GET['result']){
            case 'usr_search':
                $pattern = mysql_real_escape_string($_GET['player']);
                $SelUser = doquery("SELECT * FROM {{table}} WHERE `username` LIKE '%". $pattern ."%' LIMIT 1;", 'users', true);
                $UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $SelUser['id_planet'] ."';", 'planets', true);

                $bloc                   = $lang;
                $bloc['answer1']        = $SelUser['id'];
                $bloc['answer2']        = $SelUser['username'];
                $bloc['answer3']        = $SelUser['user_lastip'];
                $bloc['answer4']        = $SelUser['email'];
                $bloc['answer5']        = $lang['adm_usr_level'][ $SelUser['authlevel'] ];
                $bloc['answer6']        = $lang['adm_usr_genre'][ $SelUser['sex'] ];
                $bloc['answer7']        = "[".$SelUser['id_planet']."] ".$UsrMain['name'];
                $bloc['answer8']        = "[".$SelUser['galaxy'].":".$SelUser['system'].":".$SelUser['planet']."] ";
                $SubPanelTPL            = gettemplate('admin/admin_panel_asw1');
                $parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
                break;

            case 'usr_data':
                $pattern = mysql_real_escape_string($_GET['player']);
                $SelUser = doquery("SELECT * FROM {{table}} WHERE `username` LIKE '%". $pattern ."%' LIMIT 1;", 'users', true);
                $UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $SelUser['id_planet'] ."';", 'planets', true);

                $bloc                    = $lang;
                $bloc['answer1']         = $SelUser['id'];
                $bloc['answer2']         = $SelUser['username'];
                $bloc['answer3']         = $SelUser['user_lastip'];
                $bloc['answer4']         = $SelUser['email'];
                $bloc['answer5']         = $lang['adm_usr_level'][ $SelUser['authlevel'] ];
                $bloc['answer6']         = $lang['adm_usr_genre'][ $SelUser['sex'] ];
                $bloc['answer7']         = "[".$SelUser['id_planet']."] ".$UsrMain['name'];
                $bloc['answer8']         = "[".$SelUser['galaxy'].":".$SelUser['system'].":".$SelUser['planet']."] ";
                $SubPanelTPL             = gettemplate('admin/admin_panel_asw1');
                $parse['adm_sub_form1']  = parsetemplate( $SubPanelTPL, $bloc );

                $parse['adm_sub_form2']  = "<table><tbody>";
                $parse['adm_sub_form2'] .= "<tr><td colspan=\"4\" class=\"c\">".$lang['adm_colony']."</td></tr>";
                $UsrColo = doquery("SELECT * FROM {{table}} WHERE `id_owner` = '". $SelUser['id'] ." ORDER BY `galaxy` ASC, `planet` ASC, `system` ASC, `planet_type` ASC';", 'planets');
                while ( $Colo = mysql_fetch_assoc($UsrColo) ) {
                    if ($Colo['id'] != $SelUser['id_planet']) {
                        $parse['adm_sub_form2'] .= "<tr><th>".$Colo['id']."</th>";
                        $parse['adm_sub_form2'] .= "<th>". (($Colo['planet_type'] == 1) ? $lang['adm_planet'] : $lang['adm_moon'] ) ."</th>";
                        $parse['adm_sub_form2'] .= "<th>[".$Colo['galaxy'].":".$Colo['system'].":".$Colo['planet']."]</th>";
                        $parse['adm_sub_form2'] .= "<th>".$Colo['name']."</th></tr>";
                    }
                }
                $parse['adm_sub_form2'] .= "</tbody></table>";

                $parse['adm_sub_form3']  = "<table><tbody>";
                $parse['adm_sub_form3'] .= "<tr><td colspan=\"4\" class=\"c\">".$lang['adm_technos']."</td></tr>";
                for ($Item = 100; $Item <= 199; $Item++) {
                    if ($resource[$Item] != "") {
                        $parse['adm_sub_form3'] .= "<tr><th>".$lang['tech'][$Item]."</th>";
                        $parse['adm_sub_form3'] .= "<th>".$SelUser[$resource[$Item]]."</th></tr>";
                    }
                }
                $parse['adm_sub_form3'] .= "</tbody></table>";
                break;

            case 'usr_level':
                if (!isset($_GET['s']) || !isset($_SESSION['CSRF']) || $_GET['s'] !== $_SESSION['CSRF']) {
                    AdminMessage(
                        'One have tried to overcome administration privilleges.',
                        'Hacking attempt');
                    break;
                }

                $player = isset($_GET['player']) ? mysql_real_escape_string($_GET['player']) : '';
                $level  = isset($_GET['authlvl']) ? mysql_real_escape_string($_GET['authlvl']) : '';

                if ($level >= $user['authlevel'] && $user['authlevel'] != LEVEL_ADMIN) {
                    AdminMessage('Not enough privilleges to promote user.', $lang['adm_mod_level']);
                    break;
                }

                $userData = doquery("SELECT id FROM {{table}} WHERE `username` = '".$player."';", 'users', true);
                if (empty($user)) {
                    AdminMessage('No such user.', $lang['adm_mod_level']);
                    break;
                }

                doquery("UPDATE {{table}} SET `authlevel` = '{$level}' WHERE id={$userData['id']}", 'users');
                $message    = $lang['adm_mess_lvl1']. " ". $player ." ".$lang['adm_mess_lvl2'];
                $message   .= "<font color=\"red\">".$lang['adm_usr_level'][$level]."</font>!";

                AdminMessage($message, $lang['adm_mod_level']);
                break;

            case 'ip_search':
                $pattern = isset($_GET['ip']) ? mysql_real_escape_string($_GET['ip']) : '';
                $SelUser    = doquery("SELECT * FROM {{table}} WHERE `user_lastip` = '". $pattern ."' LIMIT 10;", 'users');
                $bloc                   = $lang;
                $bloc['adm_this_ip']    = $pattern;
                while ( $Usr = mysql_fetch_assoc($SelUser) ) {
                    $UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $Usr['id_planet'] ."';", 'planets', true);
                    $bloc['adm_plyer_lst'] .= "<tr><th>".$Usr['username']."</th><th>[".$Usr['galaxy'].":".$Usr['system'].":".$Usr['planet']."] ".$UsrMain['name']."</th></tr>";
                }
                $SubPanelTPL            = gettemplate('admin/admin_panel_asw2');
                $parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
                break;
            default:
                break;
        }
    }

    // Traiter les reponses aux formulaires
    if (isset($_GET['action'])) {
        $bloc = $lang;

        $_SESSION['CSRF'] = sha1(uniqid(null, true));
        $bloc['csrf_hack'] = $_SESSION['CSRF'];

        switch ($_GET['action']){
            case 'usr_search':
                $SubPanelTPL            = gettemplate('admin/admin_panel_frm1');
                break;

            case 'usr_data':
                $SubPanelTPL            = gettemplate('admin/admin_panel_frm4');
                break;

            case 'usr_level':
                for ($Lvl = 0; $Lvl < 4; $Lvl++) {
                    $bloc['adm_level_lst'] .= "<option value=\"". $Lvl ."\">". $lang['adm_usr_level'][ $Lvl ] ."</option>";
                }
                $SubPanelTPL       = gettemplate('admin/admin_panel_frm3');
                break;

            case 'ip_search':
                $SubPanelTPL            = gettemplate('admin/admin_panel_frm2');
                break;

            default:
                break;
        }
        $parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
    }

    $page = parsetemplate( $PanelMainTPL, $parse );
    display( $page, $lang['panel_mainttl'], false, '', true );
} else {
    message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
