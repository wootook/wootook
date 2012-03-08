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

define('INSTALL' , false);
define('INSIDE' , true);
require_once dirname(__FILE__) .'/application/bootstrap.php';

$user = Wootook_Player_Model_Session::getSingleton()->getPlayer();
$db = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection(Wootook_Core_Database_ConnectionManager::DEFAULT_CONNECTION_NAME);

if(!$user->getId()){
echo '<script language="javascript">';
echo 'parent.location="../";';
echo '</script>';
}

$request = Wootook::getRequest();

$mode       = $request->getQuery('mode');
$yes        = $request->getQuery('yes');
$edit       = $request->getQuery('edit');
$allyid     = intval($request->getQuery('allyid'));
$kick       = $request->getQuery('kick');
$show       = intval($request->getQuery('show'));
$sort       = intval($request->getQuery('sort'));
$sendmail   = intval($request->getQuery('sendmail'));
$rank       = intval($request->getQuery('rank'));
$t          = $request->getQuery('t');
$allianceId = intval($request->getQuery('a'));
$tag        = $request->getQuery('tag');
$page       = $request->getQuery('page');
$d          = $request->getQuery('d');
$id         = $request->getQuery('id');

$allianceIds = $request->getPost('id');
$actionType = $request->getPost('t');
$options = $request->getPost('options');
$action = $request->getPost('action');

includeLang('alliance');

/**
 *Alliance information display.
 */

if ($mode == 'ainfo') {
    $lang['Alliance_information'] = "Information Alliance";

    if ($tag) {
        $allyrow = doquery("SELECT * FROM {{table}} WHERE ally_tag={$db->quote($tag)}", "alliance", true);
    } elseif (is_numeric($allianceId) && $allianceId != 0) {
        $allyrow = doquery("SELECT * FROM {{table}} WHERE id='{$allianceId}'", "alliance", true);
    } else {
      message("Cette alliance n'existe pas !", "Information Alliance (1)");
    }
    // if not exists
    if (!$allyrow) {
      message("Cette alliance n'existe pas !", "Information Alliance (1)");
    }
    extract($allyrow);

    if ($ally_image != "") {
        $ally_image = "<tr><th colspan=2><img src=\"{$ally_image}\"></td></tr>";
    }

    if ($ally_description != "") {
        $ally_description = "<tr><th colspan=2 height=100>{$ally_description}</th></tr>";
    } else
        $ally_description = "<tr><th colspan=2 height=100>Il n'y as aucune descriptions de cette alliance.</th></tr>";

    if ($ally_web != "") {
        $ally_web = "<tr>
        <th>{$lang['Initial_page']}</th>
        <th><a href=\"{$ally_web}\">{$ally_web}</a></th>
        </tr>";
    }

    $lang['ally_member_scount'] = $ally_members;
    $lang['ally_name'] = $ally_name;
    $lang['ally_tag'] = $ally_tag;
    $patterns[] = "#\[fc\]([a-z0-9\#]+)\[/fc\](.*?)\[/f\]#Ssi";
    $replacements[] = '<font color="\1">\2</font>';
    $patterns[] = '#\[img\](.*?)\[/img\]#Smi';
    $replacements[] = '<img src="\1" alt="\1" style="border:0px;" />';
    $patterns[] = "#\[fc\]([a-z0-9\#\ \[\]]+)\[/fc\]#Ssi";
    $replacements[] = '<font color="\1">';
    $patterns[] = "#\[/f\]#Ssi";
    $replacements[] = '</font>';
    $ally_description = preg_replace($patterns, $replacements, $ally_description);

    $lang['ally_description'] = nl2br($ally_description);
    $lang['ally_image'] = $ally_image;
    $lang['ally_web'] = $ally_web;

    if ($user->getData('ally_id') == 0) {
        $lang['bewerbung'] = "<tr>
      <th>Candidature</th>
      <th><a href=\"alliance.php?mode=apply&amp;allyid=" . $id . "\">Cliquer ici pour ecrire votre candidature</a></th>

    </tr>";
    } else
        $lang['bewerbung'] = "Candidature";

    $page .= parsetemplate(gettemplate('alliance_ainfo'), $lang);
    display($page, str_replace('%s', $ally_name, $lang['Info_of_Alliance']));
}
/**
 * Verification of the alliance
 */
