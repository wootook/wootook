<?php

abstract class Wootook_Empire_Block_Planet_Builder_Queue_ItemAbstract
    extends Wootook_Empire_Block_Planet_Builder_ItemAbstract
{
    protected $_item = null;

    protected $_itemIdField = null;

    public function setItem(Wootook_Empire_Model_Builder_Item $item)
    {
        $this->_item = $item;
        $this->setItemId($item->getData($this->_itemIdField));

        return $this;
    }

    public function getItem()
    {
        return $this->_item;
    }
}