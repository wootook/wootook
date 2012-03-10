<?php

class Wootook_Player_Resource_Entity_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    public function _construct()
    {
        $this->_init(array('user' => 'users'), 'Wootook_Player_Model_Entity');
    }

    public function addAuthlevelToFilter(Array $levels, $exclude = false)
    {
        if ($exclude === true) {
            $this->addFieldToFilter('authlevel', array(array('nin' => $levels)));
        } else {
            $this->addFieldToFilter('authlevel', array(array('in' => $levels)));
        }

        return $this;
    }

    public function addIsOnlineToFilter($onlineTime = 900)
    {
        if ($onlineTime > 0) {
            $this->getSelect()->where("user.onlinetime>(UNIX_TIMESTAMP() - {$this->getReadConnection()->quote($onlineTime)}))");
        }

        return $this;
    }
}