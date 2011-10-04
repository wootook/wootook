<?php

abstract class Wootook_Empire_Block_Planet_BuilderAbstract
    extends Wootook_Core_Block_Template
{
    protected $_itemTemplate = null;
    protected $_itemBlock = null;

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

    public function getItemBlock($itemId)
    {
        $blockName = "item({$itemId})";

        return $this->getLayout()
            ->createBlock($this->getItemBlockType(), $blockName)
            ->setTemplate($this->getItemTemplate())
            ->setPlanet($this->getPlanet())
            ->setItemId($itemId);
    }

    public function prepareLayout()
    {
        parent::prepareLayout();

        $this->_initChildBlocks();

        return $this;
    }

    abstract protected function _initChildBlocks();
}