<?php

/**
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 * @uses Legacies_Empire_User
 */
class Legacies_Empire_Model_Planet_Production
    extends Legacies_Core_Entity_SubTable
{
    public function _init()
    {
        $this->_tableName = 'planet_production';
        $this->_idFieldNames = array(
            'production_code',
            'planet_id'
            );
    }
}