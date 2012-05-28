<?php

class Wootook_Empire_Block_Overview_Fleet_List_Item
    extends Wootook_Core_Block_Template
{
    protected $_fleet = null;
    protected $_currentPlayer = null;
    protected $_ownerCollection = null;

    /**
     * @return Wootook_Player_Model_Entity
     */
    public function getPlayer()
    {
        if ($this->_currentPlayer == null) {
            $this->_currentPlayer = Wootook_Player_Model_Session::getSingleton()->getPlayer();
        }
        return $this->_currentPlayer;
    }

    /**
     * @return Wootook_Player_Resource_Entity_Collection
     */
    public function getFleetOwnerCollection()
    {
        if ($this->_ownerCollection == null) {
            $this->_ownerCollection = $this->getFleetItem()->getOwnerCollection();
        }
        return $this->_ownerCollection;
    }

    /**
     * @return Wootook_Player_Resource_Entity_Collection
     */
    public function getFleetOwnerUsernames()
    {
        $usernameList = array();
        foreach ($this->getFleetOwnerCollection()->load() as $owner) {
            $usernameList[] = $owner->getUsername();
        }
        return $usernameList;
    }

    public function setFleetItem(Wootook_Empire_Model_Fleet $fleetItem)
    {
        $this->_fleet = $fleetItem;

        return $this;
    }

    /**
     * @return Wootook_Empire_Model_Fleet
     */
    public function getFleetItem()
    {
        return $this->_fleet;
    }

    public function getStartTime()
    {
        return $this->getFleetItem()->getStartTime();
    }

    public function getActionTime()
    {
        return $this->getFleetItem()->getActionTime();
    }

    public function getArrivalTime()
    {
        return $this->getFleetItem()->getArrivalTime();
    }

    public function isOwner()
    {
        return $this->getFleetItem()->isOwnedBy($this->getPlayer());
    }

    public function getRowClass()
    {
        return $this->getFleetItem()->getRowClass($this->getPlayer());
    }

    public function getOriginPlanet()
    {
        return $this->getFleetItem()->getOriginPlanet();
    }

    public function getOriginPlayer()
    {
        return $this->getOriginPlanet()->getPlayer();
    }

    public function getOriginPlayerName()
    {
        $player = $this->getOriginPlanet()->getPlayer();

        if ($player !== null) {
            return $player->getUsername();
        }
        return null;
    }

    public function getOriginPlanetName()
    {
        return $this->getOriginPlanetName();
    }

    public function getOriginPlanetCoords()
    {
        return $this->getOriginPlanetCoords();
    }

    public function getDestinationPlanet()
    {
        return $this->getFleetItem()->getDestinationPlanet();
    }

    public function getDestinationPlayer()
    {
        return $this->getDestinationPlanet()->getPlayer();
    }

    public function getDestinationPlayerName()
    {
        $player = $this->getDestinationPlanet()->getPlayer();

        if ($player !== null) {
            return $player->getUsername();
        }
        return null;
    }

    public function getDestinationPlanetName()
    {
        return $this->getDestinationPlanetName();
    }

    public function getDestinationPlanetCoords()
    {
        return $this->getDestinationPlanetCoords();
    }

    public function getMissionLabel()
    {
        return $this->getFleetItem()->getMissionLabel();
    }

    public function isMission($type)
    {
        return $this->getFleetItem()->isMission($type);
    }
}
