<?php

/**
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 * @uses Legacies_Empire_User
 */
class Legacies_Empire_Model_Planet_Resource
    extends Legacies_Core_Entity_SubTable
{
    public function _init()
    {
        $this->_tableName = 'planet_resource';
        $this->_idFieldNames = array(
            'resource_code',
            'planet_id'
            );
    }
}