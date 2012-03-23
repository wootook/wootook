<?php

class Wootook_Player_Resource_Entity_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    public function _construct()
    {
        $this->_init('users', 'Wootook_Player_Model_Entity');
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
        $onlineTime = (int) $onlineTime;

        if ($onlineTime > 0) {
            $this->addFieldToFilter('onlinetime', array(array(
                'gt' => new Wootook_Core_Database_Sql_Placeholder_Expression('UNIX_TIMESTAMP() - :online_time))', array('online_time' => $onlineTime))
                )));
        }

        return $this;
    }
}
