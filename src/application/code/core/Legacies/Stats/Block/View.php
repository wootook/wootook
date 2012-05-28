<?php

class Legacies_Stats_Block_View
    extends Wootook_Core_Block_Template
{
    protected $_statData = array();

    public function getStatData($type)
    {
        if (empty($this->_statData)) {
            $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');

            $statement = $readAdapter->select()
                ->from($readAdapter->getTable('statpoints'))
                ->where('stat_type', $type)
                ->prepare()
            ;

            $statement->execute();
            $this->_statData = $statement->fetchAll();
        }
        return $this->_statData;
    }

    public function getPlayerStatData()
    {
        if (empty($this->_statData)) {
            $playerId = Wootook_Player_Model_Session::getSingleton()->getPlayerId();

            $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');

            $statement = $readAdapter->select()
                ->from($readAdapter->getTable('statpoints'))
                ->where('id_owner', $playerId)
                ->prepare()
            ;

            $statement->execute();
            $this->_statData = $statement->fetchAll();
        }
        return $this->_statData;
    }
}
