<?php

class Legacies_Empire_Model_Planet_Builder
    extends Legacies_Empire_Model_BuilderAbstract
{
    public function init()
    {
        $this->_unserializeQueue($this->_currentPlanet->getData('b_building_id'));
    }

    /**
     * @param int $buildingId
     * @param int $level
     * @param int $time
     */
    public function _initItem()
    {
        $buildingId = func_get_arg(0);
        $level = func_get_arg(1);
        $time = func_get_arg(2);

        return new Legacies_Empire_Model_Planet_Building_Shipyard_Item(array(
            'building_id' => $buildingId,
            'level'       => $level,
            'created_at'  => $time,
            'updated_at'  => $time
            ));
    }

    public function getResourcesNeeded($typeId, $level)
    {
        return array();
    }

    public function getBuildingTime($typeId, $level)
    {
        return 0;
    }
}