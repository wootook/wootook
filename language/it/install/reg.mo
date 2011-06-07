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
$lang['registry']          = 'Registrazione';
$lang['form']              = 'Modulo registrazione';
$lang['Register']          = 'Registrazione a XNova';
$lang['Undefined']         = '- indefinito -';
$lang['Male']              = 'Maschio';
$lang['Female']            = 'Femmina';
$lang['Multiverse']        = 'XNova';
$lang['E-Mail']            = 'Indirizzo email';
$lang['MainPlanet']        = 'Nome del pianeta madre';
$lang['GameName']          = 'Nome in gioco';
$lang['Sex']               = 'Sesso';
$lang['accept']            = 'Ho letto ed accetto le regole';
$lang['signup']            = ' Registrati ';
$lang['neededpass']        = 'Password';

// Send
$lang['mail_welcome']      = 'Grazie per l\'iscrizione al gioco ({gameurl}xnova-0.8/login.php)<br> La vostra password &egrave;: "{password}"<br> Buon divertimento!<br> {gameurl}';
$lang['mail_title']        = 'Registrazione';
$lang['thanksforregistry'] = 'Grazie per esservi iscritti! Riceverete un\'email con la vostra password.';

// Errors
$lang['error_mail']        = 'Email non valida!<br />';
$lang['error_planet']      = 'Errore nel nome del pianeta!.<br />';
$lang['error_hplanetnum']  = 'Si possono utilizzare solo caratteri alfanumeriic per il nome del pianeta!<br />';
$lang['error_character']   = 'Errore nel nome del giocatore!<br />';
$lang['error_charalpha']   = 'Il nome del giocatore deve essere composto solo da caratteri alfanumerici!<br />';
$lang['error_password']    = 'La password deve essere almeno di 4 caratteri!<br />';
$lang['error_rgt']         = 'Occorre accettare i Termini di utilizzo.<<br />';
$lang['error_userexist']   = 'Il nome del giocatore esiste gi&agrave;!<br />';
$lang['error_emailexist']  = 'Questa email &egrave; gi&agrave; utilizzata!<br />';
$lang['error_sex']         = 'Errore nel sesso!<br />';
$lang['error_mailsend']    = 'Si &egrave; verificato un errore durante l\'invio dell\'email! La password &egrave; : ';
$lang['reg_welldone']      = 'Registrazione terminata con successo!';

// Created by Perberos. All rights reversed (C) 2006
// Complet by XNova Team. All rights reversed (C) 2008
?>
