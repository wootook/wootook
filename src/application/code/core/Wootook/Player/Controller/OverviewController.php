<?php

class Wootook_Player_Controller_OverviewController
    extends Wootook_Player_Mvc_Controller_Registered
{
    public function indexAction()
    {
        $this->loadLayout('player.overview');
        $this->_prepareLayoutMessages(Wootook_Player_Model_Entity::SESSION_KEY);
        $this->renderLayout();
    }
}