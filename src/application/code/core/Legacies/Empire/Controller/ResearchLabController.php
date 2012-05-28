<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 19/03/12
 * Time: 18:11
 * To change this template use File | Settings | File Templates.
 */
class Legacies_Empire_Controller_ResearchLabController
    extends Wootook_Player_Mvc_Controller_Registered
{
    public function preDispatch()
    {
        parent::preDispatch();

        $planet = $this->getCurrentPlanet();

        if ($planet->getElement(Legacies_Empire::ID_BUILDING_SHIPYARD) < 1) {
            $this->getSession()
                ->addError(Wootook::__('In order to do technological researches, you will need to build a research lab building.'));

            $this->_redirect('player/overview');
            return;
        }
    }

    public function indexAction()
    {
        $this->loadLayout('planet.research-lab');
        $this->renderLayout();
    }

    public function buildAction()
    {
        if (!is_numeric($researchId = $this->getRequest()->getParam('id'))) {
            $this->_redirect('*/*/view');
            return;
        }

        $this->getCurrentPlanet()->getResearchLab()->appendQueue($researchId);
        $this->getCurrentPlanet()->save();
        $this->getPlayer()->save();

        $this->_redirect('*/*/');
    }

    public function cancelAction()
    {
        if (!is_numeric($researchId = $this->getRequest()->getParam('id'))) {
            $this->_redirect('*/*/view');
            return;
        }

        $this->getCurrentPlanet()->getResearchLab()->dequeueItem($researchId);
        $this->getCurrentPlanet()->save();
        $this->getPlayer()->save();

        $this->_redirect('*/*/');
    }
}
