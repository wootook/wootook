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


if (!defined('INSIDE')) {
	die("attemp hacking");
}

// Registration form
$lang['registry']          = 'Inscription';
$lang['form']              = 'Formulaire';
$lang['Register']          = 'XNova Inscription';
$lang['Undefined']         = '- ind&eacute;fini -';
$lang['Male']              = 'Homme';
$lang['Female']            = 'Femme';
$lang['Multiverse']        = 'XNova';
$lang['E-Mail']            = 'Adresse e-Mail';
$lang['MainPlanet']        = 'Nom de votre plan&egrave;te m&egrave;re';
$lang['GameName']          = 'Pseudo';
$lang['Sex']               = 'Sexe';
$lang['accept']            = 'J\'acc&egrave;pte le r&egrave;glement';
$lang['signup']            = ' S\'enregistrer ';
$lang['neededpass']        = 'Mot de passe';
$lang['code_secu']          = 'Securite';

// Send
$lang['mail_welcome']      = 'Merci beaucoup de votre inscription &agrave; notre jeu ({gameurl}) \nVotre mot de passe est : {password}\n\nBon amusement !\n{gameurl}';
$lang['mail_title']        = 'Enregistrment';
$lang['thanksforregistry'] = 'Merci de vous &ecirc;tre inscrit ! Vous allez recevoir un mail avec votre mot de passe.';
$lang['sender_message_ig'] = 'Admin';
$lang['subject_message_ig']= 'Bienvenue';
$lang['text_message_ig']   = 'Bienvenue sur XNova, nous vous souhaitons bon jeu et bonne chance !';


// Errors
$lang['error_secu']        = 'Code de securite invalide !<br />';
$lang['error_mail']        = 'E-mail invalide !<br />';
$lang['error_planet']      = 'Erreur dans votre nom de plan&egrave;te !.<br />';
$lang['error_hplanetnum']  = 'Vous devez utiliser des caract&egrave;res alphanum&eacute;rique pour votre nom de plan&egrave;te !<br />';
$lang['error_character']   = 'Erreur dans le nom du joueur !<br />';
$lang['error_charalpha']   = 'Le pseudo doit etre conpose de caractere alphanumerique !<br />';
$lang['error_password']    = 'Le mot de passe doit faire 4 caracteres au minimum !<br />';
$lang['error_rgt']         = 'Vous devez accepter les conditions d\'utilisation.<<br />';
$lang['error_userexist']   = 'Ce nom de joueur existe d&eacute;j&agrave; !<br />';
$lang['error_emailexist']  = 'Cet e-mail est d&eacute;j&agrave; utilis&eacute; !<br />';
$lang['error_sex']         = 'Erreur dans le sexe !<br />';
$lang['error_mailsend']    = 'Une erreur s\'est produite lors de l\'envoi du courriel! Votre mot de passe est : ';
$lang['reg_welldone']      = 'Inscription termin&eacute;e !';

// Created by Perberos. All rights reversed (C) 2006
// Complet by XNova Team. All rights reversed (C) 2008
?>