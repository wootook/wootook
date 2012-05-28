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

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();

includeLang('options');

$lang['PHP_SELF'] = 'options.' . PHPEXT;

$mode = isset($_GET['mode']) ? $_GET['mode'] : null;

    if (!empty($_POST) && $mode == "exit") { // Array ( [db_character]
       if (isset($_POST["exit_modus"]) && !empty($_POST["exit_modus"]) && $user->getVacation() && $user->getVacationEndDate() <= time()) {
           $user->setVacation(false);
           message($lang['succeful_save'], $lang['Options'],"options.php",1);
       } else {
           message($lang['You_cant_exit_vmode'], $lan['Error'] ,"options.php",1);
       }
    }

    if ($_POST && $mode == "change") { // Array ( [db_character]
       $iduser = $user["id"];
       $avatar = $_POST["avatar"];

	   if ($_POST["dpath"] != "")
			$dpath = $_POST["dpath"];
		else
			$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

       // Gestion des options speciales pour les admins
       if ($user['authlevel'] != LEVEL_PLAYER) {
          if ($_POST['adm_pl_prot'] == 'on') {
             doquery ("UPDATE {{table}} SET `id_level` = '".$user['authlevel']."' WHERE `id_owner` = '".$user['id']."';", 'planets');
          } else {
             doquery ("UPDATE {{table}} SET `id_level` = '0' WHERE `id_owner` = '".$user['id']."';", 'planets');
          }
       }

       // Mostrar skin
       if (isset($_POST["design"]) && $_POST["design"] == 'on') {
          $design = "1";
       } else {
          $design = "0";
       }
       // Desactivar comprobaci? de IP
       if (isset($_POST["noipcheck"]) && $_POST["noipcheck"] == 'on') {
          $noipcheck = "1";
       } else {
          $noipcheck = "0";
       }
       // Nombre de usuario
       if (isset($_POST["db_character"]) && $_POST["db_character"] != '') {
          $username = CheckInputStrings ( $_POST['db_character'] );
       } else {
          $username = $user['username'];
       }
       // Adresse e-Mail
       if (isset($_POST["db_email"]) && $_POST["db_email"] != '') {
          $db_email = CheckInputStrings ( $_POST['db_email'] );
       } else {
          $db_email = $user['email'];
       }
       // Cantidad de sondas de espionaje
       if (isset($_POST["spio_anz"]) && is_numeric($_POST["spio_anz"])) {
          $spio_anz = $_POST["spio_anz"];
       } else {
          $spio_anz = "1";
       }
       // Mostrar tooltip durante
       if (isset($_POST["settings_tooltiptime"]) && is_numeric($_POST["settings_tooltiptime"])) {
          $settings_tooltiptime = $_POST["settings_tooltiptime"];
       } else {
          $settings_tooltiptime = "1";
       }
       // Maximo mensajes de flotas
       if (isset($_POST["settings_fleetactions"]) && is_numeric($_POST["settings_fleetactions"])) {
          $settings_fleetactions = $_POST["settings_fleetactions"];
       } else {
          $settings_fleetactions = "1";
       } //
       // Mostrar logos de los aliados
       if (isset($_POST["settings_allylogo"]) && $_POST["settings_allylogo"] == 'on') {
          $settings_allylogo = "1";
       } else {
          $settings_allylogo = "0";
       }
       // Espionaje
       if (isset($_POST["settings_esp"]) && $_POST["settings_esp"] == 'on') {
          $settings_esp = "1";
       } else {
          $settings_esp = "0";
       }
       // Escribir mensaje
       if (isset($_POST["settings_wri"]) && $_POST["settings_wri"] == 'on') {
          $settings_wri = "1";
       } else {
          $settings_wri = "0";
       }
       // A?dir a lista de amigos
       if (isset($_POST["settings_bud"]) && $_POST["settings_bud"] == 'on') {
          $settings_bud = "1";
       } else {
          $settings_bud = "0";
       }
       // Ataque con misiles
       if (isset($_POST["settings_mis"]) && $_POST["settings_mis"] == 'on') {
          $settings_mis = "1";
       } else {
          $settings_mis = "0";
       }
       // Ver reporte
       if (isset($_POST["settings_rep"]) && $_POST["settings_rep"] == 'on') {
          $settings_rep = "1";
       } else {
          $settings_rep = "0";
       }
       // Modo vacaciones
       if (isset($_POST["urlaubs_modus"]) && !empty($_POST["urlaubs_modus"]) && !$user->getVacation()) {
           //Selectionne si le joueur a des flottes en vol
           $fleet  = doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', true);
           //Selectionne si le joueur a des batiments en construction
           $build  = doquery("SELECT COUNT(id_owner) AS `building` FROM {{table}} WHERE `id_owner` = '".$user['id']."' and `b_building`!=0;", 'planets', true);
           //Selectionne si le joueur a des techno en cours
           $tech  = doquery("SELECT COUNT(id) AS `tech` FROM {{table}} WHERE `id` = '".$user['id']."' and `b_tech_planet`!=0;", 'users', true);
           //Selectionne si le joueur est en train de se faire attaquer
           $attack  = doquery("SELECT COUNT(fleet_target_owner) AS `attack` FROM {{table}} WHERE `fleet_target_owner` = '".$user['id']."';", 'fleets', true);

           if ($fleet['actcnt'] == 0 && $build['building'] == 0 && $tech['tech'] == 0 && $attack['attack'] == 0) {
               $user->setVacation();
           } else {
                 message(Wootook::__('Could not set to vacation mode.'),
                     Wootook::__("You currently can't get to vacation mode, please check your flying fleets, and building queues."));
           }
       }

       // Borrar cuenta
       if (isset($_POST["db_deaktjava"]) && $_POST["db_deaktjava"] == 'on') {
          $db_deaktjava = "1";
       } else {
          $db_deaktjava = "0";
       }
       $SetSort  = $_POST['settings_sort'];
       $SetOrder = $_POST['settings_order'];

       doquery("UPDATE {{table}} SET
       `email` = '$db_email',
       `avatar` = '$avatar',
       `dpath` = '$dpath',
       `design` = '$design',
       `noipcheck` = '$noipcheck',
       `planet_sort` = '$SetSort',
       `planet_sort_order` = '$SetOrder',
       `spio_anz` = '$spio_anz',
       `settings_tooltiptime` = '$settings_tooltiptime',
       `settings_fleetactions` = '$settings_fleetactions',
       `settings_allylogo` = '$settings_allylogo',
       `settings_esp` = '$settings_esp',
       `settings_wri` = '$settings_wri',
       `settings_bud` = '$settings_bud',
       `settings_mis` = '$settings_mis',
       `settings_rep` = '$settings_rep',
       `db_deaktjava` = '$db_deaktjava',
       `kolorminus` = '$kolorminus',
       `kolorplus` = '$kolorplus',
       `kolorpoziom` = '$kolorpoziom'
       WHERE `id` = '$iduser' LIMIT 1", "users");

       if (isset($_POST["db_password"]) && md5($_POST["db_password"]) == $user["password"]) {
          if (!empty($_POST['newpass1']) && !empty($_POST['newpass2']) && $_POST["newpass1"] == $_POST["newpass2"]) {
             $newpass = md5($_POST["newpass1"]);
             doquery("UPDATE {{table}} SET `password` = '{$newpass}' WHERE `id` = '{$user['id']}' LIMIT 1", "users");
             setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0); //le da el expire
             message($lang['succeful_changepass'], $lang['changue_pass'],"login.php",1);
          }
       }
       if ($user['username'] != $_POST["db_character"]) {
          $query = doquery("SELECT id FROM {{table}} WHERE username='{$_POST["db_character"]}'", 'users', true);
          if (!$query) {
             doquery("UPDATE {{table}} SET username='{$username}' WHERE id='{$user['id']}' LIMIT 1", "users");
             setcookie(COOKIE_NAME, "", time()-100000, "/", "", 0); //le da el expire
             message($lang['succeful_changename'], $lang['changue_name'],"login.php",1);
          }
       }
       message($lang['succeful_save'], $lang['Options'],"options.php",1);
    } else {
       $parse = $lang;

       $parse['dpath'] = $dpath;
       $parse['opt_lst_skin_data']  = "<option value =\"skins/xnova/\">skins/xnova/</option>";
       $parse['opt_lst_ord_data']   = "<option value =\"0\"". (($user['planet_sort'] == 0) ? " selected": "") .">". $lang['opt_lst_ord0'] ."</option>";
       $parse['opt_lst_ord_data']  .= "<option value =\"1\"". (($user['planet_sort'] == 1) ? " selected": "") .">". $lang['opt_lst_ord1'] ."</option>";
       $parse['opt_lst_ord_data']  .= "<option value =\"2\"". (($user['planet_sort'] == 2) ? " selected": "") .">". $lang['opt_lst_ord2'] ."</option>";

       $parse['opt_lst_cla_data']   = "<option value =\"0\"". (($user['planet_sort_order'] == 0) ? " selected": "") .">". $lang['opt_lst_cla0'] ."</option>";
       $parse['opt_lst_cla_data']  .= "<option value =\"1\"". (($user['planet_sort_order'] == 1) ? " selected": "") .">". $lang['opt_lst_cla1'] ."</option>";

       if ($user['authlevel'] != LEVEL_PLAYER) {
          $FrameTPL = gettemplate('options_admadd');
          $IsProtOn = doquery ("SELECT `id_level` FROM {{table}} WHERE `id_owner` = '".$user['id']."' LIMIT 1;", 'planets', true);
          $bloc['opt_adm_title']       = $lang['opt_adm_title'];
          $bloc['opt_adm_planet_prot'] = $lang['opt_adm_planet_prot'];
          $bloc['adm_pl_prot_data']    = ($IsProtOn['id_level'] > 0) ? " checked='checked'/":'';
          $parse['opt_adm_frame']      = parsetemplate($FrameTPL, $bloc);
       }

       $parse['opt_usern_data'] = $user['username'];
       $parse['opt_mail1_data'] = $user['email'];
       $parse['opt_mail2_data'] = $user['email_2'];
       $parse['opt_dpath_data'] = $user['dpath'];
       $parse['opt_avata_data'] = $user['avatar'];
       $parse['opt_probe_data'] = $user['spio_anz'];
       $parse['opt_toolt_data'] = $user['settings_tooltiptime'];
       $parse['opt_fleet_data'] = $user['settings_fleetactions'];
       $parse['opt_sskin_data'] = ($user['design'] == 1) ? " checked='checked'":'';
       $parse['opt_noipc_data'] = ($user['noipcheck'] == 1) ? " checked='checked'":'';
       $parse['opt_allyl_data'] = ($user['settings_allylogo'] == 1) ? " checked='checked'/":'';
       $parse['opt_delac_data'] = ($user['db_deaktjava'] == 1) ? " checked='checked'/":'';
       $parse['opt_modev_data'] = ($user['urlaubs_modus'] == 1)?" checked='checked'/":'';
       $parse['opt_modev_exit'] = ($user['urlaubs_modus'] == 0)?" checked='1'/":'';
       $parse['Vaccation_mode'] = $lang['Vaccation_mode'];
       $parse['vacation_until'] = date("d.m.Y G:i:s",$user['urlaubs_until']);
       $parse['user_settings_rep'] = ($user['settings_rep'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_esp'] = ($user['settings_esp'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_wri'] = ($user['settings_wri'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_mis'] = ($user['settings_mis'] == 1) ? " checked='checked'/":'';
       $parse['user_settings_bud'] = ($user['settings_bud'] == 1) ? " checked='checked'/":'';
       $parse['kolorminus']  = $user['kolorminus'];
       $parse['kolorplus']   = $user['kolorplus'];
       $parse['kolorpoziom'] = $user['kolorpoziom'];

       if($user['urlaubs_modus']){

          display(parsetemplate(gettemplate('options_body_vmode'), $parse), 'Options', false);
       }else{
       display(parsetemplate(gettemplate('options_body'), $parse), 'Options', false);
       }
       die();
    }

    ?>
