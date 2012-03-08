<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-2010, Wootook Support Team <http://wootook.org>
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

if (!defined('ROOT_PATH')) {
    die('Hacking attempt');
}

// Liste de champs pour l'indication des messages en attante
$messfields = array (
    0   => "mnl_spy",
    1   => "mnl_joueur",
    2   => "mnl_alliance",
    3   => "mnl_attaque",
    4   => "mnl_exploit",
    5   => "mnl_transport",
    15  => "mnl_expedition",
    97  => "mnl_general",
    99  => "mnl_buildlist",
    100 => "new_message"
    );

$resource = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

$requirements = Wootook_Empire_Helper_Config_Requirements::getSingleton();

$pricelist = Wootook_Empire_Helper_Config_Prices::getSingleton();

$CombatCaps = Wootook_Empire_Helper_Config_Combat::getSingleton();

$ProdGrid = Wootook_Empire_Helper_Config_Production::getSingleton();

$reslist = Wootook_Empire_Helper_Config_Types::getSingleton();
