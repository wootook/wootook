<?php

abstract class Wootook_Empire_Block_Planet_Builder_QueueAbstract
    extends Wootook_Core_Block_Template
{
    protected $_player   = null;
    protected $_planet = null;
    protected $_itemTemplate = null;
    protected $_itemBlock = null;

    public function setPlayer(Wootook_Player_Model_Entity $player)
    {
        $this->_player = $player;

        return $this;
    }

    public function getPlayer()
    {
        if ($this->_player === null) {
            $this->_player = Wootook_Player_Model_Session::getSingleton()->getPlayer();
        }

        return $this->_player;
    }

    public function setPlanet(Wootook_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = $this->getPlayer()->getCurrentPlanet();
        }

        return $this->_planet;
    }

    public function setItemTemplate($template)
    {
        $this->_itemTemplate = $template;

        return $this;
    }

    public function getItemTemplate()
    {
        return $this->_itemTemplate;
    }

    public function setItemBlockType($blockType)
    {
        $this->_itemBlockType = $blockType;

        return $this;
    }

    public function getItemBlockType()
    {
        return $this->_itemBlockType;
    }

    public function getItemBlock(Wootook_Empire_Model_Builder_Item $item)
    {
        $index = $item->getIndex();
        $blockName = "item({$index})";

        return $this->getLayout()
            ->createBlock($this->getItemBlockType(), $blockName)
            ->setTemplate($this->getItemTemplate())
            ->setPlanet($this->getPlanet())
            ->setItem($item);
    }

    public function prepareLayout()
    {
        $parentBlock = $this->getLayout()
            ->createBlock('core/concat', $this->getNameInLayout() . '.item-list')
        ;
        $this->setPartial('item-list', $parentBlock);

        foreach ($this->getQueue() as $item) {
            $block = $this->getItemBlock($item);
            $parentBlock->setPartial($block->getNameInLayout(), $block);
        }

        return $this;
    }

    abstract public function getQueue();
}