<?php

class Wootook_Player_Block_TopBar
    extends Wootook_Core_Block_Template
{
    public function getSession()
    {
        return Wootook_Player_Model_Session::getSingleton();
    }

    public function isLoggedIn()
    {
        return $this->getSession()->isLoggedIn();
    }

    public function getPlayer()
    {
        return $this->getSession()->getPlayer();
    }

    public function getPlayerCount()
    {
        $collection = new Wootook_Player_Resource_Entity_Collection($this->getPlayer()->getReadConnection());
        $collection->addAuthlevelToFilter(array(LEVEL_ADMIN), true);

        return $collection->getSize();
    }

    public function getOnlinePlayerCount()
    {
        $collection = new Wootook_Player_Resource_Entity_Collection($this->getPlayer()->getReadConnection());
        $collection->addAuthlevelToFilter(array(LEVEL_ADMIN), true);
        $collection->addIsOnlineToFilter();

        return $collection->getSize();
    }
}