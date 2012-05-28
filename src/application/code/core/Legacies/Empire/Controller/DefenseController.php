<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 19/03/12
 * Time: 18:11
 * To change this template use File | Settings | File Templates.
 */
class Legacies_Empire_Controller_DefenseController
    extends Wootook_Player_Mvc_Controller_Registered
{
    public function preDispatch()
    {
        parent::preDispatch();

        $planet = $this->getCurrentPlanet();

        if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) < 1) {
            $this->getSession()
                ->addError(Wootook::__('In order to build defenses you will need to build a shipyard building.'));

            $this->_redirect('player/overview');
            return;
        }
    }

    public function indexAction()
    {
        $this->loadLayout('planet.defense');

        /** @var Legacies_Empire_Block_Planet_Shipyard $block */
        $block = $this->getLayout()->getBlock('item-list');
        $block->setType(Legacies_Empire::TYPE_DEFENSE);

        $this->renderLayout();
    }

    public function buildAction()
    {
        if (!$this->getRequest()->isPost() || !is_array($defenseList = $this->getRequest()->getPost('id'))) {
            $this->_redirect('*/*/view');
            return;
        }

        $shipyard = $this->getCurrentPlanet()->getShipyard();
        foreach ($defenseList as $defenseId => $count) {
            $defenseId = intval($defenseId);
            $count = intval($count);

            $shipyard->appendQueue($defenseId, $count);
        }
        $this->getCurrentPlanet()->save();

        $this->_redirect('*/*/');
    }
}
