<?php

class Wootook_Player_Model_Message
    extends Wootook_Core_Mvc_Model_Entity_SubTable
{
    protected $_eventObject = 'message';
    protected $_eventPrefix = 'player.message';

    protected function _init()
    {
        $this->_tableName = 'messages';
        $this->_idFieldNames = array('message_id', 'message_owner');
    }
}
