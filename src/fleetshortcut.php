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


$mode = isset($_GET['mode']) ? $_GET['mode'] : null;
$a = isset($_GET['a']) ? $_GET['a'] : null;

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();

if ($mode) {
    if ($_POST) {
        //Pegamos el texto :P
        if (!isset($_POST["n"]) || empty($_POST["n"])) {
            $name = Wootook::__("Unnamed");
        } else {
            $name = $_POST["n"];
        }

        if (($offset = strpos($name, "\n")) !== false) {
            $name = substr($name, 0, $offset);
        }
        if (($offset = strpos($name, "\r")) !== false) {
            $name = substr($name, 0, $offset);
        }
        if (($offset = strpos($name, ",")) !== false) {
            $name = substr($name, 0, $offset);
        }
        $planetTypes = array(
            Wootook_Empire_Model_Planet::TYPE_PLANET,
            Wootook_Empire_Model_Planet::TYPE_DEBRIS,
            Wootook_Empire_Model_Planet::TYPE_MOON
            );

        $r = array();
        $r[0] = preg_replace('#[^[:alnum:]\s\-\_\']#', '', $name);
        $r[1] = (isset($_POST['g']) && intval($_POST['g']) > 0 && intval($_POST['g']) <= Wootook::getGameConfig('engine/universe/galaxies')) ? intval($_POST['g']) : '1';
        $r[2] = (isset($_POST['s']) && intval($_POST['s']) > 0 && intval($_POST['s']) <= Wootook::getGameConfig('engine/universe/systems')) ? intval($_POST['s']) : '1';
        $r[3] = (isset($_POST['p']) && intval($_POST['p']) > 0 && intval($_POST['p']) <= Wootook::getGameConfig('engine/universe/positions')) ? intval($_POST['p']) : '1';
        $r[4] = (isset($_POST['t']) && intval($_POST['t']) > 0 && in_array(intval($_POST['t']), $planetTypes)) ? intval($_POST['t']) : '1';

        $user['fleet_shortcut'] .= implode(",", $r);
        $user->save();
        message(Wootook::__("The shortcut has been saved."), Wootook::__("Success"), "fleetshortcut.php");
    }
    $page = "<form method=POST><table border=0 cellpadding=0 cellspacing=1 width=519>
    <tr height=20>
    <td colspan=2 class=c>Nom [Galaxie/Syst&egrave;me solaire/Plan&egrave;te]</td>
    </tr><tr height=\"20\"><th>
    <input type=text name=n value=\"$g\" size=32 maxlength=32 title=\"" . Wootook::__('Name') . "\">
    <input type=text name=g value=\"$s\" size=3 maxlength=1 title=\"" . Wootook::__('Galaxy') . "\">
    <input type=text name=s value=\"$p\" size=3 maxlength=3 title=\"" . Wootook::__('System') . "\">
    <input type=text name=p value=\"$t\" size=3 maxlength=3 title=\"" . Wootook::__('Planet') . "\">
     <select name=t>";
    $page .= '<option value="1"'.(($c[4]==1)?" SELECTED":"").">" . Wootook::__('Planet') . "</option>";
    $page .= '<option value="2"'.(($c[4]==2)?" SELECTED":"").">" . Wootook::__('Debris') . "</option>";
    $page .= '<option value="3"'.(($c[4]==3)?" SELECTED":"").">" . Wootook::__('Moon') . "</option>";
    $page .= "</select>
    </th></tr><tr>
    <th><input type=\"reset\" value=\"" . Wootook::__('Reset') . "\"> <input type=\"submit\" value=\"" . Wootook::__('Save') . "\">";
    //Muestra un (L) si el destino pertenece a luna, lo mismo para escombros
    $page .= "</th></tr>";
    $page .= '<tr><td colspan=2 class=c><a href=fleetshortcut.php>Effacer</a></td></tr></tr></table></form>';
} else if ($a !== null) {
    if ($_POST) {
        //Armamos el array...
        $scarray = explode("\r\n", $user['fleet_shortcut']);
        if (isset($_POST["delete"])) {
            unset($scarray[$a]);
            $user['fleet_shortcut'] =  implode("\r\n", $scarray);
            doquery("UPDATE {{table}} SET fleet_shortcut={$db->quote($user['fleet_shortcut'])} WHERE id={$user['id']}", "users");
            message(Wootook::__("The shortcut has been deleted"), Wootook::__("Success"), "fleetshortcut.php");
        } else {
            $r = explode(",", $scarray[$a]);
            if (!isset($_POST["n"]) || empty($_POST["n"])) {
                $name = $r[0];
            } else {
                $name = $_POST["n"];
            }

            if (($offset = strpos($name, "\n")) !== false) {
                $name = substr($name, 0, $offset);
            }
            if (($offset = strpos($name, "\r")) !== false) {
                $name = substr($name, 0, $offset);
            }
            if (($offset = strpos($name, ",")) !== false) {
                $name = substr($name, 0, $offset);
            }
            $planetTypes = array(
                Wootook_Empire_Model_Planet::TYPE_PLANET,
                Wootook_Empire_Model_Planet::TYPE_DEBRIS,
                Wootook_Empire_Model_Planet::TYPE_MOON
                );
            $r[0] = preg_replace('#[^[:alnum:]\s\-\_\']#', '', $name);
            $r[1] = (isset($_POST['g']) && intval($_POST['g']) > 0 && intval($_POST['g']) <= Wootook::getGameConfig('engine/universe/galaxies')) ? intval($_POST['g']) : $r[1];
            $r[2] = (isset($_POST['s']) && intval($_POST['s']) > 0 && intval($_POST['s']) <= Wootook::getGameConfig('engine/universe/systems')) ? intval($_POST['s']) : $r[2];
            $r[3] = (isset($_POST['p']) && intval($_POST['p']) > 0 && intval($_POST['p']) <= Wootook::getGameConfig('engine/universe/positions')) ? intval($_POST['p']) : $r[3];
            $r[4] = (isset($_POST['t']) && intval($_POST['t']) > 0 && in_array(intval($_POST['t']), $planetTypes)) ? intval($_POST['t']) : $r[4];

            $scarray[$a] = implode(",", $r);
            $user['fleet_shortcut'] =  implode("\r\n", $scarray);
            $user->save();
            message(Wootook::__("The shortcut has been updated."), Wootook::__("Success"), "fleetshortcut.php");
        }
    }

    if ($user['fleet_shortcut']) {

        $scarray = explode("\r\n",$user['fleet_shortcut']);
        $c = explode(',',$scarray[$a]);

        $page = "<form method=POST><table border=0 cellpadding=0 cellspacing=1 width=519>
    <tr height=20>
    <td colspan=2 class=c>Editer: {$c[0]} [{$c[1]}:{$c[2]}:{$c[3]}]</td>
    </tr>";
        //if($i==0){$page .= "";}
        $page .= "<tr height=\"20\"><th>
        <input type=hidden name=a value=$a>
        <input type=text name=n value=\"{$c[0]}\" size=32 maxlength=32>
        <input type=text name=g value=\"{$c[1]}\" size=3 maxlength=1>
        <input type=text name=s value=\"{$c[2]}\" size=3 maxlength=3>
        <input type=text name=p value=\"{$c[3]}\" size=3 maxlength=3>
         <select name=t>";
        $page .= '<option value="1"'.(($c[4]==1)?" SELECTED":"").">Plan&egrave;te</option>";
        $page .= '<option value="2"'.(($c[4]==2)?" SELECTED":"").">D&eacute;bris</option>";
        $page .= '<option value="3"'.(($c[4]==3)?" SELECTED":"").">Lune</option>";
        $page .= "</select>
        </th></tr><tr>
        <th><input type=reset value=\"Reset\"> <input type=submit value=\"Enregistrer\"> <input type=submit name=delete value=\"Supprimer\">";
        $page .= "</th></tr>";

    } else {
        $page .= message("Le raccourcis a &eacute;t&eacute; enregistr&eacute; !","Enregistrer","fleetshortcut.php");
    }

    $page .= '<tr><td colspan=2 class=c><a href=fleetshortcut.php>Retour</a></td></tr></tr></table></form>';


} else {

    $page = '<table border="0" cellpadding="0" cellspacing="1" width="519">
    <tr height="20">
    <td colspan="2" class="c">Raccourcis(<a href="?mode=add">Ajout</a>)</td>
    </tr>';

    if($user['fleet_shortcut']){
        /*
          Dentro de fleet_shortcut, se pueden almacenar las diferentes direcciones
          de acceso directo, el formato es el siguiente.
          Nombre, Galaxia,Sistema,Planeta,Tipo
        */
        $scarray = explode("\r\n",$user['fleet_shortcut']);
        $i=$e=0;
        foreach($scarray as $a => $b){
            if($b!=""){
            $c = explode(',',$b);
            if($i==0){$page .= "<tr height=\"20\">";}
            $page .= "<th><a href=\"?a=".$e++."\">";
            $page .= "{$c[0]} {$c[1]}:{$c[2]}:{$c[3]}";
            //Muestra un (L) si el destino pertenece a luna, lo mismo para escombros
            if($c[4]==2){$page .= " (E)";}elseif($c[4]==3){$page .= " (L)";}
            $page .= "</a></th>";
            if($i==1){$page .= "</tr>";}
            if($i==1){$i=0;}else{$i=1;}
            }

        }
        if($i==1){$page .= "<th></th></tr>";}

    }else{$page .= "<th colspan=\"2\">Pas de Raccourcis</th>";}

    $page .= '<tr><td colspan=2 class=c><a href=fleet.php>Retour</a></td></tr></tr></table>';
}
display($page,"Shortcutmanager");

// Created by Perberos. All rights reversed (C) 2006
?>
