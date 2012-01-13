<?php

class Wootook_Empire_Model_Planet_Builder
    extends Wootook_Empire_Model_BuilderAbstract
{
    public function init()
    {
        $this->_unserializeQueue($this->_currentPlanet->getData('b_building_id'));
    }

    /**
     * @param array $params
     */
    protected function _initItem(Array $params)
    {
        if (!isset($params['building_id']) || !isset($params['level'])) {
            return null;
        }

        $buildingId = $params['building_id'];
        $level = $params['level'];

        if (!isset($params['created_at'])) {
            $createdAt = time();
        } else {
            $createdAt = $params['created_at'];
        }

        if (!isset($params['started_at'])) {
            $startedAt = null;
        } else {
            $startedAt = $params['started_at'];
        }

        if (!isset($params['updated_at'])) {
            $updatedAt = $createdAt;
        } else {
            $updatedAt = $params['updated_at'];
        }

        return new Wootook_Empire_Model_Planet_Builder_Item(array(
            'building_id' => $buildingId,
            'level'       => $level,
            'created_at'  => $createdAt,
            'started_at'  => $startedAt,
            'updated_at'  => $updatedAt
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
        $types = Wootook_Empire_Model_Game_Types::getSingleton();

        if (!$types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return false;
        }

        return true;
    }

    public function getBuildingTime($buildingId, $level)
    {
        $prices = Wootook_Empire_Model_Game_Prices::getSingleton();

        Math::setPrecision(50);
        $firstLevelTime = $prices[$buildingId][Legacies_Empire::BASE_BUILDING_TIME];
        $partialLevelTime = Math::mul($firstLevelTime, Math::pow($prices[$buildingId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
        $levelTime = Math::sub($partialLevelTime, $firstLevelTime);

        $speedFactor = Wootook::getGameConfig('game/speed/general');
        $baseTime = $levelTime / $speedFactor * 3600;

        Math::setPrecision();

        $event = Wootook::dispatchEvent('planet.building.building-time', array(
            'time'        => $baseTime,
            'base_time'   => $baseTime,
            'planet'      => $this->_currentPlanet,
            'user'        => $this->_currentUser,
            'building_id' => $buildingId,
            'level'       => $level
            ));

        return $event->getData('time');
    }

    public function getResourcesNeeded($buildingId, $level)
    {
        $prices = Wootook_Empire_Model_Game_Prices::getSingleton();
        $resources = Wootook_Empire_Model_Game_Resources::getSingleton();

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
                $resourcesNeeded[$resourceId] = Math::mul($firstLevelCost, Math::pow($prices[$buildingId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
            }
        }

        return $resourcesNeeded;
    }

    /**
     * Update the contruction queue.
     *
     * @return Wootook_Empire_Model_Planet_Building_Shipyard
     */
    public function updateQueue($time)
    {
        $fields = Wootook_Empire_Model_Game_FieldsAlias::getSingleton();

        $startingTime = $this->_currentPlanet->getData('b_building');

        foreach ($this->getQueue() as $element) {
            $elementTime = $element->getData('started_at');

            if ($elementTime === null) {
                $element->setData('started_at', $startingTime);
                $elementTime = $startingTime;
            }

            $level = $element->getData('level');
            $buildingId = $element->getData('building_id');
            $buildTime = $this->getBuildingTime($buildingId, $level);
            $elapsedTime = $time - $elementTime;

            if ($elapsedTime >= $buildTime) {
                $this->_currentPlanet->updateResources($time - $elapsedTime);
                $this->_currentPlanet->setElement($buildingId, $level);
                $this->_currentPlanet->updateResourceProduction($time - $elapsedTime);
                $this->_currentPlanet->updateStorages($time - $elapsedTime);
                $this->_currentPlanet->updateBuildingFields();

                $this->dequeue($element);

                Wootook::dispatchEvent('planet.building.level-update', array(
                    'time'        => $time - $elapsedTime,
                    'planet'      => $this->_currentPlanet,
                    'user'        => $this->_currentUser,
                    'building_id' => $buildingId,
                    'level'       => $level
                    ));

                $startingTime = $elementTime + $buildTime;
                continue;
            }

            $element->setData('updated_at', $time);
            break;
        }

        $this->_currentPlanet->setData('b_building_id', $this->serialize());
        $this->_currentPlanet->setData('b_building', $startingTime);

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $buildingId
     * @param int|string $level
     * @return Wootook_Empire_Model_Planet_Building_Shipyard
     */
    public function appendQueue($buildingId, $level, $time)
    {
        $types = Wootook_Empire_Model_Game_Types::getSingleton();
        $resources = Wootook_Empire_Model_Game_Resources::getSingleton();

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

        if ($this->count() == 0) {
            $this->_currentPlanet->setData('b_building', $time);
        }

        $this->enqueue(array(
            'building_id' => $buildingId,
            'level'       => $level,
            'created_at'  => $time
            ));
        $this->_currentPlanet->setData('b_building_id', $this->serialize());

        foreach ($remainingAmounts as $resourceId => $resourceAmount) {
            $this->_currentPlanet[$resourceId] = $resourceAmount;
        }

        return $this;
    }

    /**
     * Dequeues the first item to build to the construction list and removes all
     * its successors of the same type.
     *
     * @return Wootook_Empire_Model_Planet_Builder
     */
    public function dequeueFirstItem()
    {
        $this->rewind();
        $item = $this->current();

        return $this->dequeueItem($item->getIndex());
    }

    /**
     * Dequeues an item to build to the construction list and removes all its
     * successors of the same type.
     *
     * @param string $itemId
     * @return Wootook_Empire_Model_Planet_Builder
     */
    public function dequeueItem($itemId)
    {
        $item = $this->getItem($itemId);
        if (!$item) {
            return $this;
        }
        $buildingId = $item->getData('building_id');

        $keys = array_keys($this->_queue);
        $size = count($keys);
        $start = array_search($item->getIndex(), $keys);
        for ($i = $start; $i < $size; $i++) {
            $index = $keys[$i];
            if ($this->_queue[$index]->getData('building_id') != $buildingId) {
                continue;
            }

            $resourcesNeeded = $this->getResourcesNeeded($buildingId, $this->_queue[$index]->getData('level'));
            $reclaimedAmounts = $this->_calculateResourceReclaimedAmounts($resourcesNeeded);

            $this->dequeue($this->_queue[$index]);

            foreach ($reclaimedAmounts as $resourceId => $resourceAmount) {
                $this->_currentPlanet[$resourceId] = $resourceAmount;
            }
        }

        $this->_currentPlanet->setData('b_building_id', $this->serialize());

        return $this;
    }
}