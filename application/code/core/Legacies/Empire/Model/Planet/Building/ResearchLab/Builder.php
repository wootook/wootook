<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.wootook.com/
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
    /**
     * @var int
     */
    protected $_maxLength = 0;

    public function init()
    {
        $this->_unserializeQueue($this->_currentUser->getData('b_laboratory_id'));
    }

    /**
     * @param int $buildingId
     * @param int $level
     * @param int $time
     */
    protected function _initItem(Array $params)
    {
        if (!isset($params['technology_id']) || !isset($params['level'])) {
            return null;
        }

        $technologyId = $params['technology_id'];
        $level = $params['level'];
        if (!isset($params['created_at'])) {
            $createdAt = time();
        } else {
            $createdAt = $params['created_at'];
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
            'updated_at'    => $updatedAt
            ));
    }

    /**
     * Check if a technology type is actually buildable on the current
     * planet, depending on the technology and buildings requirements.
     *
     * @param int $technologyId
     * @return bool
     */
    public function checkAvailability($technologyId)
    {
        $types = Wootook_Empire_Model_Game_Types::getSingleton();

        if (!$types->is($technologyId, Legacies_Empire::TYPE_RESEARCH)) {
            return false;
        }

        parent::checkAvailability($technologyId);

        return true;
    }

    /**
     * Returns the time needed to build $level of $technologyId
     *
     * @param int $technologyId
     * @param int $level
     */
    public function getBuildingTime($technologyId, $level)
    {
        $prices = Wootook_Empire_Model_Game_Prices::getSingleton();
        $types = Wootook_Empire_Model_Game_Types::getSingleton();

        Math::setPrecision(50);

         // FIXME: Resource dependency
        $totalCost = Math::mul(Math::add($prices[$technologyId][Legacies_Empire::RESOURCE_METAL], $prices[$technologyId][Legacies_Empire::RESOURCE_CRISTAL]), $level);
        $speedFactor = Wootook::getGameConfig('game/speed/general');

        // FIXME: Building & Technology dependency
        $extraLaboratoriesLevels = 0;
        $researchNetworkLevel = $this->_currentUser->getElement(Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK);
        if ($researchNetworkLevel > 0) {
            $laboratoriesLevels = array();
            foreach ($this->_currentUser->getPlanetCollection() as $planet) {
                if ($this->_currentPlanet->getId() == $planet->getId()) {
                    continue;
                }
                $level = $planet->getElement(Legacies_Empire::ID_BUILDING_RESEARCH_LAB);
                if ($level > 0) {
                    $laboratoriesLevels[] = (int) $level;
                }
            }
            sort($laboratoriesLevels, SORT_NUMERIC);
            $extraLaboratoriesLevels = array_sum(array_slice(array_reverse($laboratoriesLevels), 0, $researchNetworkLevel));
        }

        $laboratorySpeedup = Math::div($totalCost, ($this->_currentPlanet->getElement(Legacies_Empire::ID_BUILDING_RESEARCH_LAB) + 1 + $extraLaboratoriesLevels));

        Math::setPrecision();

        $baseTime = ($totalCost / $speedFactor) * $laboratorySpeedup;

        return (int) Math::floor($baseTime * 3600);
    }

    /**
     * (non-PHPdoc)
     * @see Legacies_Empire_Model_BuilderAbstract::getResourcesNeeded()
     */
    public function getResourcesNeeded($technologyId, $level)
    {
        $prices = Wootook_Empire_Model_Game_Prices::getSingleton();
        $resources = Wootook_Empire_Model_Game_Resources::getSingleton();

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
    public function updateQueue($time)
    {
        $fields = Wootook_Empire_Model_Game_FieldsAlias::getSingleton();

        $elapsedTime = $time - $this->_currentUser->getData('b_laboratory');

        foreach ($this->getQueue() as $element) {
            $technologyId = $element->getData('research_id');
            $level = $element->getData('level');
            $buildTime = $this->getBuildingTime($technologyId, $level);

            if ($elapsedTime >= $buildTime) {
                $this->_currentUser[$fields[$technologyId]] = Math::add($this->_currentUser[$fields[$technologyId]], $level);
                $elapsedTime -= $buildTime;
                $this->dequeue($element);
                continue;
            }

            $timeRatio = $elapsedTime / $buildTime;
            $itemsBuilt = Math::mul($timeRatio, $level);

            $element->setData('updated_at', $time);
            $element->setData('level', Math::sub($level, $itemsBuilt));
            $this->_currentUser->setData($fields[$technologyId], Math::add($this->_currentUser->getData($fields[$technologyId]), $itemsBuilt));
            break;
        }

        $this->_currentUser->setData('b_laboratory_id', $this->serialize());
        $this->_currentUser->setData('b_laboratory', $time);

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $technologyId
     * @param int|string $level
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function appendQueue($technologyId, $level, $time)
    {
        if ($this->_maxLength > 0 && $this->count() >= $this->_maxLength) {
            return $this;
        }

        if (!Math::isPositive($level)) {
            return $this;
        }

        $types = Wootook_Empire_Model_Game_Types::getSingleton();
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

        $this->enqueue($technologyId, $level, $time);
        $this->_currentUser->setData('b_laboratory_id', $this->serialize());

        foreach ($remainingAmounts as $resourceId => $resourceAmount) {
            $this->_currentPlanet[$resourceId] = $resourceAmount;
        }

        return $this;
    }
};