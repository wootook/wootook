<?php

abstract class Wootook_Empire_Block_Planet_Builder_QueueAbstract
    extends Wootook_Core_Block_Template
{
    protected $_user   = null;
    protected $_planet = null;
    protected $_itemTemplate = null;
    protected $_itemBlock = null;

    public function setUser(Wootook_Empire_Model_User $user)
    {
        $this->_user = $user;

        return $this;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Wootook_Empire_Model_User::getSingleton();
        }

        return $this->_user;
    }

    public function setPlanet(Wootook_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = $this->getUser()->getCurrentPlanet();
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