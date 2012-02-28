<?php

class Wootook_Core_ErrorController
    extends Wootook_Core_Controller_Action
{
    public function noRouteAction()
    {
        $this->loadLayout('no-route');
        $this->renderLayout();
    }
}