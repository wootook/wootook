<?php

class Wootook_Empire_Resource_Planet_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    protected function _construct()
    {
        $this->_init(array('planet' => 'planets'), 'Wootook_Empire_Model_Planet');
    }

    public function addPlayerToFilter(Wootook_Player_Model_Entity $player)
    {
        $this->addFieldToFilter('id_owner', $player->getId());

        return $this;
    }

    public function addTypeToFilter($type)
    {
        $this->addFieldToFilter('planet_type', $type);

        return $this;
    }
}
