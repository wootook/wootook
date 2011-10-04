<?php

class Wootook_Empire_Model_User_Message
    extends Wootook_Core_Entity_SubTable
{
    protected $_eventObject = 'message';
    protected $_eventPrefix = 'user.message';

    protected function _init()
    {
        $this->_tableName = 'messages';
        $this->_idFieldNames = array('message_id', 'message_owner');
    }
}