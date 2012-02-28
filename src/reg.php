<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
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

define('INSIDE', true);
define('INSTALL', false);
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) .'/application/bootstrap.php';

includeLang('reg');

if (!empty($_POST) && isset($_POST['username']) && isset($_POST['planet_name']) && isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
    $session = Wootook::getSession(Wootook_Player_Model_Entity::SESSION_KEY);
    if ($_POST['password'] !== $_POST['password_confirm']) {
        $session->addError('Password and password confirmation does not match.');
    }
    if ($_POST['email'] !== $_POST['email_confirm']) {
        $session->addError('Email and email confirmation does not match.');
    }

    $user = null;
    if (true || count($session->getMessages(false)) === 0) {
        $user = Wootook_Player_Model_Entity::register($_POST['username'], $_POST['email'], $_POST['password']);

        if ($user !== null) {
            $user->getHomePlanet()->setName($_POST['planet_name'])->save();
        }
    }

    if ($user !== null && $user->getId()) {
        header("HTTP/1.1 302 Found");
        //header("Location: welcome.php");
        header("Location: overview.php");
    } else {
        header("HTTP/1.1 302 Found");
        header("Location: reg.php");
    }
    Wootook_Core_ErrorProfiler::unregister(true);
    exit(0);
}

$layout = new Wootook_Core_Layout();
$layout->load('registration');
$block = $layout->getBlock('registration');

echo $layout->render();
/*
function sendpassemail($emailaddress, $password)
{
    global $lang;

    $parse['gameurl'] = Wootook::getStaticUrl('/');
    $parse['password'] = $password;
    $email = parsetemplate($lang['mail_welcome'], $parse);
    $status = mymail($emailaddress, $lang['mail_title'], $email);
    return $status;
}

function mymail($to, $title, $body, $from = '')
{
    $from = trim($from);

    if (!$from) {
        $from = ADMINEMAIL;
    }

    $rp = ADMINEMAIL;

    $head = '';
    $head .= "Content-Type: text/plain \r\n";
    $head .= "Date: " . date('r') . " \r\n";
    $head .= "Return-Path: $rp \r\n";
    $head .= "From: $from \r\n";
    $head .= "Sender: $from \r\n";
    $head .= "Reply-To: $from \r\n";
    $head .= "Organization: $org \r\n";
    $head .= "X-Sender: $from \r\n";
    $head .= "X-Priority: 3 \r\n";
    $body = str_replace("\r\n", "\n", $body);
    $body = str_replace("\n", "\r\n", $body);

    return mail($to, $title, $body, $head);
}
*/
