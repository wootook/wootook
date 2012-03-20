<?php

class Wootook_Player_Mvc_Controller_Registered
    extends Wootook_Core_Mvc_Controller_Action
{
    public function getSession()
    {
        return Wootook_Player_Model_Session::getSingleton();
    }

    public function getPlayer()
    {
        return $this->getSession()->getPlayer();
    }

    public function getCurrentPlanet()
    {
        return $this->getPlayer()->getCurrentPlanet();
    }

    public function preDispatch()
    {
        if (!$this->getSession()->isLoggedIn()) {
            $this->_redirectLogin();
        }
    }

    protected function _redirectLogin()
    {
        return $this->_redirect('player/account/login');
    }
}
