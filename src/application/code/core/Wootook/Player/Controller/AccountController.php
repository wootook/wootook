<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Wootook_Player_Controller_AccountController
    extends Wootook_Core_Mvc_Controller_Action
{
    public function loginAction()
    {
        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('player/overview');
            return;
        }

        $this->loadLayout('player.login');
        $this->renderLayout();
    }

    public function loginPostAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->_redirect('player/account/login');
            return;
        }

        if ($request->getPost('username') === null || $request->getPost('password') === null) {
            $this->_redirect('player/account/login');
            return;
        }

        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('player/overview');
            return;
        }

        $session->login($request->getPost('username'), $request->getPost('password'), (bool) $request->getPost('rememberme'));

        if ($session->isLoggedIn()) {
            $this->_redirect('player/overview');
        } else {
            $this->_redirect('player/account/login');
        }
    }

    public function logoutAction()
    {
        $session = Wootook_Player_Model_Session::getSingleton();
        if ($session->isLoggedIn()) {
            $session->logout();
        }

        $this->_redirect('');
    }

    public function newAction()
    {
        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('player/overview');
            return;
        }

        $this->loadLayout('player.registration');
        $this->renderLayout();
    }

    public function logoutSuccessAction()
    {
    }

    public function editAction()
    {
    }

    public function optionsAction()
    {
    }
}