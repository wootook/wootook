<?php

abstract class Wootook_Empire_Block_Planet_Builder_ItemAbstract
    extends Wootook_Core_Block_Template
{
    protected $_user   = null;
    protected $_planet = null;
    protected $_itemId = null;

    public function setUser(Wootook_Empire_Model_User $user)
    {
        $this->_user = $user;

        return $this;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Wootook_Empire_Model_User::getSingleton();
        }

        return $this->_user;
    }

    public function setPlanet(Wootook_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->_planet === null) {
            $this->_planet = $this->getUser()->getCurrentPlanet();
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
        return $this->getUrl('infos.php', array('gid' => $this->getItemId()));
    }

    public function getItemImageUrl()
    {
        // TODO : Upgrade theme
        return $this->getSkinUrl('graphics/gebaeude/' . $this->getItemId() . '.gif');
    }

    public function getName()
    {
        static $lang = null;
        if ($lang === null) {
            // FIXME: implement a cleaner way to get names
            $lang = includeLang('tech');
        }

        if (isset($lang['tech']) && isset($lang['tech'][$this->getItemId()])) {
            return $this->__($lang['tech'][$this->getItemId()]);
        }
        return '';
    }

    public function getDescription()
    {
        static $lang = null;
        if ($lang === null) {
            // FIXME: implement a cleaner way to get names
            $lang = includeLang('tech');
        }

        if (isset($lang['res']) && isset($lang['res']['descriptions']) && isset($lang['res']['descriptions'][$this->getItemId()])) {
            return $this->__($lang['res']['descriptions'][$this->getItemId()]);
        }
        return '';
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