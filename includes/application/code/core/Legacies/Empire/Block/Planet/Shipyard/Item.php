<?php

class Legacies_Empire_Block_Planet_Shipyard_Item
    extends Legacies_Core_Block_Template
{
    protected $_planet = null;
    protected $_shipyard = null;
    protected $_itemId = null;

    public function setPlanet(Legacies_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;
        $this->_shipyard = $this->_planet->getShipyard();

        return $this;
    }

    public function getPlanet()
    {
        return $this->_planet;
    }

    public function setItemId($shipId)
    {
        $this->_itemId = $shipId;

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

    public function getQty()
    {
        return $this->_planet->getElement($this->getItemId());
    }

    public function getResourcesNeeded()
    {
        return $this->_shipyard->getResourcesNeeded($this->getItemId(), 1);
    }

    public function getBuildingTime()
    {
        return $this->_shipyard->getBuildingTime($this->getItemId(), 1);
    }

    public function getMaximumBuildableElementsCount()
    {
        return $this->_shipyard->getMaximumBuildableElementsCount($this->getItemId());
    }
}