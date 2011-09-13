<?php

class Legacies_Empire_Block_Planet_Shipyard
    extends Legacies_Empire_Block_Planet_BuilderAbstract
{
    protected $_planet = null;
    protected $_type = Legacies_Empire::TYPE_SHIP;

    protected $_allowedTypes = array(
        Legacies_Empire::TYPE_SHIP,
        Legacies_Empire::TYPE_DEFENSE
        );

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

    public function setAllowedTypes($types)
    {
        if (is_array($types)) {
            $this->_allowedTypes = $types;
        }
        return $this;
    }

    public function addAllowedType($type)
    {
        if (!in_array($type, $this->_allowedTypes)) {
            $this->_allowedTypes[] = $type;
        }
        return $this;
    }

    public function getAllowedTypes()
    {
        return $this->_allowedTypes;
    }

    public function setType($type)
    {
        if (in_array($type, $this->_allowedTypes)) {
            $this->_type = $type;
        }
        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function _initChildBlocks()
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();

        /** @var Legacies_Core_Block_Concat $parentBlock */
        $parentBlock = $this->getLayout()->getBlock('item-list.items');
        foreach ($types->getData($this->getType()) as $itemId) {
            if (!$this->getPlanet()->getShipyard()->checkAvailability($itemId)) {
                continue;
            }

            $block = $this->getItemBlock($itemId);
            $parentBlock->setPartial($block->getName(), $block);
        }

        return $this;
    }
}