<?php

class Wootook_Admin_IndexController
    extends Wootook_Admin_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('index', 'user');
    }
}