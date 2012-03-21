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

define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(__FILE__) .'/application/bootstrap.php';

$frontController = new Wootook_Core_Mvc_Controller_Front(Wootook::getRequest(), Wootook::getResponse());

$frontController
    ->addModule('core', 'Wootook_Core_Controller_', 'Wootook/Core/Controller')
    ->addModule('player', 'Wootook_Player_Controller_', 'Wootook/Player/Controller')
    ->addModule('empire', 'Wootook_Empire_Controller_', 'Wootook/Empire/Controller')
    ->addModule('legacies-empire', 'Legacies_Empire_Controller_', 'Legacies/Empire/Controller')
    ->dispatch()
    ->send();
