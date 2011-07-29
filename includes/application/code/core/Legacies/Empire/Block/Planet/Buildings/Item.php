<?php

class Legacies_Empire_Block_Planet_Buildings_Item
    extends Legacies_Core_Block_Template
{
    protected $_planet = null;
    protected $_itemId = null;

    public function setPlanet(Legacies_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
    }

    public function getPlanet()
    {
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
            $resourceId = 'crystal';
        }

        if (isset($lang[$resourceId])) {
            return $this->__($lang[$resourceId]);
        }
        return '';
    }

    public function getLevel()
    {
        return $this->_planet->getElement($this->getItemId());
    }

    public function getResourcesNeeded()
    {
        return $this->_planet->getResourcesNeeded($this->getItemId(), 1);
    }

    public function getItemTime()
    {
        return $this->_planet->getBuildingTime($this->getItemId(), 1);
    }
}