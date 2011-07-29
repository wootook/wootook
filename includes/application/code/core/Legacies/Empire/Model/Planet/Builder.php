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

        return new Legacies_Empire_Model_Planet_Builder_Item(array(
            'building_id' => $buildingId,
            'level'       => $level,
            'created_at'  => $time,
            'updated_at'  => $time
            ));
    }

    /**
     * Check if a building is actually buildable on the current planet,
     * depending on the technology and buildings requirements.
     *
     * @param int $buildingId
     * @return bool
     */
    public function checkAvailability($buildingId)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();

        if ($types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return true;
        }

        return $this->checkAvailability($buildingId);
    }

    public function getBuildingTime($buildingId, $level)
    {
        return 0;
    }

    public function getResourcesNeeded($buildingId, $level)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        if (!isset($prices[$buildingId])) {
            return array();
        }
        $resourcesNeeded = array();
        foreach ($resources as $resourceId => $resourceConfig) {
            if (!isset($prices[$buildingId][$resourceId])) {
                continue;
            }
            if (Math::isPositive($prices[$buildingId][$resourceId])) {
                $firstLevelCost = $prices[$buildingId][$resourceId];
                $partialLevelCost = Math::mul($firstLevelCost, Math::pow($prices[$buildingId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
                $resourcesNeeded[$resourceId] = Math::sub($partialLevelCost, $firstLevelCost);
            }
        }

        return $resourcesNeeded;
    }

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function updateQueue($time)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        $elapsedTime = $time - $this->_currentPlanet->getData('b_building');

        foreach ($this->getQueue() as $element) {
            $buildingId = $element->getData('building_id');
            $level = $element->getData('level');
            $buildTime = $this->getBuildingTime($buildingId, $level); // FIXME: consider total time, not only the construction time

            if ($elapsedTime >= $buildTime) {
                $this->_currentPlanet->updateResources($time - $elapsedTime);
                $this->_currentPlanet->setElement($buildingId, $level);
                $this->_currentPlanet->updateResourceProduction($time - $elapsedTime);
                $this->_currentPlanet->updateStorages($time - $elapsedTime);
                $this->_currentPlanet->updateBuildingFields();
                $this->dequeue($element);

                Legacies::dispatchEvent('planet.building.level-update', array(
                    'time'        => $time - $elapsedTime,
                    'planet'      => $this->_currentPlanet,
                    'user'        => $this->_currentUser,
                    'building_id' => $buildingId,
                    'level'       => $level
                    ));

                $elapsedTime -= $buildTime;
                continue;
            }
            $element->setData('updated_at', $time);
            break;
        }

        $this->_currentPlanet->setData('b_building_id', $this->serialize());
        $this->_currentPlanet->setData('b_building', $time);

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $buildingId
     * @param int|string $level
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function appendQueue($buildingId, $level, $time)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        if (!Math::isPositive($level)) {
            return $this;
        }

        if (!$types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return $this;
        }

        if (!$this->checkAvailability($buildingId)) {
            return $this;
        }

        $resourcesNeeded = $this->getResourcesNeeded($buildingId, $level);
        $remainingAmounts = $this->_calculateResourceRemainingAmounts($resourcesNeeded);
        if ($remainingAmounts === false) {
            return $this;
        }

        $this->enqueue($buildingId, $level, $time);
        $this->_currentPlanet->setData('b_building_id', $this->serialize());

        foreach ($remainingAmounts as $resourceId => $resourceAmount) {
            $this->_currentPlanet[$resourceId] = $resourceAmount;
        }

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $buildingId
     * @param int|string $level
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function dequeueFirstItem($time)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        if (!Math::isPositive($level)) {
            return $this;
        }

        // FIXME

        $this->_currentPlanet->setData('b_building_id', $this->serialize());

        return $this;
    }
}