<?php

class Legacies_Empire_Block_Planet_Buildings
    extends Legacies_Empire_Block_Planet_BuilderAbstract
{
    protected $_planet = null;

    public function setPlanet(Legacies_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = Legacies_Empire_Model_User::getSingleton()
                ->getCurrentPlanet()
            ;
        }
        return $this->_planet;
    }

    public function _initChildBlocks()
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $type = Legacies_Empire::TYPE_BUILDING_PLANET;
        if ($this->getPlanet()->isMoon()) {
            $type = Legacies_Empire::TYPE_BUILDING_MOON;
        }

        /** @var Legacies_Core_Block_Concat $parentBlock */
        $parentBlock = $this->getLayout()->getBlock('item-list.items');
        foreach ($types->getData($type) as $itemId) {
            if (!$this->getPlanet()->checkAvailability($itemId)) {
                continue;
            }

            $block = $this->getItemBlock($itemId);
            $parentBlock->setPartial($block->getName(), $block);
        }

        return $this;
    }
}