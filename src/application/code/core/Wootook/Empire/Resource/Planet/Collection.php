<?php

class Wootook_Empire_Resource_Planet_Collection
    extends Wootook_Core_Resource_EntityCollection
{
    protected function _construct()
    {
        $this->_init('planets', 'Wootook_Empire_Model_Planet');
    }

    public function addPlayerToFilter(Wootook_Player_Model_Entity $player)
    {
        $this->addFieldToFilter('id_owner', $player->getId());

        return $this;
    }

    public function addGalaxyToFilter($galaxy)
    {
        $this->addFieldToFilter('galaxy', $galaxy);

        return $this;
    }

    public function addSystemToFilter($system)
    {
        $this->addFieldToFilter('system', $system);

        return $this;
    }

    public function addPositionToFilter($position)
    {
        $this->addFieldToFilter('planet', $position);

        return $this;
    }

    public function addTypeToFilter($type)
    {
        $this->addFieldToFilter('planet_type', $type);

        return $this;
    }

    public function addCoordsToFilter($galaxy, $system = null, $position = null, $type = null)
    {
        $this->addGalaxyToFilter($galaxy);

        if ($system !== null) {
            $this->addSystemToFilter($system);

            if ($position !== null) {
                $this->addPositionToFilter($position);

                if ($type !== null) {
                    $this->addTypeToFilter($type);
                }
            }
        }

        return $this;
    }
}
