<?php

class Wootook_Empire_Block_Overview_Fleet_List
    extends Wootook_Core_Block_Template
{
    protected $_itemTemplate = null;
    protected $_itemBlockType = null;

    protected $_fleetCollection = null;

    public function getFleetCollection()
    {
        if ($this->_fleetCollection === null) {
            $player = Wootook_Player_Model_Session::getSingleton()->getPlayer();
            $this->_fleetCollection = $player->getVisibleFleets();
        }
        return $this->_fleetCollection;
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

    public function getItemBlock($fleet)
    {
        $blockName = $this->getNameInLayout() . ".item({$fleet->getId()})";

        return $this->getLayout()
            ->createBlock($this->getItemBlockType(), $blockName)
            ->setTemplate($this->getItemTemplate())
            ->setFleetItem($fleet);
    }

    public function prepareLayout()
    {
        parent::prepareLayout();

        $this->_initChildBlocks();

        return $this;
    }

    public function _initChildBlocks()
    {
        $parentBlock = $this->getLayout()
            ->createBlock('core/concat', $this->getNameInLayout() . '.item-list')
        ;
        $this->setPartial('item-list', $parentBlock);

        foreach ($this->getFleetCollection()->load() as $fleet) {
            $block = $this->getItemBlock($fleet);
            $parentBlock->setPartial($block->getNameInLayout(), $block);
        }

        return $this;
    }
}
