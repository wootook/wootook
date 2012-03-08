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


function DisplayGameSettingsPage($CurrentUser) {
	global $lang;

	includeLang('admin/settings');

	if (in_array((int) $CurrentUser['authlevel'], array(LEVEL_ADMIN))) {
		if (isset($_POST['opt_save']) && $_POST['opt_save'] == "1") {
			// Jeu Ouvert ou Ferm� !
			if (isset($_POST['closed']) && $_POST['closed'] == 'on') {
			    Wootook::setConfig('game/general/active', true, 1, 1);
			} else {
			    Wootook::setConfig('game/general/active', false, 1, 1);
			}

			if (isset($_POST['close_reason'])) {
			    Wootook::setConfig('game/general/closing-message', $_POST['close_reason'], 1, 1);
			}

			// Y a un News Frame ? !
			if (isset($_POST['newsframe']) && $_POST['newsframe'] == 'on') {
			    Wootook::setConfig('game/news/active', true, 1, 1);
			} else {
			    Wootook::setConfig('game/news/active', false, 1, 1);
			}

			if (isset($_POST['NewsText'])) {
			    Wootook::setConfig('game/news/content', $_POST['NewsText'], 1, 1);
			}

			// Y a un TCHAT externe ??
			if (isset($_POST['chatframe']) && $_POST['chatframe'] == 'on') {
			    Wootook::setConfig('engine/options/chat', true, 1, 1);
			} else {
			    Wootook::setConfig('engine/options/chat', false, 1, 1);
			}

			if (isset($_POST['ga']) && $_POST['ga'] == 'on') {
			    Wootook::setConfig('engine/options/ga', true, 1, 1);
			} else {
			    Wootook::setConfig('engine/options/ga', false, 1, 1);
			}
			if (isset($_POST['ga_id']) && !empty($_POST['ga_id'])) {
			    Wootook::setConfig('engine/options/ga-id', $_POST['ga_id'], 1, 1);
			}

			// Y a un BANNER Frame ?
			if (isset($_POST['bannerframe']) && $_POST['bannerframe'] == 'on') {
			    Wootook::setConfig('engine/options/banner', true, 1, 1);
			} else {
			    Wootook::setConfig('engine/options/banner', false, 1, 1);
			}

			// Nom du Jeu
			if (isset($_POST['game_name']) && !empty($_POST['game_name'])) {
			    Wootook::setConfig('game/general/name', $_POST['game_name'], 1, 1);
			}

			// Adresse du Forum
			if (isset($_POST['forum_url']) && !empty($_POST['forum_url'])) {
			    Wootook::setConfig('game/general/boards-url', $_POST['forum_url'], 1, 1);
			}

			// Vitesse du Jeu
			if (isset($_POST['game_speed']) && is_numeric($_POST['game_speed'])) {
			    Wootook::setConfig('game/speed/general', $_POST['game_speed'], 1, 1);
			}

			// Vitesse des Flottes
			if (isset($_POST['fleet_speed']) && is_numeric($_POST['fleet_speed'])) {
			    Wootook::setConfig('game/speed/fleet', $_POST['fleet_speed'], 1, 1);
			}

			// Multiplicateur de Production
			if (isset($_POST['resource_multiplier']) && is_numeric($_POST['resource_multiplier'])) {
			    Wootook::setConfig('game/resource/multiplier', $_POST['resource_multiplier'], 1, 1);
			}

			// Taille de la planete mère
			if (isset($_POST['initial_fields']) && is_numeric($_POST['initial_fields'])) {
			    Wootook::setConfig('resource/initial/fields', $_POST['initial_fields'], 1, 1);
			}

			// Revenu de base Metal
			if (isset($_POST['metal_basic_income']) && is_numeric($_POST['metal_basic_income'])) {
			    Wootook::setConfig('resource/initial/metal', $_POST['metal_basic_income'], 1, 1);
			}

			// Revenu de base Cristal
			if (isset($_POST['crystal_basic_income']) && is_numeric($_POST['crystal_basic_income'])) {
			    Wootook::setConfig('resource/initial/cristal', $_POST['crystal_basic_income'], 1, 1);
			}

			// Revenu de base Deuterium
			if (isset($_POST['deuterium_basic_income']) && is_numeric($_POST['deuterium_basic_income'])) {
			    Wootook::setConfig('resource/initial/deuterium', $_POST['deuterium_basic_income'], 1, 1);
			}

			// Revenu de base Energie
			if (isset($_POST['energy_basic_income']) && is_numeric($_POST['energy_basic_income'])) {
			    Wootook::setConfig('resource/initial/energy', $_POST['energy_basic_income'], 1, 1);
			}

			// Lien supplémentaire dans le menu
			if (isset($_POST['url_link_']) && is_numeric($_POST['url_link_'])) {
			    Wootook::setConfig('game/general/boards-url', $_POST['url_link_'], 1, 1);
			}

			// Image de la bannière
			if (isset($_POST['banner_source_post'])) {
			    Wootook::setConfig('engine/options/banner', $_POST['banner_source_post'], 1, 1);
			}
			// 1 point = ??? Ressources ?
	        if (isset($_POST['stat_settings']) && is_numeric($_POST['stat_settings'])) {
			    Wootook::setConfig('game/resource/multiplier', $_POST['stat_settings'], 1, 1);
			}
			// Activation -ou non- des annonces
			if (isset($_POST['enable_announces_']) && is_numeric($_POST['enable_announces_'])) {
			    Wootook::setConfig('engine/options/announces', $_POST['enable_announces_'], 1, 1);
			}
			// Activation -ou non- du marchand
			if (isset($_POST['enable_marchand_']) && is_numeric($_POST['enable_marchand_'])) {
			    Wootook::setConfig('engine/options/retailer', $_POST['enable_marchand_'], 1, 1);
			}
			// Activation -ou non- des notes
			if (isset($_POST['enable_notes_']) && is_numeric($_POST['enable_notes_'])) {
			    Wootook::setConfig('engine/options/notes', $_POST['enable_notes_'], 1, 1);
			}
			// Nom du bot antimulti
			if (isset($_POST['name_bot'])) {
			    Wootook::setConfig('engine/bot/name', $_POST['name_bot'], 1, 1);
			}
			// email du bot antimulti
			if (isset($_POST['adress_bot'])) {
			    Wootook::setConfig('engine/bot/email', $_POST['adress_bot'], 1, 1);
			}

			// Activation -ou non- des notes
			if (isset($_POST['duration_ban']) && is_numeric($_POST['duration_ban'])) {
				Wootook::setConfig('engine/ban/duration', $_POST['duration_ban'], 1, 1);
			}

			// Activation -ou non- du bot
			if (isset($_POST['bot_enable']) && is_numeric($_POST['bot_enable'])) {
				Wootook::setConfig('engine/bot/active', $_POST['bot_enable'], 1, 1);
			}

			// BBCode ou pas ?
			if (isset($_POST['bbcode_field']) && is_numeric($_POST['bbcode_field'])) {
				Wootook::setConfig('engine/options/bbcode', $_POST['bbcode_field'], 1, 1);
			}

			AdminMessage('Options changees avec succes !', 'Succes', '?');
		} else {
			$parse                           = $lang;
			$parse['game_name']              = Wootook::getGameConfig('game/general/name');
			$parse['game_speed']             = Wootook::getGameConfig('game/speed/general');
			$parse['fleet_speed']            = Wootook::getGameConfig('game/speed/fleet');
			$parse['resource_multiplier']    = Wootook::getGameConfig('game/resource/multiplier');
			$parse['forum_url']              = Wootook::getGameConfig('game/general/boards-url');
			$parse['initial_fields']         = Wootook::getGameConfig('resource/initial/fields');
			$parse['metal_basic_income']     = Wootook::getGameConfig('resource/initial/metal');
			$parse['crystal_basic_income']   = Wootook::getGameConfig('resource/initial/cristal');
			$parse['deuterium_basic_income'] = Wootook::getGameConfig('resource/initial/deuterium');
			$parse['energy_basic_income']    = Wootook::getGameConfig('resource/initial/energy');
			$parse['enable_link']            = $gameConfig['link_enable'];
			$parse['name_link']              = Wootook::getGameConfig('game/general/extra-url-title');
			$parse['url_link']               = Wootook::getGameConfig('game/general/extra-url');
			$parse['enable_announces']       = Wootook::getGameConfig('engine/options/announces');
			$parse['enable_marchand']        = Wootook::getGameConfig('engine/options/retailer');
			$parse['enable_notes']           = Wootook::getGameConfig('engine/options/notes');
			$parse['bot_name']               = Wootook::getGameConfig('engine/bot/name');
			$parse['bot_adress']             = Wootook::getGameConfig('engine/bot/email');
			$parse['ban_duration']           = Wootook::getGameConfig('engine/ban/duration');
			$parse['enable_bot']             = Wootook::getGameConfig('engine/bot/active');
			$parse['enable_bbcode']          = Wootook::getGameConfig('engine/options/bbcode');

			$parse['banner_source_post']     = Wootook::getGameConfig('engine/options/banner');
			$parse['stat_settings']          = Wootook::getGameConfig('game/resource/multiplier');

			$parse['closed']                 = (!Wootook::getGameConfig('game/general/active')) ? " checked = 'checked' ":"";
			$parse['close_reason']           = Wootook::getGameConfig('game/general/closing-message');

			$parse['newsframe']              = (Wootook::getGameConfig('game/news/active')) ? " checked = 'checked' ":"";
			$parse['NewsTextVal']            = Wootook::getGameConfig('game/news/content');

			$parse['chatframe']              = ($gameConfig['OverviewExternChat'] == 1) ? " checked = 'checked' ":"";
			$parse['ExtTchatVal']            = stripslashes( $gameConfig['OverviewExternChatCmd'] );

			$parse['ga']                     = (Wootook::getGameConfig('engine/options/ga')) ? " checked = 'checked' ":"";
			$parse['ga_id']                  = Wootook::getGameConfig('engine/options/ga-id');

			$parse['bannerframe']            = ($gameConfig['ForumBannerFrame'] == 1) ? " checked = 'checked' ":"";

			$PageTPL                         = gettemplate('admin/options_body');
			$Page                            = parsetemplate($PageTPL, $parse);

			display($Page, $lang['adm_opt_title'], false, '', true);
		}
	} else {
		AdminMessage($lang['sys_noalloaw'], $lang['sys_noaccess']);
	}
	return $Page;
}

$Page = DisplayGameSettingsPage($user);

