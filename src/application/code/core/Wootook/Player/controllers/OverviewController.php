<?php

class Wootook_Player_OverviewController
    extends Wootook_Player_Controller_Registered
{
    public function indexAction()
    {
        $this->loadLayout('player.overview');
        $this->renderLayout();
        /*
        $this->getResponse()
            ->setRedirect(Wootook::getStaticUrl('overview.php'), Wootook_Core_Controller_Response_Http::REDIRECT_TEMPORARY);
        */
    }
}