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
class Legacies_Empire_Model_Planet_Building_ResearchLab_Builder
    extends Wootook_Empire_Model_BuilderAbstract
{
    const FIELD_SERIALIZED = 'b_tech_id';
    const FIELD_DATETIME = 'b_tech';

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

        $this->_maxLength = Wootook::getGameConfig('engine/core/lab_queue_size');
    }

    /**
     * @param array $params
     */
    protected function _initItem(Array $params)
    {
        if (!isset($params['technology_id']) || !isset($params['level'])) {
            return null;
        }

        $technologyId = $params['technology_id'];
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

        return new Legacies_Empire_Model_Planet_Building_ResearchLab_Item(array(
            'technology_id' => $technologyId,
            'level'         => $level,
            'created_at'    => $createdAt,
            'started_at'    => $startedAt,
            'updated_at'    => $updatedAt
            ));
    }

    public function getSpeedEnhancement()
    {
        if ($this->_speedEnhancement === null) {
            $event = Wootook::dispatchEvent('planet.research-lab.technology.speed-enhancement', array(
                'player'      => $this->_currentPlanet,
                'planet'      => $this->_currentPlanet,
                'enhancement' => 1
                ));

            $this->_speedEnhancement = $event->getData('enhancement');
        }

        return $this->_speedEnhancement;
    }

    /**
     * Check if a technology type is actually buildable on the current
     * planet, depending on the technology and buildings requirements.
     *
     * @param int|string $technologyId
     * @return bool
     */
    public function checkAvailability($technologyId)
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

        if (!$types->is($technologyId, Legacies_Empire::TYPE_RESEARCH)) {
            return false;
        }

        return parent::checkAvailability($technologyId);
    }

    /**
     * Returns the time needed to build $level of $technologyId
     *
     * @param int|string $technologyId
     * @param int $level
     */
    public function getBuildingTime($technologyId, $level)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        Math::setPrecision(50);
        $firstLevelTime = $prices[$technologyId][Legacies_Empire::BASE_BUILDING_TIME];
        $partialLevelTime = Math::mul($firstLevelTime, Math::pow($prices[$technologyId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
        $levelTime = Math::sub($partialLevelTime, $firstLevelTime);

        $speedFactor = Wootook::getGameConfig('game/speed/general');
        if ($speedFactor == null) {
            $speedFactor = 1;
        }

        $baseTime = ($levelTime * 3600 / $speedFactor) / $this->getSpeedEnhancement();

        Math::setPrecision();

        $event = Wootook::dispatchEvent('planet.research-lab.technology.building-time', array(
            'time'          => $baseTime,
            'base_time'     => $baseTime,
            'planet'        => $this->_currentPlanet,
            'player'        => $this->_currentPlayer,
            'technology_id' => $technologyId,
            'level'         => $level
            ));

        return $event->getData('time');
    }

    /**
     * (non-PHPdoc)
     * @see Legacies_Empire_Model_BuilderAbstract::getResourcesNeeded()
     */
    public function getResourcesNeeded($technologyId, $level)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

        if (!isset($prices[$technologyId])) {
            return array();
        }
        $resourcesNeeded = array();
        foreach ($resources as $resourceId => $resourceConfig) {
            if (!isset($prices[$technologyId][$resourceId])) {
                continue;
            }
            if (Math::isPositive($prices[$technologyId][$resourceId])) {
                $firstLevelCost = $prices[$technologyId][$resourceId];
                $partialLevelCost = Math::mul($firstLevelCost, Math::pow($prices[$technologyId][Legacies_Empire::RESOURCE_MULTIPLIER], $level));
                $resourcesNeeded[$resourceId] = Math::sub($partialLevelCost, $firstLevelCost);
            }
        }

        return $resourcesNeeded;
    }

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function updateQueue(Wootook_Core_DateTime $time)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        $startingTime = clone $this->_currentPlanet->getData(self::FIELD_DATETIME);

        $elapsedTime = $time->getTimestamp() - $this->_currentPlanet->getData(self::FIELD_DATETIME)->getTimestamp();

        foreach ($this->getQueue() as $element) {
            $elementTime = $element->getData('started_at');

            if ($elementTime === null) {
                $element->setData('started_at', $startingTime->getTimestamp());
                $elementTime = $startingTime->getTimestamp();
            }

            $level = $element->getData('level');
            $technologyId = $element->getData('technology_id');
            $buildTime = $this->getBuildingTime($technologyId, $level);
            $elapsedTime = $time->getTimestamp() - $elementTime;

            if ($elapsedTime >= $buildTime) {
                $this->dequeue($element);

                $currentTime = clone $startingTime;
                $currentTime->add($elapsedTime);

                $this->_currentPlayer->setElement($technologyId, $level);
                $this->_currentPlanet->updateStorages($currentTime);
                $this->_currentPlanet->updateResourceProduction($currentTime);
                $this->_currentPlanet->updateBuildingFields();

                Wootook::dispatchEvent('planet.research-lab.technology.level-update', array(
                    'time'          => $currentTime,
                    'planet'        => $this->_currentPlanet,
                    'player'        => $this->_currentPlayer,
                    'technology_id' => $technologyId,
                    'level'         => $level
                    ));

                $startingTime->set($elementTime + $buildTime);
                continue;
            }

            break;
        }

        $this->_currentPlanet->setData(self::FIELD_SERIALIZED, $this->serialize());
        $this->_currentPlanet->setData(self::FIELD_DATETIME, $startingTime->now());

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int|string $technologyId
     * @param int $level
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function appendQueue($technologyId, $level, Wootook_Core_DateTime $time)
    {
        if ($this->_maxLength > 0 && $this->count() >= $this->_maxLength) {
            return $this;
        }

        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

        if (!Math::isPositive($level)) {
            return $this;
        }

        if (!$types->is($technologyId, Legacies_Empire::TYPE_RESEARCH)) {
            return $this;
        }

        if (!$this->checkAvailability($technologyId)) {
            return $this;
        }

        $resourcesNeeded = $this->getResourcesNeeded($technologyId, $level);
        $remainingAmounts = $this->_calculateResourceRemainingAmounts($resourcesNeeded);
        if ($remainingAmounts === false) {
            return $this;
        }

        if ($this->count() == 0) {
            $this->_currentPlanet->setData(self::FIELD_DATETIME, $time);
        }

        $this->enqueue(array(
            'technology_id' => $technologyId,
            'level'         => $level,
            'created_at'    => $time->getTimestamp()
            ));

        $this->_currentPlanet->setData(self::FIELD_SERIALIZED, $this->serialize());
        $this->_currentPlayer->setdata('b_tech_planet', $this->_currentPlanet->getId());

        foreach ($remainingAmounts as $resourceId => $resourceAmount) {
            $this->_currentPlanet[$resourceId] = $resourceAmount;
        }

        return $this;
    }

    /**
     * Dequeues the first item to build to the construction list and removes all
     * its successors of the same type.
     *
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
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
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function dequeueItem($itemId)
    {
        $item = $this->getItem($itemId);
        if (!$item) {
            return $this;
        }
        $technologyId = $item->getData('technology_id');

        $keys = array_keys($this->_queue);
        $size = count($keys);
        $start = array_search($item->getIndex(), $keys);
        for ($i = $start; $i < $size; $i++) {
            $index = $keys[$i];
            if ($this->_queue[$index]->getData('technology_id') != $technologyId) {
                continue;
            }

            $resourcesNeeded = $this->getResourcesNeeded($technologyId, $this->_queue[$index]->getData('level'));
            $reclaimedAmounts = $this->_calculateResourceReclaimedAmounts($resourcesNeeded);

            $this->dequeue($this->_queue[$index]);

            foreach ($reclaimedAmounts as $resourceId => $resourceAmount) {
                $this->_currentPlanet[$resourceId] = $resourceAmount;
            }
        }

        $this->_currentPlanet->setData(self::FIELD_SERIALIZED, $this->serialize());

        return $this;
    }
}
