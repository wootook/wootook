<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 19/03/12
 * Time: 18:46
 * To change this template use File | Settings | File Templates.
 */
class Wootook_Empire_Controller_BuildingsController
    extends Wootook_Player_Mvc_Controller_Registered
{
    public function indexAction()
    {
        $this->loadLayout('planet.buildings');
        $this->renderLayout();
    }

    public function buildAction()
    {
        if (!is_numeric($buildingId = $this->getRequest()->getParam('id'))) {
            $this->_redirect('*/*/view');
            return;
        }

        $this->getCurrentPlanet()->appendBuildingQueue($buildingId);
        $this->getCurrentPlanet()->save();

        $this->_redirect('*/*/');
    }

    public function cancelAction()
    {
        if (!is_numeric($buildingId = $this->getRequest()->getParam('id'))) {
            $this->_redirect('*/*/view');
            return;
        }

        $this->getCurrentPlanet()->dequeueBuilding($buildingId);
        $this->getCurrentPlanet()->save();

        $this->_redirect('*/*/');
    }
}
