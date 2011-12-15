<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Core_Model_Website
    extends Wootook_Core_Entity
{
    const DEFAULT_CODE = 'default';

    protected $_eventPrefix = 'core.website';
    protected $_eventObject = 'website';

    protected function _init()
    {
        $this->_tableName = 'core_website';
        $this->_idFieldName = 'website_id';
    }
}