if ($user->getData('ally_id') == 0) {
    if ($mode == 'make' && $user->getData('ally_request') == 0) { // Make alliance
    /*
     * Creation of the alliance
     */
        if ($yes == 1 && $request->isPost()) {

            if ($request->getPost('atag') !== null) {
                message($lang['have_not_tag'], $lang['make_alliance']);
            }
            if ($request->getPost('aname') !== null) {
                message($lang['have_not_name'], $lang['make_alliance']);
            }

            $alliance_name = $db->quote($request->getPost('aname'));
            $alliance_tag  = $db->quote($request->getPost('atag'));

            $tagquery = doquery("SELECT * FROM {{table}} WHERE ally_tag=" .$alliance_tag. ";", 'alliance', true);

            if ($tagquery) {
                message(str_replace('%s', $alliance_tag, $lang['always_exist']), $lang['make_alliance']);
            }
            $ranks = '';
            $allianz_raenge[] = array('name' => 'Novice',
                'mails' => 0,
                'delete' => 0,
                'kick' => 0,
                'bewerbungen' => 0,
                'administrieren' => 0,
                'bewerbungenbearbeiten' => 0,
                'memberlist' => 0,
                'onlinestatus' => 0,
                'rechtehand' => 0
                );

            $ranks = serialize($allianz_raenge);

            doquery("INSERT INTO {{table}} SET
            `ally_name`=" . $alliance_name . ",
            `ally_tag`=" . $alliance_tag . " ,
            `ally_owner`='" .$user->getId(). "',
            `ally_owner_range`='Leader',
            `ally_ranks`='" . $ranks . "',
            `ally_members`='1',
            `ally_register_time`=" . time() , "alliance");

            $allyquery = doquery("SELECT * FROM {{table}} WHERE ally_tag=" .$alliance_tag. ";", 'alliance', true);

            doquery("UPDATE {{table}} SET
            `ally_id`='" .$allyquery['id']. "',
            `ally_name`='" .$allyquery['ally_name']. "',
            `ally_register_time`='" . time() . "'
            WHERE `id`='{$user->getId()}'", "users");

            $page = MessageForm(str_replace('%s', $alliance_tag, $lang['ally_maked']), str_replace('%s', $alliance_tag, $lang['alliance_has_been_maked']) . "<br><br>", "", $lang['Ok']);
        } else {
            $page .= parsetemplate(gettemplate('alliance_make'), $lang);
        }

        display($page, $lang['make_alliance']);
    }
    /**
     * User is searching for an alliance
     */
    if ($mode == 'search' && $user->getData('ally_request') == 0) {

        $parse = $lang;
        $search_text = $request->getPost('searchtext');
        $lang['searchtext'] = $search_text;
        $page = parsetemplate(gettemplate('alliance_searchform'), $lang);

        if ($request->getPost('searchtext') !== null) {
            $stmt = $db->prepare("SELECT * FROM {$db->getTable('alliance')} WHERE ally_name LIKE :search_field OR ally_tag LIKE :search_field LIMIT 30");
            $stmt->execute(array(':search_field' => '%' .$search_text. '%'));
            if ($stmt->rowCount() != 0) {
                $template = gettemplate('alliance_searchresult_row');
                $parse['result'] = null;
                while ($s = $stmt->fetch(PDO::FETCH_BOTH)) {
                    $entry = array();
                    $entry['ally_tag'] = "[<a href=\"alliance.php?mode=apply&allyid={$s['id']}\">{$s['ally_tag']}</a>]";
                    $entry['ally_name'] = $s['ally_name'];
                    $entry['ally_members'] = $s['ally_members'];

                    $parse['result'] .= parsetemplate($template, $entry);
                }

                $page .= parsetemplate(gettemplate('alliance_searchresult_table'), $parse);
            }
        }

        display($page, $lang['search_alliance']);
    }
    /**
     * request handler.
     */
    if ($mode == 'apply' && $user->getData('ally_request') == 0) {

        if (!is_numeric($allyid) || empty($allyid) || $user->getData('ally_request') != 0 || $user->getData('ally_id') != 0) {
            message($lang['it_is_not_posible_to_apply'], $lang['it_is_not_posible_to_apply']);
        }
        /**
         * ask for the info of the alliance
         */
        $allyrow = doquery("SELECT ally_tag,ally_request FROM {{table}} WHERE id='" .$allyid. "'", "alliance", true);

        if (!$allyrow) {
            message($lang['it_is_not_posible_to_apply'], $lang['it_is_not_posible_to_apply']);
        }

        extract($allyrow);

        if ($request->getPost('further') !== null && $request->getPost('further') == $lang['Send']) {
            doquery("UPDATE {{table}} SET `ally_request`='" . $allyid . "', ally_request_text=" . $db->quote($request->getPost('text')) . ", ally_register_time='" . time() . "' WHERE `id`='" . $user->getId(). "'", "users");
            message($lang['apply_registered'], $lang['your_apply']);
        } else {
            $text_apply = ($ally_request) ? $ally_request : $lang['There_is_no_a_text_apply'];
        }

        $parse = $lang;
        $parse['allyid'] = $allyid;
        $parse['chars_count'] = strlen($text_apply);
        $parse['text_apply'] = $text_apply;
        $parse['Write_to_alliance'] = str_replace('%s', $ally_tag, $lang['Write_to_alliance']);

        $page = parsetemplate(gettemplate('alliance_applyform'), $parse);

        display($page, $lang['Write_to_alliance']);
    }
    /**
     * accept request
     */
    if ($user['ally_request'] != 0) {

        $allyquery = doquery("SELECT ally_tag FROM {{table}} WHERE id='" . $user->getData('ally_request') . "' ORDER BY `id`", "alliance", true);

        extract($allyquery);
        if ($request->getPost('bcancel') !== null) {
            doquery("UPDATE {{table}} SET `ally_request`=0 WHERE `id`=" . $user->getId('id'), "users");

            $lang['request_text'] = str_replace('%s', $ally_tag, $lang['Canceled_a_request_text']);
            $lang['button_text'] = $lang['Ok'];
            $page = parsetemplate(gettemplate('alliance_apply_waitform'), $lang);
        } else {
            $lang['request_text'] = str_replace('%s', $ally_tag, $lang['Waiting_a_request_text']);
            $lang['button_text'] = $lang['Delete_apply'];
            $page = parsetemplate(gettemplate('alliance_apply_waitform'), $lang);
        }
        display($page, "You don't have any requests");
    } else {
        $page .= parsetemplate(gettemplate('alliance_defaultmenu'), $lang);
        display($page, $lang['alliance']);
    }
}

/**
 *Inside the alliance
 */

elseif ($user->getData('ally_id') != 0 && $user->getData('ally_request') == 0) { // player with alliance only

    $ally = doquery("SELECT * FROM {{table}} WHERE id='" .$user->getData('ally_id'). "'", "alliance", true);

    $ally_ranks = unserialize($ally['ally_ranks']);

    $allianz_raenge = unserialize($ally['ally_ranks']);

    if (!empty($ally_ranks)){
        if ($allianz_raenge[$user->GetData('ally_rank_id')]['onlinestatus'] == 1 || $ally['ally_owner'] == $user->getId()) {
            $user_can_watch_memberlist_status = true;
        } else
            $user_can_watch_memberlist_status = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['memberlist'] == 1 || $ally['ally_owner'] == $user->getId()) {
            $user_can_watch_memberlist = true;
        } else
            $user_can_watch_memberlist = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['mails'] == 1 || $ally['ally_owner'] == $user->getId()) {
            $user_can_send_mails = true;
        } else
            $user_can_send_mails = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['kick'] == 1 || $ally['ally_owner'] == $user->getId()) {
            $user_can_kick = true;
        } else
            $user_can_kick = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['rechtehand'] == 1 || $ally['ally_owner'] == $user->getId()){
            $user_can_edit_rights = true;
        } else
            $user_can_edit_rights = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['delete'] == 1 || $ally['ally_owner'] == $user->getId())
            $user_can_exit_alliance = true;
        else
            $user_can_exit_alliance = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['bewerbungen'] == 1 || $ally['ally_owner'] == $user->getId())
            $user_bewerbungen_einsehen = true;
        else
            $user_bewerbungen_einsehen = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['bewerbungenbearbeiten'] == 1 || $ally['ally_owner'] == $user->getId())
            $user_bewerbungen_bearbeiten = true;
        else
            $user_bewerbungen_bearbeiten = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['administrieren'] == 1 || $ally['ally_owner'] == $user->getId())
            $user_admin = true;
        else
            $user_admin = false;

        if ($allianz_raenge[$user->GetData('ally_rank_id')]['onlinestatus'] == 1 || $ally['ally_owner'] == $user->getId())
           $user_onlinestatus = true;
        else
           $user_onlinestatus = false;
    } elseif ($ally['ally_owner'] == $user->getId()) {
        $user_can_watch_memberlist_status = true;
        $user_can_watch_memberlist = true;
        $user_can_send_mails = true;
        $user_can_kick = true;
        $user_can_edit_rights = true;
        $user_can_exit_alliance = true;
        $user_bewerbungen_einsehen = true;
        $user_bewerbungen_bearbeiten = true;
        $user_admin = true;
    }
    if (!$ally) {
        doquery("UPDATE {{table}} SET `ally_name`='',`ally_id`=0 WHERE `id`='" .$user->getId(). "'", "users");
        message($lang['ally_notexist'], $lang['your_alliance'], 'alliance.php');
    }

    if ($mode == 'exit') {
        if ($ally['ally_owner'] == $user->getId()) {
            message($lang['Owner_cant_go_out'], $lang['Alliance']);
        }
        // want to go out the alliance
        if ($yes == 1) {
            doquery("UPDATE {{table}} SET `ally_id`=0, `ally_name` = '' WHERE `id`='" .$user->getId(). "'", "users");
            $lang['Go_out_welldone'] = str_replace("%s", $ally_name, $lang['Go_out_welldone']);
            $page = MessageForm($lang['Go_out_welldone'], "<br>", $PHP_SELF, $lang['Ok']);
        } else {
            // ask if you want to leave the alliance
            $lang['Want_go_out'] = str_replace("%s", $ally_name, $lang['Want_go_out']);
            $page = MessageForm($lang['Want_go_out'], "<br>", "?mode=exit&yes=1", "Oui");
        }
        display($page);
    }

    if ($mode == 'memberslist') { // List of all members.
    /**
      *List of members.
      *Apparently only one query is used for searching the users with the same ally_id.
      *followed by the query of the primary planet of each to get the position.
      */
        $allianz_raenge = unserialize($ally['ally_ranks']);
        // permission check if user can watch memberlist
        if ($ally['ally_owner'] != $user->getId() && !$user_can_watch_memberlist) {
            message($lang['Denied_access'], $lang['Members_list']);
        }
        // Order of displaying the different users
        $sort2 = intval($request->getQuery('sort2'));
        if ($sort2) {
            $sort1 = intval($request->getQuery('sort1'));
            switch($sort1){
                case 1:
                    $sort = " ORDER BY `username`";
                    break;
                case 2:
                    $sort = " ORDER BY `username`";
                    break;
                case 4:
                    $sort = " ORDER BY `ally_register_time`";
                    break;
                case 5:
                    $sort = " ORDER BY `onlinetime`";
                    break;
                default:
                    $sort = " ORDER BY `id`";
                    break;
            }
            switch($sort2){
                case 1:
                    $sort .= " DESC;";
                    break;
                case 2:
                    $sort .= " ASC;";
                    break;
                default:
                    $sort .= " DESC;";
                    break;
            }

            $listuser = doquery("SELECT * FROM {{table}} WHERE ally_id='{$user['ally_id']}'{$sort}", 'users');
        } else {
            $listuser = doquery("SELECT * FROM {{table}} WHERE ally_id='{$user['ally_id']}'", 'users');
        }
        /**
         *count the points of users
         */
        $i = 0;
        $template = gettemplate('alliance_memberslist_row');
        $page_list = '';
        while ($u = $listuser->fetch(PDO::FETCH_BOTH)) {
            $UserPoints = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $u['id'] . "';", 'statpoints', true);

            $i++;
            $u['i'] = $i;

            if ($u["onlinetime"] + 60 * 10 >= time() && $user_can_watch_memberlist_status) {
                $u["onlinetime"] = "lime>{$lang['On']}<";
            } elseif ($u["onlinetime"] + 60 * 20 >= time() && $user_can_watch_memberlist_status) {
                $u["onlinetime"] = "yellow>{$lang['15_min']}<";
            } elseif ($user_can_watch_memberlist_status) {
                $u["onlinetime"] = "red>{$lang['Off']}<";
            } else $u["onlinetime"] = "orange>-<";
            // Number of the rang
            if ($ally['ally_owner'] == $u['id']) {
                $u["ally_range"] = ($ally['ally_owner_range'] == '')?"Leader":$ally['ally_owner_range'];
            } elseif (isset($allianz_raenge[$u['ally_rank_id']]['name'])) {
                $u["ally_range"] = $allianz_raenge[$u['ally_rank_id']]['name'];
            } else {
                $u["ally_range"] = $lang['Novate'];
            }

            $u['points'] = "" . pretty_number($UserPoints['total_points']) . "";

            if ($u['ally_register_time'] > 0)
                $u['ally_register_time'] = date("Y-m-d h:i:s", $u['ally_register_time']);
            else
                $u['ally_register_time'] = "-";

            $page_list .= parsetemplate($template, $u);
        }
        // Change the link for order by
        switch($sort2){
            case 1:
                $s = 2;
                break;
            case 2:
                $s = 1;
                break;
            default:
                $s = 1;
                break;
        }

        if ($i != $ally['ally_members']) {
            doquery("UPDATE {{table}} SET `ally_members`='{$i}' WHERE `id`='{$ally['id']}'", 'alliance');
        }

        $parse = $lang;
        $parse['i'] = $i;
        $parse['s'] = $s;
        $parse['list'] = $page_list;

        $page .= parsetemplate(gettemplate('alliance_memberslist_table'), $parse);

        display($page, $lang['Members_list']);
    }

    if ($mode == 'circular') {
    /**
     *Create a circular message
     *I think here they would have to see how to create the messaging system
     */
        $allianz_raenge = unserialize($ally['ally_ranks']);
        // check permissions
        if ($ally['ally_owner'] != $user->getId() && !$user_can_send_mails) {
            message($lang['Denied_access'], $lang['Send_circular_mail']);
        }

        if ($sendmail == 1) {
            if ($request->getPost('r') !== null && is_numeric($request->getPost('r'))){
                $group_rank = intval($request->getPost('r'));
                $message_text = $db->quote($request->getPost('text'));
                if ($group_rang == 0) {
                    $sq = doquery("SELECT id,username FROM {{table}} WHERE ally_id='{$user['ally_id']}'", "users");
                } else {
                    $sq = doquery("SELECT id,username FROM {{table}} WHERE ally_id='{$user['ally_id']}' AND ally_rank_id='{$group_rank}'", "users");
                }
                // for each user founded, we send a message. Perhaps there is a better way to do this.
                $list = '';
                while ($u = $sq->fetch(PDO::FETCH_BOTH)) {
                    doquery("INSERT INTO {{table}} SET
                    `message_owner`='{$u['id']}',
                    `message_sender`='{$user->getId()}' ,
                    `message_time`='" . time() . "',
                    `message_type`='2',
                    `message_from`='{$ally['ally_tag']}',
                    `message_subject`='{$user['username']}',
                    `message_text`={$message_text} ", "messages");
                    $list .= "<br>{$u['username']} ";
                }
                doquery("UPDATE {{table}} SET `new_message`=new_message+1 WHERE ally_id='{$user['ally_id']}' AND ally_rank_id='{$group_rank}'", "users");
                doquery("UPDATE {{table}} SET `mnl_alliance`=mnl_alliance+1 WHERE ally_id='{$user['ally_id']}' AND ally_rank_id='{$group_rank}'", "users");
                /*
                 *Messages were sended successfully
                 */
                $page = MessageForm($lang['Circular_sended'], "The followed members have recieved the message:" . $list, "alliance.php", $lang['Ok'], true);
                display($page, $lang['Send_circular_mail']);
            } else {
                message($lang['Denied_access'], $lang['Members_list']);
            }
        }

        $lang['r_list'] = "<option value=\"0\">{$lang['All_players']}</option>";
        if ($allianz_raenge) {
            foreach($allianz_raenge as $id => $array) {
                $lang['r_list'] .= "<option value=\"" . ($id + 1) . "\">" . $array['name'] . "</option>";
            }
        }

        $page .= parsetemplate(gettemplate('alliance_circular'), $lang);

        display($page, $lang['Send_circular_mail']);
    }
    /**
     *Manage the rights
     */
    if ($mode == 'admin' && $edit == 'rights') {
        $allianz_raenge = unserialize($ally['ally_ranks']);

        if ($ally['ally_owner'] != $user->getId() && !$user_can_edit_rights) {
            message($lang['Denied_access'], $lang['Members_list']);
        } else if ($request->getPost('newrangname') != '') {
            $name = strip_tags($request->getPost('newrangname'));

            $allianz_raenge[] = array('name' => $name,
                'mails' => 0,
                'delete' => 0,
                'kick' => 0,
                'bewerbungen' => 0,
                'administrieren' => 0,
                'bewerbungenbearbeiten' => 0,
                'memberlist' => 0,
                'onlinestatus' => 0,
                'rechtehand' => 0
                );

            $ranks = serialize($allianz_raenge);

            doquery("UPDATE {{table}} SET `ally_ranks`=" . $db->quote($ranks) . " WHERE `id`=" . $ally['id'], "alliance");

            $goto = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

            header("Location: " . $goto);
            exit();
        } elseif (is_array($allianceIds) && !empty($allianceIds)) {
            $ally_ranks_new = array();

            foreach ($allianceIds as $id) {
                $name = $allianz_raenge[$id]['name'];

                $ally_ranks_new[$id]['name'] = $name;

                if ($request->getPost('u' . $id . 'r0') !== null) {
                    $ally_ranks_new[$id]['delete'] = 1;
                } else {
                    $ally_ranks_new[$id]['delete'] = 0;
                }

                if ($request->getPost('u' . $id . 'r1') !== null && $ally['ally_owner'] == $user->getId()) {
                    $ally_ranks_new[$id]['kick'] = 1;
                } else {
                    $ally_ranks_new[$id]['kick'] = 0;
                }

                if ($request->getPost('u' . $id . 'r2') !== null) {
                    $ally_ranks_new[$id]['bewerbungen'] = 1;
                } else {
                    $ally_ranks_new[$id]['bewerbungen'] = 0;
                }

                if ($request->getPost('u' . $id . 'r3') !== null) {
                    $ally_ranks_new[$id]['memberlist'] = 1;
                } else {
                    $ally_ranks_new[$id]['memberlist'] = 0;
                }

                if ($request->getPost('u' . $id . 'r4') !== null) {
                    $ally_ranks_new[$id]['bewerbungenbearbeiten'] = 1;
                } else {
                    $ally_ranks_new[$id]['bewerbungenbearbeiten'] = 0;
                }

                if ($request->getPost('u' . $id . 'r5') !== null) {
                    $ally_ranks_new[$id]['administrieren'] = 1;
                } else {
                    $ally_ranks_new[$id]['administrieren'] = 0;
                }

                if ($request->getPost('u' . $id . 'r6') !== null) {
                    $ally_ranks_new[$id]['onlinestatus'] = 1;
                } else {
                    $ally_ranks_new[$id]['onlinestatus'] = 0;
                }

                if ($request->getPost('u' . $id . 'r7') !== null) {
                    $ally_ranks_new[$id]['mails'] = 1;
                } else {
                    $ally_ranks_new[$id]['mails'] = 0;
                }

                if ($request->getPost('u' . $id . 'r8') !== null) {
                    $ally_ranks_new[$id]['rechtehand'] = 1;
                } else {
                    $ally_ranks_new[$id]['rechtehand'] = 0;
                }
            }

            $ranks = serialize($ally_ranks_new);

            doquery("UPDATE {{table}} SET `ally_ranks`=" . $db->quote($ranks) . " WHERE `id`=" . $ally['id'], "alliance");

            $goto = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

            header("Location: " . $goto);
            exit();
        }
        // Delete a rank
        elseif (isset($d) && isset($ally_ranks[$d])) {
            unset($ally_ranks[$d]);
            $ally['ally_rank'] = serialize($ally_ranks);

            doquery("UPDATE {{table}} SET `ally_ranks`='{$ally['ally_rank']}' WHERE `id`={$ally['id']}", "alliance");
        }

        if (count($ally_ranks) == 0 || $ally_ranks == '') { // If there is no rank
            $list = "<th>{$lang['There_is_not_range']}</th>";
        } else { // If there is a rank
            $list = parsetemplate(gettemplate('alliance_admin_laws_head'), $lang);
            $template = gettemplate('alliance_admin_laws_row');
            // Create the list of ranks

            foreach($ally_ranks as $a => $b) {
                if ($ally['ally_owner'] == $user->getId()) {
                    $lang['id'] = $a;
                    $lang['r0'] = $b['name'];
                    $lang['a'] = $a;
                    $lang['r1'] = "<input type=checkbox name=\"u{$a}r0\"" . (($b['delete'] == 1)?' checked="checked"':'') . ">"; //{$b[1]}
                    $lang['r2'] = "<input type=checkbox name=\"u{$a}r1\"" . (($b['kick'] == 1)?' checked="checked"':'') . ">";
                    $lang['r3'] = "<input type=checkbox name=\"u{$a}r2\"" . (($b['bewerbungen'] == 1)?' checked="checked"':'') . ">";
                    $lang['r4'] = "<input type=checkbox name=\"u{$a}r3\"" . (($b['memberlist'] == 1)?' checked="checked"':'') . ">";
                    $lang['r5'] = "<input type=checkbox name=\"u{$a}r4\"" . (($b['bewerbungenbearbeiten'] == 1)?' checked="checked"':'') . ">";
                    $lang['r6'] = "<input type=checkbox name=\"u{$a}r5\"" . (($b['administrieren'] == 1)?' checked="checked"':'') . ">";
                    $lang['r7'] = "<input type=checkbox name=\"u{$a}r6\"" . (($b['onlinestatus'] == 1)?' checked="checked"':'') . ">";
                    $lang['r8'] = "<input type=checkbox name=\"u{$a}r7\"" . (($b['mails'] == 1)?' checked="checked"':'') . ">";
                    $lang['r9'] = "<input type=checkbox name=\"u{$a}r8\"" . (($b['rechtehand'] == 1)?' checked="checked"':'') . ">";

                    $list .= parsetemplate($template, $lang);
                } else {
                    $lang['id'] = $a;
                    $lang['r0'] = $b['name'];
                    $lang['a'] = $a;
                    $lang['r1'] = "<b>-</b>";
                    $lang['r2'] = "<input type=checkbox name=\"u{$a}r1\"" . (($b['kick'] == 1)?' checked="checked"':'') . ">";
                    $lang['r3'] = "<input type=checkbox name=\"u{$a}r2\"" . (($b['bewerbungen'] == 1)?' checked="checked"':'') . ">";
                    $lang['r4'] = "<input type=checkbox name=\"u{$a}r3\"" . (($b['memberlist'] == 1)?' checked="checked"':'') . ">";
                    $lang['r5'] = "<input type=checkbox name=\"u{$a}r4\"" . (($b['bewerbungenbearbeiten'] == 1)?' checked="checked"':'') . ">";
                    $lang['r6'] = "<input type=checkbox name=\"u{$a}r5\"" . (($b['administrieren'] == 1)?' checked="checked"':'') . ">";
                    $lang['r7'] = "<input type=checkbox name=\"u{$a}r6\"" . (($b['onlinestatus'] == 1)?' checked="checked"':'') . ">";
                    $lang['r8'] = "<input type=checkbox name=\"u{$a}r7\"" . (($b['mails'] == 1)?' checked="checked"':'') . ">";
                    $lang['r9'] = "<input type=checkbox name=\"u{$a}r8\"" . (($b['rechtehand'] == 1)?' checked="checked"':'') . ">";

                    $list .= parsetemplate($template, $lang);
                }
            }

            if (count($ally_ranks) != 0) {
                $list .= parsetemplate(gettemplate('alliance_admin_laws_feet'), $lang);
            }
        }

        $lang['list'] = $list;
        $page .= parsetemplate(gettemplate('alliance_admin_laws'), $lang);

        display($page, $lang['Law_settings']);
    }
    /**
     * Manage alliance
     */
    if ($mode == 'admin' && $edit == 'ally') {
        if ($t != 1 && $t != 2 && $t != 3) {
            $t = 1;
        }

        if ($options) {
            $ally['ally_owner_range'] = htmlspecialchars(strip_tags($request->getPost('owner_range')));

            $ally['ally_web'] = htmlspecialchars(strip_tags($request->getPost('web')));

            $ally['ally_image'] = htmlspecialchars(strip_tags($request->getPost('image')));

            $ally['ally_request_notallow'] = intval($request->getPost('request_notallow'));

            if ($ally['ally_request_notallow'] != 0 && $ally['ally_request_notallow'] != 1) {
                message("Aller ï¿½ \"Candidature\" et sur une option dans le formulaire!", "Erreur");
                exit;
            }

            doquery("UPDATE {{table}} SET
            `ally_owner_range`={$db->quote($ally['ally_owner_range'])},
            `ally_image`={$db->quote($ally['ally_image'])},
            `ally_web`={$db->quote($ally['ally_web'])},
            `ally_request_notallow`='{$ally['ally_request_notallow']}'
            WHERE `id`='{$ally['id']}'", "alliance");
        } elseif ($actionType) {
            if ($t == 3) {
                $ally['ally_request'] = strip_tags($request->getPost('text'));

                doquery("UPDATE {{table}} SET
                `ally_request`={$db->quote($ally['ally_request'])}
                WHERE `id`='{$ally['id']}'", "alliance");
            } elseif ($t == 2) {
                $ally['ally_text'] = strip_tags($request->getPost('text'));
                doquery("UPDATE {{table}} SET
                `ally_text`={$db->quote($ally['ally_text'])}
                WHERE `id`='{$ally['id']}'", "alliance");
            } else {
                $ally['ally_description'] = strip_tags($request->getPost('text'));

                doquery("UPDATE {{table}} SET
                `ally_description`=" . $db->quote($ally['ally_description']) . "
                WHERE `id`='{$ally['id']}'", "alliance");
            }
        }
        /**
         * Show form for each type of $t
         * This one works fine. In the preview version, the request was not handled.
           */
        if ($t == 3) {
            $lang['Show_of_request_text'] = 'Request text';
            $lang['request_type'] = $lang['Show_of_request_text'];
        } elseif ($t == 2) {
            $lang['Internal_text_of_alliance'] = 'Internal text';
            $lang['request_type'] = $lang['Internal_text_of_alliance'];
        } else {
            $lang['Public_text_of_alliance'] = 'Public text';
            $lang['request_type'] = $lang['Public_text_of_alliance'];
        }
        if ($t == 2) {
            $lang['text'] = $ally['ally_text'];
            $lang['Texts'] = $lang['Texts'];
            $lang['Show_of_request_text'] = "Texte Interne d'ALliance";
        } elseif ($t == 1) {
            $lang['text'] = stripslashes($ally['ally_description']);
            $lang['Show_of_request_text'] = "Texte Externe d'ALliance";
        }    else {
            $lang['text'] = $ally['ally_request'];
            $lang['Show_of_request_text'] = "Texte de candidature";
        }

        $lang['t'] = $t;
        $lang['ally_web'] = $ally['ally_web'];
        $lang['ally_image'] = $ally['ally_image'];
        $lang['ally_request_notallow_0'] = (($ally['ally_request_notallow'] == 1) ? ' SELECTED' : '');
        $lang['ally_request_notallow_1'] = (($ally['ally_request_notallow'] == 0) ? ' SELECTED' : '');
        $lang['ally_owner_range'] = $ally['ally_owner_range'];
        $lang['Transfer_alliance'] = MessageForm("Abandonner / Transf&eacute;rer L'alliance", "", "?mode=admin&edit=give", $lang['Continue']);
        $lang['Disolve_alliance'] = MessageForm("Dissoudre L'alliance", "", "?mode=admin&edit=exit", $lang['Continue']);

        $page .= parsetemplate(gettemplate('alliance_admin'), $lang);
        display($page, $lang['Alliance_admin']);
    }

    if ($mode == 'admin' && $edit == 'give') {
        if ($request->getPost('id')) {
            doquery("update {{table}} set `ally_owner`=".$db->quote($request->getPost('id'))." where `id`='".$user['ally_id']."'",'alliance');
         } else {
            $selection=doquery("SELECT * FROM {{table}} where ally_id='".$user['ally_id']."'",'users');
            $select='';
            while ($data = $selection->fetch(PDO::FETCH_BOTH)){
                $select.='<OPTION VALUE="'.$data['id'].'">'.$data['username'];
            }
            $page .= '<br><form method="post" action="alliance.php?mode=admin&edit=give"><table width="600" border="0" cellpadding="0" cellspacing="1" ALIGN="center">';
            $page .= '<tr><td class="c" colspan="4" align="center">A qui voulez vous donner l alliance ?</td></tr>';
            $page .= '<tr>';
            $page .= "<th colspan=\"3\">Choisissez le joueur a qui vous souhaitez donner l alliance :</th><th colspan=\"1\"><SELECT NAME=\"id\">$select</SELECT></th>";
            $page .= '</tr>';
            $page .= '<tr><th colspan="4"><INPUT TYPE="submit" VALUE="Donner"></th></tr>';
        }
    }
    /**
     *Manage the members
     */
    if ($mode == 'admin' && $edit == 'members') {
        /**
         *The managing members can set ranges
         *to give different rights "laws"
         */
        // Check permissions
        if ($ally['ally_owner'] != $user->getId() && !$user_can_kick) {
            message($lang['Denied_access'], $lang['Members_list']);
        }
        /**
         *Kicker users requires the permission number 1
         */
        if (isset($kick)) {
            if ($ally['ally_owner'] != $user->getId() && !$user_can_kick) {
                message($lang['Denied_access'], $lang['Members_list']);
            }

            $u = doquery("SELECT * FROM {{table}} WHERE id='{$kick}' LIMIT 1", 'users', true);
            if ($u['ally_id'] == $ally['id'] && $u['id'] != $ally['ally_owner']) {
                doquery("UPDATE {{table}} SET `ally_id`='0', `ally_name`='', `ally_rank_id` = '0' WHERE `id`='{$u['id']}'", 'users');
            }
        } else if ($request->getPost('newrang') !== null) {
            $newrank = $request->getPost('newrang');
            $selectedUser = Wootook_Player_Model_Entity::factory($id);

            if ($newrank !== null && isset($ally_ranks[$newrank]) && $selectedUser->getId() != $ally['ally_owner']) {
                $selectedUser->setData('ally_rank_id', $newrank)->save();
            }
        }

        $template = gettemplate('alliance_admin_members_row');
        $f_template = gettemplate('alliance_admin_members_function');

        // Order of displaying the different users
        $sort2 = intval($request->getQuery('sort2'));
        if ($sort2) {
            $sort1 = intval($request->getQuery('sort1'));
            switch($sort1){
                case 1:
                    $sort = " ORDER BY `username`";
                    break;
                case 2:
                    $sort = " ORDER BY `username`";
                    break;
                case 4:
                    $sort = " ORDER BY `ally_register_time`";
                    break;
                case 5:
                    $sort = " ORDER BY `onlinetime`";
                    break;
                default:
                    $sort = " ORDER BY `id`";
                    break;
            }
            switch($sort2){
                case 1:
                    $sort .= " DESC;";
                    break;
                case 2:
                    $sort .= " ASC;";
                    break;
                default:
                    $sort .= " DESC;";
                    break;
            }
            $listuser = "SELECT * FROM {$db->getTable('users')} WHERE ally_id='{$user['ally_id']}'{$sort}";
        } else {
            $listuser = "SELECT * FROM {$db->getTable('users')} WHERE ally_id={$user['ally_id']}";
        }
        $user_list = $db->prepare($listuser);
        $user_list->execute();
        $i = 0;
        $page_list = '';
        $lang['memberzahl'] = $user_list->rowCount();

        while ($u = $user_list->fetch(PDO::FETCH_BOTH)) {
            $UserPoints = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '" . $u['id'] . "';", 'statpoints', true);
            $i++;
            $u['i'] = $i;
            $u['points'] = "" . pretty_number($UserPoints['total_points']) . "";
            $days = floor(round(time() - $u["onlinetime"]) / 3600 % 24);
            $u["onlinetime"] = str_replace("%s", $days, "%s d");
            // Number of the rank
            if ($ally['ally_owner'] == $u['id']) {
                $ally_range = ($ally['ally_owner_range'] == '')?$lang['Founder']:$ally['ally_owner_range'];
            } elseif (!isset($ally_ranks[$u['ally_rank_id']]['name'])) {
                $ally_range = $lang['Novate'];
            } else {
                $ally_range = $ally_ranks[$u['ally_rank_id']]['name'];
            }

            /*
             * this was a wierd spanish comment : "Here comes the fucking ..."
             * Don't know what it means ...
             */
            if ($ally['ally_owner'] == $u['id'] || ($rank == $u['id']) && !empty($ally_ranks)) {
                $u["functions"] = '';
            } elseif ($ally['ally_owner'] == $user->getId()) {
                $lang['Expel_user'] = 'Kick player';
                $lang['You_are_sure_want_kick_to'] = 'Are you sure to kick player %s ?';
                $lang['Set_range'] = 'Set rang';
                $f['Set_range'] = $lang['Set_range'];
                $f['Expel_user'] = $lang['Expel_user'];

                $f['You_are_sure_want_kick_to'] = str_replace("%s", $u['username'], $lang['You_are_sure_want_kick_to']);
                $f['id'] = $u['id'];
                $u["functions"] = parsetemplate($f_template, $f);
            } else {
                $u["functions"] = '';
            }
            // Form stuff
            if ($rank != $u['id'] || empty($ally_ranks)) {
                $u['ally_range'] = $ally_range;
            } else {
                $u['ally_range'] = '';
            }
            $u['ally_register_time'] = date("Y-m-d h:i:s", $u['ally_register_time']);
            $page_list .= parsetemplate($template, $u);
            $r['options'] = '';
            if ($rank == $u['id'] && !empty($ally_ranks)) {
                $lang['Rank_for'] = 'define new rank for %s';
                $r['Rank_for'] = str_replace("%s", $u['username'], $lang['Rank_for']);

                foreach($ally_ranks as $a => $b) {
                    $r['options'] .= "<option value=\"" . $a . "\"";
                    if ($u['ally_rank_id'] == $a) {
                        $r['options'] .= ' selected=selected';
                    }
                    $r['options'] .= ">{$b['name']}</option>";
                }
                $r['id'] = $u['id'];
                $r['Save'] = $lang['Save'];
                $page_list .= parsetemplate(gettemplate('alliance_admin_members_row_edit'), $r);
            }
        }
        // Change the link for order by
        switch($sort2){
            case 1:
                $s = 2;
                break;
            case 2:
                $s = 1;
                break;
            default:
                $s = 1;
                break;
        }

        if ($i != $ally['ally_members']) {
            doquery("UPDATE {{table}} SET `ally_members`={$db->quote($i)} WHERE `id`='{$ally['id']}'", 'alliance');
        }

        $lang['memberslist'] = $page_list;
        $lang['s'] = $s;
        $page .= parsetemplate(gettemplate('alliance_admin_members_table'), $lang);

        display($page, $lang['Members_administrate']);
        /**
         * Don't know why those comments are here...
         */
        // a=9 es para cambiar la etiqueta de la etiqueta.
        // a=10 es para cambiarle el nombre de la alianza
    }

    /**
     * Manage the requests
     */
    if ($mode == 'admin' && $edit == 'requests') {
        if ($ally['ally_owner'] != $user->getId() && !$user_bewerbungen_bearbeiten) {
            message($lang['Denied_access'], $lang['Check_the_requests']);
        }

        if ($action == "Accepter") {
            $u = doquery("SELECT * FROM {{table}} WHERE id=$show", 'users', true);
            // add points from the user who join the alliance
            doquery("UPDATE {{table}} SET
            ally_members=ally_members+1
            WHERE id='{$ally['id']}'", 'alliance');

            doquery("UPDATE {{table}} SET
            ally_name='{$ally['ally_name']}',
            ally_request_text='',
            ally_request='0',
            ally_id='{$ally['id']}',
            new_message=new_message+1,
            mnl_alliance=mnl_alliance+1
            WHERE id='{$show}'", 'users');

            $message = "Bonjour,<br>L'Alliance <b>{$ally['ally_name']}</b> a acceptÃ© votre candidature.<br>Charte:<br>{$request->getPost('text')}";
            // Send a message to the new member of the alliance
            doquery("INSERT INTO {{table}} SET
            `message_owner`=" .$db->quote($show).",
            `message_sender`='{$user->getId()}' ,
            `message_time`='" . time() . "',
            `message_type`='2',
            `message_from`='{$ally['ally_tag']}',
            `message_subject`='[" . $ally['ally_name'] . "] vous a acceptee!',
            `message_text`={$db->quote($message)}", "messages");

            header('Location: alliance.php?mode=admin&edit=requests');
            die();

        } elseif ($action == "Refuser" && $action != '') {
            doquery("UPDATE {{table}} SET ally_request_text='',ally_request='0',ally_id='0',new_message=new_message+1, mnl_alliance=mnl_alliance+1 WHERE id={$db->quote($show)}", 'users');

            $message = "Bonjour,<br>L'Alliance <b>{$ally['ally_name']}</b> a refusÃ© votre candidature.<br>Raison:<br>{$request->getPost('text')}";
            // Send a polite message to the player that he is too noobish for that alliance.
            doquery("INSERT INTO {{table}} SET
            `message_owner`=" .$db->quote($show).",
            `message_sender`='{$user->getId()}' ,
            `message_time`='" . time() . "',
            `message_type`='2',
            `message_from`='{$ally['ally_tag']}',
            `message_subject`='[" . $ally['ally_name'] . "] vous as refuse!',
            `message_text`={$db->quote($message)}", "messages");

            header('Location:alliance.php?mode=admin&edit=requests');
            die();
        }
        $row = gettemplate('alliance_admin_request_row');
        $i = 0;
        $parse = $lang;
        $parse['list'] = '';
        $query = doquery("SELECT id,username,ally_request_text,ally_register_time FROM {{table}} WHERE ally_request='{$ally['id']}'", 'users');
        while ($r = $query->fetch(PDO::FETCH_BOTH)) {
            // collect data that was chosen.
            if (isset($show) && $r['id'] == $show) {
                $s['username'] = $r['username'];
                $s['ally_request_text'] = nl2br($r['ally_request_text']);
                $s['id'] = $r['id'];
            }
            // the date when the application was sent
            $r['time'] = date("Y-m-d h:i:s", $r['ally_register_time']);
            $parse['list'] .= parsetemplate($row, $r);
            $i++;
        }
        if ($parse['list'] == '') {
            $parse['list'] = '<tr><th colspan=2>Il ne reste plus aucune candidature</th></tr>';
        }
        if (isset($show) && $show != 0 && $parse['list'] != '') {
            // Date of the application.
            $lang['Request_from'] = 'Request From %s';
            $lang['ally_request_text'] = $s['ally_request_text'];
            $lang['Request_answer'] = 'Type here your answer. (be nice :p)';
            $s['Request_from'] = str_replace('%s', $s['username'], $lang['Request_from']);
            $s['Motive_optional'] = $lang['Motive_optional'];
            $s['Request_answer'] = $lang['Request_answer'];
            $s['Request_responde'] = $lang['Request_responde'];
            $parse['request'] = parsetemplate(gettemplate('alliance_admin_request_form'), $s);
        } else {
            $parse['request'] = '';
        }

        $parse['ally_tag'] = $ally['ally_tag'];
        $parse['Back'] = $lang['Back'];

        $lang['There_is_hanging_request'] = '%n hanging request(s)';
        $parse['There_is_hanging_request'] = str_replace('%n', $i, $lang['There_is_hanging_request']);
        // $parse['list'] = $lang['Return_to_overview'];
        $page = parsetemplate(gettemplate('alliance_admin_request_table'), $parse);
        display($page, $lang['Check_the_requests']);
    }

    if ($mode == 'admin' && $edit == 'name') {
         // Changer le nom de l'alliance
         // Change the name of alliance

        $ally_ranks = unserialize($ally['ally_ranks']);
        // check permissions
        if ($ally['ally_owner'] != $user->getId() && !$user_admin) {
            message($lang['Denied_access'], $lang['Members_list']);
        }

        if ($request->getPost('newname') !== null) {
            // Y a le nouveau Nom
            // New name so lets update all
            $ally['ally_name'] = strip_tags($request->getPost('newname'));
            doquery("UPDATE {{table}} SET `ally_name` = ". $db->quote($ally['ally_name']) ." WHERE `id` = '". $user->getData('ally_id') ."';", 'alliance');
            doquery("UPDATE {{table}} SET `ally_name` = ". $db->quote($ally['ally_name']) ." WHERE `ally_id` = '". $ally['id'] ."';", 'users');
        }

        $parse['question']           = str_replace('%s', $ally['ally_name'], $lang['How_you_will_call_the_alliance_in_the_future']);
        $parse['New_name']           = $lang['New_name'];
        $parse['Change']             = $lang['Change'];
        $parse['name']               = 'newname';
        $parse['Return_to_overview'] = $lang['Return_to_overview'];
        $page .= parsetemplate(gettemplate('alliance_admin_rename'), $parse);
        display($page, $lang['Alliance_admin']);

    }

    if ($mode == 'admin' && $edit == 'tag') {
        // Changer le TAG l'alliance
        // change the TAG of the alliance
        $ally_ranks = unserialize($ally['ally_ranks']);

        // Check permissions
        if ($ally['ally_owner'] != $user->getId() && !$user_admin) {
            message($lang['Denied_access'], $lang['Members_list']);
        }

        if ($request->getPost('newtag') !== null) {
            // Y a le nouveau TAG
            // new TAG, lets update the table
            $ally['ally_tag'] = strip_tags($request->getPost('newtag'));
            doquery("UPDATE {{table}} SET `ally_tag` = ". $db->quote($ally['ally_tag']) ." WHERE `id` = '". $user->getData('ally_id') ."';", 'alliance');
        }

        $parse['question']           = str_replace('%s', $ally['ally_tag'], $lang['How_you_will_call_the_alliance_in_the_future']);
        $parse['New_name']           = $lang['New_name'];
        $parse['Change']             = $lang['Change'];
        $parse['name']               = 'newtag';
        $parse['Return_to_overview'] = $lang['Return_to_overview'];
        $page .= parsetemplate(gettemplate('alliance_admin_rename'), $parse);
        display($page, $lang['Alliance_admin']);
    }

    /**
     * Delete the alliance
     */
    if ($mode == 'admin' && $edit == 'exit') {
        // Array with the different ranks
        $ally_ranks = unserialize($ally['ally_ranks']);
        // check permissions
        if ($ally['ally_owner'] != $user->getId() && !$user_can_exit_alliance) {
            message($lang['Denied_access'], $lang['Members_list']);
        }
        doquery("UPDATE {{table}} SET `ally_id`='0', `ally_name` = '' WHERE `id`='{$user->getId()}'", 'users');
        doquery("DELETE FROM {{table}} WHERE id={$db->quote($ally['id'])}", "alliance");
        header('Location: alliance.php');
        exit;
    }
    {
     // Still work to do here. Code has to be revised
        if ($ally['ally_owner'] != $user->getId()) {
            $ally_ranks = unserialize($ally['ally_ranks']);
        }
        // Display the image of the alliance
        if ($ally['ally_ranks'] != '') {
            $ally['ally_ranks'] = "<tr><td colspan=2><img src=\"{$ally['ally_image']}\"></td></tr>";
        }
        // temporarily ... (google translate :D)
        if ($ally['ally_owner'] == $user->getId()) {
            $range = ($ally['ally_owner_range'] != '')?$lang['Founder']:$ally['ally_owner_range'];
        } elseif (isset($ally_ranks[$user['ally_rank_id']]['name'])) {
            $range = $ally_ranks[$user['ally_rank_id']]['name'];
        } else {
            $range = $lang['Novate'];
        }
        // Link to the list of members
        if ($ally['ally_owner'] == $user->getId() || (isset($ally_ranks[$user->getData('ally_rank_id')]['name']) && $ally_ranks[$user['ally_rank_id']]['memberlist'] != 0) ) {
            $lang['members_list'] = " (<a href=\"?mode=memberslist\">{$lang['Members_list']}</a>)";
        } else {
            $lang['members_list'] = '';
        }
        // The link to manage the alliance
        if ($ally['ally_owner'] == $user->getId() || (isset($ally_ranks[$user->getData('ally_rank_id')]['name']) && $ally_ranks[$user['ally_rank_id']]['administrieren'] != 0) ) {
            $lang['alliance_admin'] = " (<a href=\"?mode=admin&edit=ally\">{$lang['Alliance_admin']}</a>)";
        } else {
            $lang['alliance_admin'] = '';
        }
        // The link to send circular messages
        if ($ally['ally_owner'] == $user->getId() || (isset($ally_ranks[$user->getData('ally_rank_id')]['name']) && $ally_ranks[$user['ally_rank_id']]['mails'] != 0) ) {
            $lang['send_circular_mail'] = "<tr><th>{$lang['Circular_message']}</th><th><a href=\"?mode=circular\">{$lang['Send_circular_mail']}</a></th></tr>";
        } else {
            $lang['send_circular_mail'] = '';
        }
        // The link to view the applications
        $lang['requests'] = '';
        $request = $db->prepare("SELECT id FROM {$db->getTable('users')} WHERE ally_request=:allyid");
        $request->execute(array(':allyid' => $ally['id']));
        $request_count = $request->rowCount();
        if ($request_count != 0) {
            if ($ally['ally_owner'] == $user->getId() || $ally_ranks[$user['ally_rank_id']]['bewerbungen'] != 0)
                $lang['requests'] = "<tr><th>{$lang['Requests']}</th><th><a href=\"alliance.php?mode=admin&edit=requests\">{$request_count} request(s)</a></th></tr>";
        }
        if ($ally['ally_owner'] != $user->getId()) {
            $lang['ally_owner'] = MessageForm($lang['Exit_of_this_alliance'], "", "?mode=exit", $lang['Continue']);
        } else {
            $lang['ally_owner'] = '';
        }
        // Logo image
        $lang['ally_image'] = ($ally['ally_image'] != '')?
        "<tr><th colspan=2><img src=\"{$ally['ally_image']}\"></td></tr>":'';
        $lang['range'] = $range;

        $patterns[] = "#\[fc\]([a-z0-9\#]+)\[/fc\](.*?)\[/f\]#Ssi";
        $replacements[] = '<font color="\1">\2</font>';
        $patterns[] = '#\[img\](.*?)\[/img\]#Smi';
        $replacements[] = '<img src="\1" alt="\1" style="border:0px;" />';
        $patterns[] = "#\[fc\]([a-z0-9\#\ \[\]]+)\[/fc\]#Ssi";
        $replacements[] = '<font color="\1">';
        $patterns[] = "#\[/f\]#Ssi";
        $replacements[] = '</font>';
        $ally['ally_description'] = preg_replace($patterns, $replacements, $ally['ally_description']);
        $lang['ally_description'] = nl2br($ally['ally_description']);

        $ally['ally_text'] = preg_replace($patterns, $replacements, $ally['ally_text']);
        $lang['ally_text'] = nl2br($ally['ally_text']);

        $lang['ally_web'] = $ally['ally_web'];
        $lang['ally_tag'] = $ally['ally_tag'];
        $lang['ally_members'] = $ally['ally_members'];
        $lang['ally_name'] = $ally['ally_name'];

        $page .= parsetemplate(gettemplate('alliance_frontpage'), $lang);
        display($page, $lang['your_alliance']);
    }
}