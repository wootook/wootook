<?php

class Wootook_Empire_Model_Builder_Item
    extends Wootook_Object
{
    protected $_index = null;

    public function getIndex()
    {
        return $this->_index;
    }

    public function setIndex($index)
    {
        $this->_index = $index;

        return $this;
    }
}