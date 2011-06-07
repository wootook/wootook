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
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) . '/common.php';

$mailData = array(
    'recipient' => NULL,
    'sender' => 'no-reply',
    'subject' => 'XNova:Legacies - Changement de mot de passe'
    );

includeLang('lostpassword');

$username = NULL;
if (!empty($_POST)) {
    if(isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        $username = mysql_real_escape_string($_POST['pseudo']);
        $sql =<<<EOF
SELECT users.email, users.username
  FROM {{table}} AS users
  WHERE users.username="{$username}"
  LIMIT 1
EOF;
        if (!($result = doquery($sql, 'users', true))) {
            message("Cet utilisateur n'existe pas", 'Erreur', 'lostpassword.php');
            die();
        }
        list($mailData['recipient'], $username) = $result;
    } else if(isset($_POST['email']) && !empty($_POST['email'])) {
        $email = mysql_real_escape_string($_POST['email']);
        $sql =<<<EOF
SELECT users.email, users.username
  FROM {{table}} AS users
  WHERE users.email="{$email}"
  LIMIT 1
EOF;
        if (!($result = doquery($sql, 'users', true))) {
            message("Cet email n'est utilisé par aucun joueur", 'Erreur', 'lostpassword.php');
            die();
        }
        list($mailData['recipient'], $username) = $result;
    } else {
        message('Veuillez entrer votre login ou votre email.', 'Erreur', 'lostpassword.php');
        die();
    }

    if (!is_null($mailData['recipient'])) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomPass = '';
        $size = rand(8, 10);
        for ($i = 0; $i < $size; $i++) {
            $randomPass .= $characters[rand(0, strlen($characters) - 1)];
        }

        $message =<<<EOF
Votre mot de passe a été modifié, veuillez trouver ci-dessous vos informations de connexion :
login : $username
mot de passe : $randomPass

A bientôt sur XNova:Legacies
EOF;

        $version = VERSION;
        $headers =<<<EOF
From: {$mailData['sender']}
X-Sender: Legacies/{$version}

EOF;
        mail($mailData['recipient'], $mailData['subject'], $message, $headers);

        $sql =<<<EOF
UPDATE {{table}} AS users
  SET users.password="{$randomPass}"
  WHERE users.username="$username"
EOF;

        doquery($sql, 'users');
        message('Mot de passe envoyé ! Veuillez regarder votre boite e-mail ou dans vos spam.', 'Nouveau mot de passe', 'index.php');
        die();
    }
}

$parse = $lang;
$page = parsetemplate(gettemplate('lostpassword'), $parse);
display($page, $lang['registry']);
