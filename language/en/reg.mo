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
$lang['registry']          = 'Register';
$lang['form']              = 'Registration Form';
$lang['Register']          = 'SteadGame Registration';
$lang['Undefined']         = '- Undefined -';
$lang['Male']              = 'Male';
$lang['Female']            = 'Female';
$lang['Multiverse']        = 'XNova';
$lang['E-Mail']            = 'E-Mail address';
$lang['MainPlanet']        = 'Main planet\'s name';
$lang['GameName']          = 'In-Game Name';
$lang['Sex']               = 'Sex';
$lang['accept']            = 'I accept the agreement';
$lang['signup']            = ' Signup ';
$lang['neededpass']        = 'Password';
$lang['code_secu']          = 'Security';

// Send
$lang['mail_welcome']      = 'Thank you for registering at SteadGame({gameurl}) \nYour password is: {password}\n\nHave fun!\n{gameurl}';
$lang['mail_title']        = 'Registration';
$lang['thanksforregistry'] = 'Thank you for signing up! You will receive an email with your password.';
$lang['sender_message_ig'] = 'Admin';
$lang['subject_message_ig']= 'Welcome';
$lang['text_message_ig']   = 'Welcome to SteadGame, we wish you all the best and goodluck!';


// Errors
$lang['error_secu']        = 'Security code invalid!<br />';
$lang['error_mail']        = 'E-mail invalid!<br />';
$lang['error_planet']      = 'Error in planet name!<br />';
$lang['error_hplanetnum']  = 'You must use alphanumeric characters to name your planet!<br />';
$lang['error_character']   = 'Error in your name!<br />';
$lang['error_charalpha']   = 'The username must be alphanumeric characters!<br />';
$lang['error_password']    = 'Your password must be 4 character\'s minimum!<br />';
$lang['error_rgt']         = 'You must accept the Terms of Usage.<<br />';
$lang['error_userexist']   = 'Player name already exists.<br />';
$lang['error_emailexist']  = 'E-Mail is already in use.<br />';
$lang['error_sex']         = 'Error in sex!<br />';
$lang['error_mailsend']    = 'An error occurred while sending the email! Your password is: ';
$lang['reg_welldone']      = 'Registration complete!';

// Created by Perberos. All rights reversed (C) 2006
// Complet by XNova Team. All rights reversed (C) 2008
?>
