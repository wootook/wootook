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

        $date = new Wootook_Core_DateTime();
        $date->sub($onlineTime, Wootook_Core_DateTime::TIMESTAMP);

        if ($onlineTime > 0) {
            $this->addFieldToFilter('onlinetime', array(array(
                Wootook_Core_Database_Sql_Select::OPERATOR_DATE => array('from' => $date)
                )));
        }

        return $this;
    }
}
