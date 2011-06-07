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

	$QryTableAks         = "CREATE TABLE `{{table}}` ( ";
	$QryTableAks        .= "`id` bigint(20) unsigned NOT NULL auto_increment, ";
	$QryTableAks        .= "`name` varchar(50) collate latin1_general_ci default NULL, ";
	$QryTableAks        .= "`teilnehmer` text collate latin1_general_ci, ";
	$QryTableAks        .= "`flotten` text collate latin1_general_ci, ";
	$QryTableAks        .= "`ankunft` int(32) default NULL, ";
	$QryTableAks        .= "`galaxy` int(2) default NULL, ";
	$QryTableAks        .= "`system` int(4) default NULL, ";
	$QryTableAks        .= "`planet` int(2) default NULL, ";
	$QryTableAks        .= "`eingeladen` int(11) default NULL, ";
	$QryTableAks        .= "PRIMARY KEY  (`id`) ";
	$QryTableAks        .= ") ENGINE=MyISAM;";

	// Table annonce
	$QryTableAnnonce     = "CREATE TABLE `{{table}}` ( ";
	$QryTableAnnonce    .= "`id` int(11) NOT NULL auto_increment, ";
	$QryTableAnnonce    .= "`user` text collate latin1_general_ci NOT NULL, ";
	$QryTableAnnonce    .= "`galaxie` int(11) NOT NULL, ";
	$QryTableAnnonce    .= "`systeme` int(11) NOT NULL, ";
	$QryTableAnnonce    .= "`metala` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "`cristala` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "`deuta` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "`metals` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "`cristals` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "`deuts` bigint(11) NOT NULL, ";
	$QryTableAnnonce    .= "PRIMARY KEY  (`id`) ";
	$QryTableAnnonce    .= ") ENGINE=MyISAM;";

	// Table alliance
	$QryTableAlliance    = "CREATE TABLE `{{table}}` ( ";
	$QryTableAlliance   .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTableAlliance   .= "`ally_name` varchar(32) character set latin1 default '', ";
	$QryTableAlliance   .= "`ally_tag` varchar(8) character set latin1 default '', ";
	$QryTableAlliance   .= "`ally_owner` int(11) NOT NULL default '0', ";
	$QryTableAlliance   .= "`ally_register_time` int(11) NOT NULL default '0', ";
	$QryTableAlliance   .= "`ally_description` text character set latin1, ";
	$QryTableAlliance   .= "`ally_web` varchar(255) character set latin1 default '', ";
	$QryTableAlliance   .= "`ally_text` text character set latin1, ";
	$QryTableAlliance   .= "`ally_image` varchar(255) character set latin1 default '', ";
	$QryTableAlliance   .= "`ally_request` text character set latin1, ";
	$QryTableAlliance   .= "`ally_request_waiting` text character set latin1, ";
	$QryTableAlliance   .= "`ally_request_notallow` tinyint(4) NOT NULL default '0', ";
	$QryTableAlliance   .= "`ally_owner_range` varchar(32) character set latin1 default '', ";
	$QryTableAlliance   .= "`ally_ranks` text character set latin1, ";
	$QryTableAlliance   .= "`ally_members` int(11) NOT NULL default '0', ";
	$QryTableAlliance   .= "PRIMARY KEY  (`id`) ";
	$QryTableAlliance   .= ") ENGINE=MyISAM;";

	// Table banned
	$QryTableBanned      = "CREATE TABLE `{{table}}` ( ";
	$QryTableBanned     .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTableBanned     .= "`who` varchar(11) character set latin1 NOT NULL default '', ";
	$QryTableBanned     .= "`theme` text character set latin1 NOT NULL, ";
	$QryTableBanned     .= "`who2` varchar(11) character set latin1 NOT NULL default '', ";
	$QryTableBanned     .= "`time` int(11) NOT NULL default '0', ";
	$QryTableBanned     .= "`longer` int(11) NOT NULL default '0', ";
	$QryTableBanned     .= "`author` varchar(11) character set latin1 NOT NULL default '', ";
	$QryTableBanned     .= "`email` varchar(20) character set latin1 NOT NULL default '', ";
	$QryTableBanned     .= "KEY `ID` (`id`) ";
	$QryTableBanned     .= ") ENGINE=MyISAM;";

	// Table buddy
	$QryTableBuddy       = "CREATE TABLE `{{table}}` ( ";
	$QryTableBuddy      .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTableBuddy      .= "`sender` int(11) NOT NULL default '0', ";
	$QryTableBuddy      .= "`owner` int(11) NOT NULL default '0', ";
	$QryTableBuddy      .= "`active` tinyint(3) NOT NULL default '0', ";
	$QryTableBuddy      .= "`text` text character set latin1, ";
	$QryTableBuddy      .= "PRIMARY KEY  (`id`) ";
	$QryTableBuddy      .= ") ENGINE=MyISAM;";

	// Table chat
	$QryTableChat        = "CREATE TABLE `{{table}}` ( ";
	$QryTableChat       .= "`messageid` int(5) unsigned NOT NULL auto_increment, ";
	$QryTableChat       .= "`user` varchar(255) NOT NULL default '', ";
	$QryTableChat       .= "`message` text NOT NULL, ";
	$QryTableChat       .= "`timestamp` int(11) NOT NULL default '0', ";
	$QryTableChat       .= "PRIMARY KEY  (`messageid`) ";
	$QryTableChat       .= ") ENGINE=MyISAM;";

	// Table config
	$QryTableConfig      = "CREATE TABLE `{{table}}` ( ";
	$QryTableConfig     .= "`config_name` varchar(64) character set latin1 NOT NULL default '', ";
	$QryTableConfig     .= "`config_value` text character set latin1 NOT NULL ";
	$QryTableConfig     .= ") ENGINE=MyISAM;";

	// Valeurs de base de la config
	$QryInsertConfig     = "INSERT INTO `{{table}}` ";
	$QryInsertConfig    .= "(`config_name`           , `config_value`) VALUES ";
	$QryInsertConfig    .= "('users_amount'          , '0'), ";
	$QryInsertConfig    .= "('game_speed'            , '2500'), ";
	$QryInsertConfig    .= "('fleet_speed'           , '2500'), ";
	$QryInsertConfig    .= "('resource_multiplier'   , '1'), ";
	$QryInsertConfig    .= "('Fleet_Cdr'             , '30'), ";
	$QryInsertConfig    .= "('Defs_Cdr'              , '30'), ";
	$QryInsertConfig    .= "('initial_fields'        , '163'), ";
	$QryInsertConfig    .= "('COOKIE_NAME'           , 'XNova Legacies'), ";
	$QryInsertConfig    .= "('game_name'             , 'XNova Legacies'), ";
	$QryInsertConfig    .= "('game_disable'          , '1'), ";
	$QryInsertConfig    .= "('close_reason'          , 'Le jeu est clos pour le moment!'), ";
	$QryInsertConfig    .= "('metal_basic_income'    , '20'), ";
	$QryInsertConfig    .= "('crystal_basic_income'  , '10'), ";
	$QryInsertConfig    .= "('deuterium_basic_income', '0'), ";
	$QryInsertConfig    .= "('energy_basic_income'   , '0'), ";
	$QryInsertConfig    .= "('BuildLabWhileRun'      , '0'), ";
	$QryInsertConfig    .= "('LastSettedGalaxyPos'   , '1'), ";
	$QryInsertConfig    .= "('LastSettedSystemPos'   , '8'), ";
	$QryInsertConfig    .= "('LastSettedPlanetPos'   , '3'), ";
	$QryInsertConfig    .= "('urlaubs_modus_erz'     , '1'), ";
	$QryInsertConfig    .= "('noobprotection'        , '1'), ";
	$QryInsertConfig    .= "('noobprotectiontime'    , '5000'), ";
	$QryInsertConfig    .= "('noobprotectionmulti'   , '5'), ";
	$QryInsertConfig    .= "('forum_url'             , 'http://board.xnova-ng.org/' ), ";
	$QryInsertConfig    .= "('OverviewNewsFrame'     , '1' ), ";
	$QryInsertConfig    .= "('OverviewNewsText'      , 'Bienvenue sur le nouveau serveur XNova Legacies' ), ";
	$QryInsertConfig    .= "('OverviewExternChat'    , '0' ), ";
	$QryInsertConfig    .= "('OverviewExternChatCmd' , '' ), ";
	$QryInsertConfig    .= "('OverviewBanner'        , '0' ), ";
	$QryInsertConfig    .= "('OverviewClickBanner'   , '' ), ";
	$QryInsertConfig    .= "('ExtCopyFrame'          , '0' ), ";
	$QryInsertConfig    .= "('ExtCopyOwner'          , '' ), ";
	$QryInsertConfig    .= "('ExtCopyFunct'          , '' ), ";
	$QryInsertConfig    .= "('ForumBannerFrame'      , '0' ), ";
	$QryInsertConfig    .= "('stat_settings'          , '1000' ), ";
	$QryInsertConfig    .= "('link_enable'          , '0' ), ";
	$QryInsertConfig    .= "('link_name'          , '' ), ";
	$QryInsertConfig    .= "('link_url'          , '' ), ";
	$QryInsertConfig    .= "('enable_announces'      , '1' ), ";
	$QryInsertConfig    .= "('enable_marchand'                 , '1'), ";
	$QryInsertConfig    .= "('enable_notes'                 , '1'), ";
	$QryInsertConfig    .= "('bot_name'                 , 'XNoviana Reali'), ";
	$QryInsertConfig    .= "('bot_adress'          , 'xnova@xnova-ng.org' ), ";
	$QryInsertConfig    .= "('banner_source_post'          , '../images/bann.png' ), ";
	$QryInsertConfig    .= "('ban_duration'          , '30' ), ";
	$QryInsertConfig    .= "('enable_bot'          , '0' ), ";
	$QryInsertConfig    .= "('enable_bbcode'          , '1' ), ";
	$QryInsertConfig    .= "('debug'                 , '0') ";
	$QryInsertConfig    .= ";";


	// Table declared (multicomptes)
	$QryTabledeclared         = "CREATE TABLE `{{table}}` ( ";
	$QryTabledeclared        .= "`declarator`TEXT NOT NULL, ";
	$QryTabledeclared        .= "`declared_1`TEXT NOT NULL, ";
	$QryTabledeclared        .= "`declared_2`TEXT NOT NULL, ";
	$QryTabledeclared        .= "`declared_3`TEXT NOT NULL, ";
	$QryTabledeclared        .= "`reason`TEXT NOT NULL, ";
	$QryTabledeclared        .= "`declarator_name`TEXT NOT NULL ";

	$QryTabledeclared       .= ") ENGINE=MyISAM;";

	// Table errors
	$QryTableErrors      = "CREATE TABLE `{{table}}` ( ";
	$QryTableErrors     .= "`error_id` bigint(11) NOT NULL auto_increment, ";
	$QryTableErrors     .= "`error_sender` varchar(32) character set latin1 NOT NULL default '0', ";
	$QryTableErrors     .= "`error_time` int(11) NOT NULL default '0', ";
	$QryTableErrors     .= "`error_type` varchar(32) character set latin1 NOT NULL default 'unknown', ";
	$QryTableErrors     .= "`error_text` text character set latin1, ";
	$QryTableErrors     .= "PRIMARY KEY  (`error_id`) ";
	$QryTableErrors     .= ") ENGINE=MyISAM;";

	// Table fleets
	$QryTableFleets      = "CREATE TABLE `{{table}}` ( ";
	$QryTableFleets     .= "`fleet_id` bigint(11) NOT NULL auto_increment, ";
	$QryTableFleets     .= "`fleet_owner` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_mission` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_amount` bigint(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_array` text character set latin1, ";
	$QryTableFleets     .= "`fleet_start_time` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_start_galaxy` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_start_system` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_start_planet` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_start_type` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_time` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_stay` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_galaxy` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_system` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_planet` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_end_type` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_taget_owner` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_resource_metal` bigint(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_resource_crystal` bigint(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_resource_deuterium` bigint(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_target_owner` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`fleet_group` int (11) NOT NULL DEFAULT '0', ";
	$QryTableFleets     .= "`fleet_mess` int(11) NOT NULL default '0', ";
	$QryTableFleets     .= "`start_time` int(11) default NULL, ";
	$QryTableFleets     .= "PRIMARY KEY  (`fleet_id`) ";
	$QryTableFleets     .= ") ENGINE=MyISAM;";

	// Table galaxy
	$QryTableGalaxy      = "CREATE TABLE `{{table}}` ( ";
	$QryTableGalaxy     .= "`galaxy` int(2) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`system` int(3) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`planet` int(2) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`id_planet` int(11) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`metal` bigint(11) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`crystal` bigint(11) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`id_luna` int(11) NOT NULL default '0', ";
	$QryTableGalaxy     .= "`luna` int(2) NOT NULL default '0', ";
	$QryTableGalaxy     .= "KEY `galaxy` (`galaxy`), ";
	$QryTableGalaxy     .= "KEY `system` (`system`), ";
	$QryTableGalaxy     .= "KEY `planet` (`planet`) ";
	$QryTableGalaxy     .= ") ENGINE=MyISAM;";

	// Table iraks
	$QryTableIraks       = "CREATE TABLE `{{table}}` ( ";
	$QryTableIraks      .= "`id` bigint(20) unsigned NOT NULL auto_increment, ";
	$QryTableIraks      .= "`zeit` int(32) default NULL, ";
	$QryTableIraks      .= "`galaxy` int(2) default NULL, ";
	$QryTableIraks      .= "`system` int(4) default NULL, ";
	$QryTableIraks      .= "`planet` int(2) default NULL, ";
	$QryTableIraks      .= "`galaxy_angreifer` int(2) default NULL, ";
	$QryTableIraks      .= "`system_angreifer` int(4) default NULL, ";
	$QryTableIraks      .= "`planet_angreifer` int(2) default NULL, ";
	$QryTableIraks      .= "`owner` int(32) default NULL, ";
	$QryTableIraks      .= "`zielid` int(32) default NULL, ";
	$QryTableIraks      .= "`anzahl` int(32) default NULL, ";
	$QryTableIraks      .= "`primaer` int(32) default NULL, ";
	$QryTableIraks      .= "PRIMARY KEY  (`id`) ";
	$QryTableIraks      .= ") ENGINE=MyISAM;";

	// Table lunas
	$QryTableLunas       = "CREATE TABLE `{{table}}` ( ";
	$QryTableLunas      .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTableLunas      .= "`id_luna` int(11) NOT NULL default '0', ";
	$QryTableLunas      .= "`name` varchar(11) character set latin1 NOT NULL default 'Lune', ";
	$QryTableLunas      .= "`image` varchar(11) character set latin1 NOT NULL default 'mond', ";
	$QryTableLunas      .= "`destruyed` int(11) NOT NULL default '0', ";
	$QryTableLunas      .= "`id_owner` int(11) default NULL, ";
	$QryTableLunas      .= "`galaxy` int(11) default NULL, ";
	$QryTableLunas      .= "`system` int(11) default NULL, ";
	$QryTableLunas      .= "`lunapos` int(11) default NULL, ";
	$QryTableLunas      .= "`temp_min` int(11) NOT NULL default '0', ";
	$QryTableLunas      .= "`temp_max` int(11) NOT NULL default '0', ";
	$QryTableLunas      .= "`diameter` int(11) NOT NULL default '0', ";
	$QryTableLunas      .= "PRIMARY KEY  (`id`) ";
	$QryTableLunas      .= ") ENGINE=MyISAM;";

	// Table messages
	$QryTableMessages    = "CREATE TABLE `{{table}}` ( ";
	$QryTableMessages   .= "`message_id` bigint(11) NOT NULL auto_increment, ";
	$QryTableMessages   .= "`message_owner` int(11) NOT NULL default '0', ";
	$QryTableMessages   .= "`message_sender` int(11) NOT NULL default '0', ";
	$QryTableMessages   .= "`message_time` int(11) NOT NULL default '0', ";
	$QryTableMessages   .= "`message_type` int(11) NOT NULL default '0', ";
	$QryTableMessages   .= "`message_from` varchar(48) character set latin1 default NULL, ";
	$QryTableMessages   .= "`message_subject` varchar(48) character set latin1 default NULL, ";
	$QryTableMessages   .= "`message_text` text character set latin1, ";
	$QryTableMessages   .= "PRIMARY KEY  (`message_id`) ";
	$QryTableMessages   .= ") ENGINE=MyISAM;";

	// Table notes
	$QryTableNotes       = "CREATE TABLE `{{table}}` ( ";
	$QryTableNotes      .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTableNotes      .= "`owner` int(11) default NULL, ";
	$QryTableNotes      .= "`time` int(11) default NULL, ";
	$QryTableNotes      .= "`priority` tinyint(1) default NULL, ";
	$QryTableNotes      .= "`title` varchar(32) character set latin1 default NULL, ";
	$QryTableNotes      .= "`text` text character set latin1, ";
	$QryTableNotes      .= "PRIMARY KEY  (`id`) ";
	$QryTableNotes      .= ") ENGINE=MyISAM;";

	// Table planets
	$QryTablePlanets     = "CREATE TABLE `{{table}}` ( ";
	$QryTablePlanets    .= "`id` bigint(11) NOT NULL auto_increment, ";
	$QryTablePlanets    .= "`name` varchar(255) character set latin1 default NULL, ";
	$QryTablePlanets    .= "`id_owner` int(11) default NULL, ";
	$QryTablePlanets    .= "`id_level` int(11) default NULL, ";
	$QryTablePlanets    .= "`galaxy` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`system` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`planet` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`last_update` int(11) default NULL, ";
	$QryTablePlanets    .= "`planet_type` int(11) NOT NULL default '1', ";
	$QryTablePlanets    .= "`destruyed` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`b_building` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`b_building_id` text character set latin1 NOT NULL, ";
	$QryTablePlanets    .= "`b_tech` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`b_tech_id` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`b_hangar` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`b_hangar_id` text character set latin1 NOT NULL, ";
	$QryTablePlanets    .= "`b_hangar_plus` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`image` varchar(32) character set latin1 NOT NULL default 'normaltempplanet01', ";
	$QryTablePlanets    .= "`diameter` int(11) NOT NULL default '12800', ";
	$QryTablePlanets    .= "`points` bigint(20) default '0', ";
	$QryTablePlanets    .= "`ranks` bigint(20) default '0', ";
	$QryTablePlanets    .= "`field_current` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`field_max` int(11) NOT NULL default '163', ";
	$QryTablePlanets    .= "`temp_min` int(3) NOT NULL default '-17', ";
	$QryTablePlanets    .= "`temp_max` int(3) NOT NULL default '23', ";
	$QryTablePlanets    .= "`metal` double(132,8) NOT NULL default '0.00000000', ";
	$QryTablePlanets    .= "`metal_perhour` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`metal_max` bigint(20) default '100000', ";
	$QryTablePlanets    .= "`crystal` double(132,8) NOT NULL default '0.00000000', ";
	$QryTablePlanets    .= "`crystal_perhour` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`crystal_max` bigint(20) default '100000', ";
	$QryTablePlanets    .= "`deuterium` double(132,8) NOT NULL default '0.00000000', ";
	$QryTablePlanets    .= "`deuterium_perhour` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`deuterium_max` bigint(20) default '100000', ";
	$QryTablePlanets    .= "`energy_used` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`energy_max` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`metal_mine` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`crystal_mine` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`deuterium_sintetizer` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`solar_plant` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`fusion_plant` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`robot_factory` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`nano_factory` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`hangar` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`metal_store` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`crystal_store` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`deuterium_store` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`laboratory` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`terraformer` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`ally_deposit` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`silo` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`small_ship_cargo` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`big_ship_cargo` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`light_hunter` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`heavy_hunter` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`crusher` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`battle_ship` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`colonizer` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`recycler` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`spy_sonde` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`bomber_ship` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`solar_satelit` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`destructor` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`dearth_star` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`battleship` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`supernova` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`misil_launcher` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`small_laser` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`big_laser` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`gauss_canyon` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`ionic_canyon` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`buster_canyon` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`small_protection_shield` enum('0', '1') NOT NULL default '0', ";
	$QryTablePlanets    .= "`big_protection_shield` enum('0', '1') NOT NULL default '0', ";
	$QryTablePlanets    .= "`interceptor_misil` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`interplanetary_misil` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`metal_mine_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`crystal_mine_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`deuterium_sintetizer_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`solar_plant_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`fusion_plant_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`solar_satelit_porcent` int(11) NOT NULL default '10', ";
	$QryTablePlanets    .= "`mondbasis` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`phalanx` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`sprungtor` bigint(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "`last_jump_time` int(11) NOT NULL default '0', ";
	$QryTablePlanets    .= "PRIMARY KEY  (`id`) ";
	$QryTablePlanets    .= ") ENGINE=MyISAM;";

	// Table rw
	$QryTableRw          = "CREATE TABLE `{{table}}` ( ";
	$QryTableRw         .= "`id_owner1` int(11) NOT NULL default '0', ";
	$QryTableRw         .= "`id_owner2` int(11) NOT NULL default '0', ";
	$QryTableRw         .= "`rid` varchar(72) character set latin1 NOT NULL, ";
	$QryTableRw         .= "`raport` text character set latin1 NOT NULL, ";
	$QryTableRw         .= "`a_zestrzelona` tinyint(3) unsigned NOT NULL default '0', ";
	$QryTableRw         .= "`time` int(10) unsigned NOT NULL default '0', ";
	$QryTableRw         .= "UNIQUE KEY `rid` (`rid`), ";
	$QryTableRw         .= "KEY `id_owner1` (`id_owner1`,`rid`), ";
	$QryTableRw         .= "KEY `id_owner2` (`id_owner2`,`rid`), ";
	$QryTableRw         .= "KEY `time` (`time`), ";
	$QryTableRw         .= "FULLTEXT KEY `raport` (`raport`) ";
	$QryTableRw         .= ") ENGINE=MyISAM;";

	// Table statpoints
	$QryTableStatPoints  = "CREATE TABLE `{{table}}` ( ";
	$QryTableStatPoints .= "`id_owner` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`id_ally` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`stat_type` int(2) NOT NULL, ";
	$QryTableStatPoints .= "`stat_code` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`tech_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`tech_old_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`tech_points` bigint(20) NOT NULL, ";
	$QryTableStatPoints .= "`tech_count` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`build_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`build_old_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`build_points` bigint(20) NOT NULL, ";
	$QryTableStatPoints .= "`build_count` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`defs_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`defs_old_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`defs_points` bigint(20) NOT NULL, ";
	$QryTableStatPoints .= "`defs_count` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`fleet_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`fleet_old_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`fleet_points` bigint(20) NOT NULL, ";
	$QryTableStatPoints .= "`fleet_count` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`total_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`total_old_rank` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`total_points` bigint(20) NOT NULL, ";
	$QryTableStatPoints .= "`total_count` int(11) NOT NULL, ";
	$QryTableStatPoints .= "`stat_date` int(11) NOT NULL, ";
	$QryTableStatPoints .= "KEY `TECH` (`tech_points`), ";
	$QryTableStatPoints .= "KEY `BUILDS` (`build_points`), ";
	$QryTableStatPoints .= "KEY `DEFS` (`defs_points`), ";
	$QryTableStatPoints .= "KEY `FLEET` (`fleet_points`), ";
	$QryTableStatPoints .= "KEY `TOTAL` (`total_points`) ";
	$QryTableStatPoints .= ") ENGINE=MyISAM;";

	// Table users
	$QryTableUsers       = "CREATE TABLE `{{table}}` ( ";
	$QryTableUsers      .= "`id` bigint(11) unsigned NOT NULL auto_increment PRIMARY KEY, ";
	$QryTableUsers      .= "`username` varchar(64) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`password` varchar(64) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`email` varchar(64) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`email_2` varchar(64) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`lang` varchar(8) character set latin1 NOT NULL default 'fr', ";
	$QryTableUsers      .= "`authlevel` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`sex` char(1) character set latin1 default NULL, ";
	$QryTableUsers      .= "`avatar` varchar(255) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`sign` text character set latin1, ";
	$QryTableUsers      .= "`id_planet` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`galaxy` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`system` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`planet` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`current_planet` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`user_lastip` varchar(16) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`ip_at_reg` varchar(16) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`user_agent` text character set latin1 NOT NULL, ";
	$QryTableUsers      .= "`current_page` text character set latin1 NOT NULL, ";
	$QryTableUsers      .= "`register_time` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`onlinetime` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`dpath` varchar(255) character set latin1 NOT NULL default '', ";
	$QryTableUsers      .= "`design` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`noipcheck` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`planet_sort` tinyint(1) NOT NULL default '0', ";
	$QryTableUsers      .= "`planet_sort_order` tinyint(1) NOT NULL default '0', ";
	$QryTableUsers      .= "`spio_anz` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`settings_tooltiptime` tinyint(4) NOT NULL default '5', ";
	$QryTableUsers      .= "`settings_fleetactions` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`settings_allylogo` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`settings_esp` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`settings_wri` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`settings_bud` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`settings_mis` tinyint(4) NOT NULL default '1', ";
	$QryTableUsers      .= "`settings_rep` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`urlaubs_modus` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`urlaubs_until` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`db_deaktjava` tinyint(4) NOT NULL default '0', ";
	$QryTableUsers      .= "`new_message` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`fleet_shortcut` text character set latin1, ";
	$QryTableUsers      .= "`b_tech_planet` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`spy_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`computer_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`military_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`defence_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`shield_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`energy_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`hyperspace_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`combustion_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`impulse_motor_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`hyperspace_motor_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`laser_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`ionic_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`buster_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`intergalactic_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`expedition_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`graviton_tech` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`ally_id` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`ally_name` varchar(32) character set latin1 default '', ";
	$QryTableUsers      .= "`ally_request` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`ally_request_text` text character set latin1, ";
	$QryTableUsers      .= "`ally_register_time` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`ally_rank_id` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`current_luna` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`kolorminus` varchar(11) character set latin1 NOT NULL default 'red', ";
	$QryTableUsers      .= "`kolorplus` varchar(11) character set latin1 NOT NULL default '#00FF00', ";
	$QryTableUsers      .= "`kolorpoziom` varchar(11) character set latin1 NOT NULL default 'yellow', ";
	$QryTableUsers      .= "`rpg_geologue` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_amiral` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_ingenieur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_technocrate` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_espion` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_constructeur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_scientifique` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_commandant` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_points` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_stockeur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_defenseur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_destructeur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_general` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_bunker` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_raideur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`rpg_empereur` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`lvl_minier` int(11) NOT NULL default '1', ";
	$QryTableUsers      .= "`lvl_raid` int(11) NOT NULL default '1', ";
	$QryTableUsers      .= "`xpraid` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`xpminier` int(11) NOT NULL default '0', ";
	$QryTableUsers      .= "`raids` bigint(20) NOT NULL default '0', ";
	$QryTableUsers      .= "`p_infligees` bigint(20) NOT NULL default '0', ";
	$QryTableUsers      .= "`mnl_alliance` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_joueur` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_attaque` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_spy` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_exploit` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_transport` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_expedition` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_general` INT( 11 ) NOT NULL , ";
	$QryTableUsers      .= "`mnl_buildlist` INT (11) NOT NULL , ";
	$QryTableUsers      .= "`bana` int(11) default NULL , ";
	$QryTableUsers      .= "`multi_validated` int(11) default NULL , ";
	$QryTableUsers      .= "`banaday` int(11) default NULL , ";
	$QryTableUsers      .= "`raids1` int(11) default NULL , ";
	$QryTableUsers      .= "`raidswin` int(11) default NULL , ";
	$QryTableUsers      .= "`raidsloose` int(11) default NULL  ";
	$QryTableUsers      .= ") ENGINE=MyISAM;";

	// Multi
	$QryTableMulti       = "CREATE TABLE `{{table}}` ( ";
	$QryTableMulti      .= "`id` int(11) NOT NULL auto_increment, ";
	$QryTableMulti      .= "`player` bigint(11) unsigned NOT NULL, ";
	$QryTableMulti      .= "`sharer` bigint(11) unsigned NOT NULL, ";
	$QryTableMulti      .= "`reason` text character set latin1 NOT NULL, ";
	$QryTableMulti      .= "PRIMARY KEY  (`id`) ";
	$QryTableMulti      .= ") ENGINE=MyISAM;";

?>