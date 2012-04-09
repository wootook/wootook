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
require_once dirname(__FILE__) .'/application/bootstrap.php';

$mode = Wootook::getRequest()->getPost('mode');

if ($mode == 'addit') {
    $adapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_write');
    $adapter->insert()
        ->into($adapter->getTable('declared'))
        ->set('declarator', $user->getId())
        ->set('declarator_name', $user->getUsername())
        ->set('declared_1', Wootook::getRequest()->getPost('dec1'))
        ->set('declared_2', Wootook::getRequest()->getPost('dec2'))
        ->set('declared_3', Wootook::getRequest()->getPost('dec2'))
        ->set('reason', Wootook::getRequest()->getPost('reason'))
        ->execute()
    ;

    $adapter->update()
        ->into($adapter->getTable('users'))
        ->set('multi_validated', 1)
    ;

    message("Merci, votre demande a ete prise en compte. Les autres joueurs que vous avez implique doivent egalement et imperativement suivre cette procedure aussi.", "Ajout");
}

includeLang('admin');
$Page = parsetemplate(gettemplate("add_declare"), $lang);

display($Page, "Declaration d\'IP partagee", false, '', true);

