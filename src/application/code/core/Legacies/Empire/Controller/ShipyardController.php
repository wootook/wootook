<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 19/03/12
 * Time: 18:11
 * To change this template use File | Settings | File Templates.
 */
class Legacies_Empire_Controller_ShipyardController
    extends Wootook_Player_Mvc_Controller_Registered
{
    public function preDispatch()
    {
        parent::preDispatch();

        $planet = $this->getCurrentPlanet();

        if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) < 1) {
            $this->getSession()
                ->addError(Wootook::__('In order to build ships you will need to build a shipyard building.'));

            $this->_redirect('player/overview');
            return;
        }
    }

    public function indexAction()
    {
        $this->loadLayout('planet.shipyard');
        $this->renderLayout();
    }

    public function buildAction()
    {
        if (!$this->getRequest()->isPost() || !is_array($shipList = $this->getRequest()->getPost('id'))) {
            $this->_redirect('*/*/');
            return;
        }

        $shipyard = $this->getCurrentPlanet()->getShipyard();
        foreach ($shipList as $shipId => $count) {
            $shipId = intval($shipId);
            $count = intval($count);

            $shipyard->appendQueue($shipId, $count);
        }
        $this->getCurrentPlanet()->save();

        $this->_redirect('*/*/');
    }
}
