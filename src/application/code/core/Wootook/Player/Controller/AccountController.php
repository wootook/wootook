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
            $this->_redirect('*/overview');
            return;
        }

        $this->loadLayout('player.login');
        $this->_prepareLayoutMessages(Wootook_Player_Model_Entity::SESSION_KEY);
        $this->renderLayout();
    }

    public function loginPostAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            $this->_redirect('*/*/login');
            return;
        }

        if ($request->getPost('username') === null || $request->getPost('password') === null) {
            $this->_redirect('*/*/login');
            return;
        }

        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        }

        $session->login($request->getPost('username'), $request->getPost('password'), (bool) $request->getPost('rememberme'));

        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        } else {
            $this->_redirect('*/*/login');
            return;
        }
    }

    public function logoutAction()
    {
        $session = Wootook_Player_Model_Session::getSingleton();
        if ($session->isLoggedIn()) {
            $session->logout();
        } else {
            $this->_redirect('*/*/login');
            return;
        }

        $this->_redirect('');
    }

    public function newAction()
    {
        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        }

        $this->loadLayout('player.registration');
        $this->_prepareLayoutMessages(Wootook_Player_Model_Entity::SESSION_KEY);
        $this->renderLayout();
    }

    public function newPostAction()
    {
        $session = Wootook_Player_Model_Session::getSingleton();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        }

        $request = $this->getRequest();

        if ($request->getPost('password') != $request->getPost('password_confirm')) {
            $session->addError(Wootook::__('Passwords does not match. Please check your input.'));
            $this->_redirect('*/*/new');
            return;
        }

        if ($request->getPost('email') != $request->getPost('email_confirm')) {
            $session->addError(Wootook::__('Both emails does not match. Please check your input.'));
            $this->_redirect('*/*/new');
            return;
        }

        try {
            $user = Wootook_Player_Model_Entity::register($request->getPost('username'), $request->getPost('email'), $request->getPost('password'));
        } catch (Wootook_Empire_Exception_RuntimeException $e) {
            $session->addError(Wootook::__('Could not create user: %s', $e->getMessage()));
            $this->_redirect('*/*/new');
            return;
        }

        if (!$user || !$user->getId()) {
            $session->addError(Wootook::__('Could not create user. Please contact the game administrator for more information.'));
            $this->_redirect('*/*/new');
            return;
        }

        $session->setLoggedIn($user);
        if ($request->getPost('planet_name') != '') {
            $user->getHomePlanet()->setName($request->getPost('planet_name'))->save();
        } else {
            $user->getHomePlanet()->setName(Wootook::__('Planet'))->save();
        }

        $this->_redirect('*/overview');
    }

    public function lostPasswordAction()
    {
        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        }

        $this->loadLayout('player.lost-password');
        $this->_prepareLayoutMessages(Wootook_Player_Model_Entity::SESSION_KEY);
        $this->renderLayout();
    }

    /**
     * Send new password by mail
     */
    public function lostPasswordPostAction()
    {
        $session = new Wootook_Player_Model_Session();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/overview');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirect('*/*/lost-password');
            return;
        }

        $email = $this->getRequest()->getPost('email');
        $username = $this->getRequest()->getPost('username');

        $player = new Wootook_Player_Model_Entity();
        $player->loadByEmail($email);

        if (!$player->getId() || strtolower($player->getUsername()) != strtolower(trim($username))) {
            $session->addError(Wootook::__('The information you entered could not be found.'));
            $this->_redirect('*/*/lost-password');
            return;
        }

        $chars = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P',
            'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e',
            'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', '2', '3', '4', '5', '6', '7', '8', '9'
            );

        shuffle($chars);
        $passwordChars = array_slice($chars, 0, 10);
        $newPassword = implode($passwordChars);

        $player->setPassword($newPassword)->save();

        try {
            $mailer = new Wootook_Core_Email();
            $mailer->send(
                array($player->getEmail() => $player->getUsername()),
                array('contact@wootook.org' => 'Wootook'),
                Wootook::__('Your new password'),
                Wootook::__('Your new password is %s', $newPassword));
        } catch (Wootook_Core_Exception_RuntimeException $e) {
            Wootook_Player_Model_Session::getSingleton()
                ->addError($e->getMessage());

            $this->_redirect('*/*/lost-password');
            return;
        }

        $this->_redirect('*/*/login');
    }

    public function editAction()
    {
    }

    public function optionsAction()
    {
    }
}
