<?php

class Legacies_Empire_Model_Galaxy_Position
    extends Legacies_Core_Entity_SubTable
{
    protected function _init()
    {
        $this->_tableName = 'galaxy';
        $this->_idFieldNames = array('id_planet');
    }
}