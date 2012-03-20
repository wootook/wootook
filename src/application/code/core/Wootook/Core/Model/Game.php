<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Core_Model_Game
    extends Wootook_Core_Mvc_Model_Entity
{
    const DEFAULT_CODE = 'default';

    protected $_eventPrefix = 'core.game';
    protected $_eventObject = 'game';

    protected function _init()
    {
        $this->_tableName = 'core_game';
        $this->_idFieldName = 'game_id';
    }
}
