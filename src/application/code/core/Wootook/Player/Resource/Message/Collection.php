<?php

class Wootook_Player_Resource_Message_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    public function _construct()
    {
        $this->_init('messages', 'Wootook_Player_Model_Message');
    }

    public function addPlayerToFilter(Wootook_Player_Model_Entity $player)
    {
        $this->addFieldToFilter('message_owner', $player->getId());

        return $this;
    }

    public function addUnreadToFilter()
    {
        $this->addFieldToFilter('message_read_at', array(array('lteq' => 0)));

        return $this;
    }
}
