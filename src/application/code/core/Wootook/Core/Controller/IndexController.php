<?php

class Wootook_Core_Controller_IndexController
    extends Wootook_Core_Mvc_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout('home');
        $this->renderLayout();
    }
}
