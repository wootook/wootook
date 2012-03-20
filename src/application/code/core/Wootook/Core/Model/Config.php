<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Core_Model_Config
    extends Wootook_Core_Mvc_Model_Entity_SubTable
{
    protected $_eventPrefix = 'core.config';
    protected $_eventObject = 'config';

    protected function _init()
    {
        $this->_tableName = 'core_config';
        $this->_idFieldNames = array('config_path', 'website_id', 'game_id');
    }

    public function setWebsiteId($websiteId)
    {
        return $this->setData('website_id', $websiteId);
    }

    public function getWebsiteId()
    {
        return $this->getData('website_id');
    }

    public function setGameId($gameId)
    {
        return $this->setData('game_id', $gameId);
    }

    public function getGameId()
    {
        return $this->getData('game_id');
    }

    public function setPath($path)
    {
        return $this->setData('config_path', $path);
    }

    public function getPath()
    {
        return $this->getData('config_path');
    }

    public function setValue($value)
    {
        return $this->setData('config_value', $value);
    }

    public function getValue()
    {
        return $this->getData('config_value');
    }
}
