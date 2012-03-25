<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Wootook_Empire_Model_Planet_Builder_Builder
    extends Wootook_Empire_Model_BuilderAbstract
{
    const FIELD_SERIALIZED = 'b_building_id';

    /**
     * @var int
     */
    protected $_maxLength = 0;

    /**
     * @var float
     */
    protected $_speedEnhancement = null;

    public function init()
    {
        $this->_unserializeQueue($this->_currentPlanet->getData(self::FIELD_SERIALIZED));

        $this->_maxLength = Wootook::getGameConfig('engine/core/buildings_queue_size');
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
            $createdAt = new Wootook_Core_DateTime();
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

    public function getSpeedEnhancement()
    {
        if ($this->_speedEnhancement === null) {
            $event = Wootook::dispatchEvent('planet.building.speed-enhancement', array(
                'player'      => $this->_currentPlanet,
                'planet'      => $this->_currentPlanet,
                'enhancement' => 1
                ));

            $this->_speedEnhancement = $event->getData('enhancement');
        }

        return $this->_speedEnhancement;
    }

    /**
     * Check if a building is actually buildable on the current planet,
     * depending on the technology and buildings requirements.
     *
     * @param int|string $buildingId
     * @return bool
     */
    public function checkAvailability($buildingId)
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

        if (!$types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return false;
        }

        return parent::checkAvailability($buildingId);
    }

    /**
     * Returns the time needed to build $level of $buildingId
     *
     * @param int|string $buildingId
     * @param int $level
     */
    public function getBuildingTime($buildingId, $level)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        Math::setPrecision(50);
        $firstLevelTime = $prices[$buildingId][Legacies_Empire::BASE_BUILDING_TIME];
        $partialLevelTime = Math::mul($firstLevelTime, Math::pow($prices[$buildingId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
        $levelTime = Math::sub($partialLevelTime, $firstLevelTime);

        $speedFactor = Wootook::getGameConfig('game/speed/general');

        if ($speedFactor == null) {
            $speedFactor = 1;
        }
        $baseTime = Math::div($levelTime, 3600 / ($speedFactor * $this->getSpeedEnhancement()));

        Math::setPrecision();

        $event = Wootook::dispatchEvent('planet.building.building-time', array(
            'time'        => $baseTime,
            'base_time'   => $baseTime,
            'planet'      => $this->_currentPlanet,
            'player'      => $this->_currentPlayer,
            'building_id' => $buildingId,
            'level'       => $level
            ));

        return $event->getData('time');
    }

    /**
     * (non-PHPdoc)
     * @see Legacies_Empire_Model_BuilderAbstract::getResourcesNeeded()
     */
    public function getResourcesNeeded($buildingId, $level)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

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
     * @return Wootook_Empire_Model_Planet_Builder_Builder
     */
    public function updateQueue(Wootook_Core_DateTime $time)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        $startingTime = clone $this->_currentPlanet->getData('b_building');

        foreach ($this->getQueue() as $element) {
            $elementTime = $element->getData('started_at');

            if ($elementTime === null) {
                $element->setData('started_at', $startingTime->getTimestamp());
                $elementTime = $startingTime->getTimestamp();
            }

            $level = $element->getData('level');
            $buildingId = $element->getData('building_id');
            $buildTime = $this->getBuildingTime($buildingId, $level);
            $elapsedTime = $time->getTimestamp() - $elementTime;

            if ($elapsedTime >= $buildTime) {
                $this->dequeue($element);

                $currentTime = clone $startingTime;
                $currentTime->add($elapsedTime);

                $this->_currentPlanet->updateResources($currentTime);
                $this->_currentPlanet->setElement($buildingId, $level);
                $this->_currentPlanet->updateStorages($currentTime);
                $this->_currentPlanet->updateResourceProduction($currentTime);
                $this->_currentPlanet->updateBuildingFields();

                Wootook::dispatchEvent('planet.building.level-update', array(
                    'time'        => $currentTime,
                    'planet'      => $this->_currentPlanet,
                    'player'      => $this->_currentPlayer,
                    'building_id' => $buildingId,
                    'level'       => $level
                    ));

                $startingTime->set($elementTime + $buildTime);
                continue;
            }

            break;
        }

        $this->_currentPlanet->setData('b_building_id', $this->serialize());
        $this->_currentPlanet->setData('b_building', $startingTime->now());

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int|string $buildingId
     * @param int $level
     * @return Wootook_Empire_Model_Planet_Builder_Builder
     */
    public function appendQueue($buildingId, $level, Wootook_Core_DateTime $time)
    {
        if ($this->_maxLength > 0 && $this->count() >= $this->_maxLength) {
            return $this;
        }

        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

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
            'created_at'  => $time->getTimestamp()
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
     * @return Wootook_Empire_Model_Planet_Builder_Builder
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
     * @return Wootook_Empire_Model_Planet_Builder_Builder
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
