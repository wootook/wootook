<?php

abstract class Wootook_Empire_Block_Planet_Builder_ItemAbstract
    extends Wootook_Core_Block_Template
{
    protected $_player   = null;
    protected $_planet = null;
    protected $_itemId = null;

    protected $_labels = null;

    public function __construct()
    {
        $this->_labels = Wootook_Empire_Helper_Config_Labels::getSingleton();
    }

    public function setPlayer(Wootook_Player_Model_Entity $player)
    {
        $this->_player = $player;

        return $this;
    }

    public function getPlayer()
    {
        if ($this->_player === null) {
            $this->_player = Wootook_Player_Model_Session::getSingleton()->getPlayer();
        }

        return $this->_player;
    }

    public function setPlanet(Wootook_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = $this->getPlayer()->getCurrentPlanet();
        }

        return $this->_planet;
    }

    public function setItemId($itemId)
    {
        $this->_itemId = $itemId;

        return $this;
    }

    public function getItemId()
    {
        return $this->_itemId;
    }

    public function getItemInfoUrl()
    {
        return $this->getStaticUrl('infos.php', array('gid' => $this->getItemId()));
    }

    public function getLabel($type)
    {
        return $this->_labels[$this->getItemId()][$type];
    }

    public function getItemImageUrl()
    {
        return $this->getStaticUrl($this->getLabel('image'));
    }

    public function getName()
    {
        return $this->getLabel('name');
    }

    public function getDescription()
    {
        return $this->getLabel('description');
    }

    public function getResourceName($resourceId)
    {
        static $lang = null;
        if ($lang === null) {
            // FIXME: implement a cleaner way to get names
            $lang = includeLang('imperium');
        }
        if ($resourceId == 'cristal') {
            $resourceId = 'crystal'; // FIXME: backward compatibility
        }

        if (isset($lang[$resourceId])) {
            return $this->__($lang[$resourceId]);
        }
        return '';
    }

    public function getNextLevel()
    {
        return $this->getQueuedLevel() + 1;
    }

    abstract public function getResourcesNeeded($level);

    abstract public function getBuildingTime($level);
}
