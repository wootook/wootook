<?php

class Wootook_Empire_Model_Galaxy_Position
    extends Wootook_Core_Mvc_Model_Entity_SubTable
{
    protected function _init()
    {
        $this->_tableName = 'galaxy';
        $this->_idFieldNames = array('id_planet');
    }
}
