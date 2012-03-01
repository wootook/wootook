<?php

class Wootook_Core_Controller_ErrorController
    extends Wootook_Core_Mvc_Controller_Action
{
    public function noRouteAction()
    {
        $this->loadLayout('no-route');
        $this->renderLayout();
    }
}