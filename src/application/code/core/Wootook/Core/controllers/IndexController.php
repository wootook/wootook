<?php

class Wootook_Core_IndexController
    extends Wootook_Core_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout('home');
        $this->renderLayout();
    }
}