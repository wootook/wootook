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
class Legacies_Empire_Model_Planet_Building_Shipyard_Builder
    extends Wootook_Empire_Model_BuilderAbstract
{
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
        $this->_unserializeQueue($this->_currentPlanet->getData('b_hangar_id'));
    }

    /**
     * @param int $buildingId
     * @param int $qty
     * @param int $time
     */
    protected function _initItem(Array $params)
    {
        if (!isset($params['ship_id']) || !isset($params['qty'])) {
            return null;
        }

        $shipId = $params['ship_id'];
        $qty = $params['qty'];
        if (!isset($params['created_at'])) {
            $createdAt = new Wootook_Core_DateTime();
        } else {
            $createdAt = $params['created_at'];
        }
        if (!isset($params['updated_at'])) {
            $updatedAt = $createdAt;
        } else {
            $updatedAt = $params['updated_at'];
        }

        return new Legacies_Empire_Model_Planet_Building_Shipyard_Item(array(
            'ship_id'    => $shipId,
            'qty'        => $qty,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
            ));
    }

    public function getSpeedEnhancement()
    {
        if ($this->_speedEnhancement === null) {
            $event = Wootook::dispatchEvent('planet.shipyard.speed-enhancement', array(
                'player'      => $this->_currentPlanet,
                'planet'      => $this->_currentPlanet,
                'enhancement' => 1
                ));

            $this->_speedEnhancement = $event->getData('enhancement');
        }

        return $this->_speedEnhancement;
    }

    /**
     * Check if a ship or defense type is actually buildable on the current
     * planet, depending on the technology and buildings requirements.
     *
     * @param int $shipId
     * @return bool
     */
    public function checkAvailability($shipId)
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

        if (!$types->is($shipId, Legacies_Empire::TYPE_SHIP) && !$types->is($shipId, Legacies_Empire::TYPE_DEFENSE)) {
            return false;
        }

        return parent::checkAvailability($shipId);
    }

    /**
     * Returns the maximum quantity of elements that are possible to build on
     * the current planet.
     *
     * @param int $shipId
     * @return int|string
     */
    public function getMaximumBuildableElementsCount($shipId)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

        $qty = 0;
        foreach ($resources as $resourceId => $_) {
            if (isset($prices[$shipId]) && isset($prices[$shipId][$resourceId]) && Math::comp($prices[$shipId][$resourceId], 0) > 0) {
                $maxQty = Math::floor(Math::div($this->_currentPlanet->getData($resourceId), $prices[$shipId][$resourceId]));

                if ($maxQty == 0) {
                    return 0;
                }

                if ($qty == 0 || Math::comp($maxQty, $qty) < 0) {
                    $qty = $maxQty;
                }
            }
        }

        if ($qty == 0) {
            return 0;
        }

        $limitedElementsQty = array(
            Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME      => array(
                'current'   => $this->_currentPlanet[$fields[Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME]],
                'requested' => $this->_currentPlanet[$fields[Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME]],
                'limit'     => 1
                ),
            Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME      => array(
                'current'   => $this->_currentPlanet[$fields[Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME]],
                'requested' => $this->_currentPlanet[$fields[Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME]],
                'limit'     => 1
                ),
            Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE  => array(
                'current'   => $this->_currentPlanet[$fields[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE]],
                'requested' => $this->_currentPlanet[$fields[Legacies_Empire::ID_SPECIAL_ANTIBALLISTIC_MISSILE]],
                'limit'     => $this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_MISSILE_SILO]] * 10
                ),
            Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE => array(
                'current'   => $this->_currentPlanet[$fields[Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE]],
                'requested' => $this->_currentPlanet[$fields[Legacies_Empire::ID_SPECIAL_INTERPLANETARY_MISSILE]],
                'limit'     => $this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_MISSILE_SILO]] * 5
                )
            );

        if (in_array($shipId, array_keys($limitedElementsQty))) {
            foreach ($this->getQueue() as $element) {
                if ($element['ship_id'] != $shipId) {
                    continue;
                }

                $limitedElementsQty[$shipId]['requested'] = Math::add($limitedElementsQty[$shipId]['requested'], $element['qty']);
                if (Math::comp($limitedElementsQty[$shipId]['requested'], $limitedElementsQty[$shipId]['limit']) >= 0) {
                    return 0;
                }
            }
            if (Math::comp($limitedElementsQty[$shipId]['current'], $limitedElementsQty[$shipId]['limit']) >= 0) {
                return 0;
            }
            if (Math::comp($qty, $limitedElementsQty[$shipId]['limit']) >= 0) {
                return $limitedElementsQty[$shipId]['limit'];
            }
        }

        return $qty;
    }

    /**
     * Returns the time needed to build $qty of $shipId
     *
     * @param int $shipId
     * @param int $qty
     */
    public function getBuildingTime($shipId, $qty)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        Math::setPrecision(50);
        $buildingTime = Math::mul($prices[$shipId][Legacies_Empire::BASE_BUILDING_TIME], intval($qty));

        $speedFactor = Wootook::getGameConfig('game/speed/general') / 1000;
        $baseTime = Math::mul(Math::div($buildingTime, 5000), Math::mul($speedFactor, $this->getSpeedEnhancement()));
        Math::setPrecision();

        $event = Wootook::dispatchEvent('planet.shipyard.building-time', array(
            'time'        => $baseTime,
            'base_time'   => $baseTime,
            'planet'      => $this->_currentPlanet,
            'player'      => $this->_currentPlayer,
            'ship_id'     => $shipId,
            'qty'         => $qty
            ));

        return $event->getData('time');
    }

    /**
     * (non-PHPdoc)
     * @see Legacies_Empire_Model_BuilderAbstract::getResourcesNeeded()
     */
    public function getResourcesNeeded($shipId, $qty)
    {
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

        if (!isset($prices[$shipId])) {
            return array();
        }
        $resourcesNeeded = array();
        foreach ($resources as $resourceId => $resourceConfig) {
            if (!isset($prices[$shipId][$resourceId])) {
                continue;
            }
            if (Math::isPositive($prices[$shipId][$resourceId])) {
                $resourcesNeeded[$resourceId] = Math::mul($prices[$shipId][$resourceId], $qty);
            }
        }

        return $resourcesNeeded;
    }

    /**
     * Returns the quantity set in parameter or the maximum buildable elements
     * if the quantity requested exeeds this number.
     *
     * @param int $shipId
     * @param int|string $qty
     * @return int|stirng
     */
    protected function _checkMaximumQuantity($shipId, $qty)
    {
        return Math::min($qty, $this->getMaximumBuildableElementsCount($shipId));
    }

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function updateQueue(Wootook_Core_DateTime $time)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        $elapsedTime = $time->getTimestamp() - $this->_currentPlanet->getData('b_hangar')->getTimestamp();

        foreach ($this->getQueue() as $element) {
            $shipId = $element->getData('ship_id');
            $qty = $element->getData('qty');
            $buildTime = $this->getBuildingTime($shipId, $qty);

            if ($elapsedTime >= $buildTime) {
                $this->_currentPlanet[$fields[$shipId]] = Math::add($this->_currentPlanet[$fields[$shipId]], $qty);
                $elapsedTime -= $buildTime;
                $this->dequeue($element);
                continue;
            }

            $timeRatio = $elapsedTime / $buildTime;
            $itemsBuilt = Math::mul($timeRatio, $qty);

            $element->setData('updated_at', $time->getTimestamp());
            $element->setData('qty', Math::sub($qty, $itemsBuilt));
            $this->_currentPlanet->setData($fields[$shipId], Math::add($this->_currentPlanet->getData($fields[$shipId]), $itemsBuilt));
            break;
        }

        $this->_currentPlanet->setData('b_hangar_id', $this->serialize());
        $this->_currentPlanet->setData('b_hangar', $time);

        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $shipId
     * @param int|string $qty
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function appendQueue($shipId, $qty, Wootook_Core_DateTime $time)
    {
        if ($this->_maxLength > 0 && $this->count() >= $this->_maxLength) {
            return $this;
        }

        if (!Math::isPositive($qty)) {
            return $this;
        }

        $types = Wootook_Empire_Helper_Config_Types::getSingleton();
        if (!$types->is($shipId, Legacies_Empire::TYPE_SHIP) && !$types->is($shipId, Legacies_Empire::TYPE_DEFENSE)) {
            return $this;
        }

        if (!$this->checkAvailability($shipId)) {
            return $this;
        }

        if (MAX_FLEET_OR_DEFS_PER_ROW > 0) {
            $qty = Math::min($this->_checkMaximumQuantity($shipId, $qty), MAX_FLEET_OR_DEFS_PER_ROW);
        } else {
            $qty = $this->_checkMaximumQuantity($shipId, $qty);
        }

        if (!Math::isPositive($qty)) {
            return $this;
        }

        $resourcesNeeded = $this->getResourcesNeeded($shipId, $qty);
        $remainingAmounts = $this->_calculateResourceRemainingAmounts($resourcesNeeded);
        if ($remainingAmounts === false) {
            return $this;
        }

        $this->enqueue(array(
            'ship_id'    => $shipId,
            'qty'        => $qty,
            'created_at' => $time->getTimestamp(),
            'updated_at' => $time->getTimestamp()
            ));
        $this->_currentPlanet->setData('b_hangar_id', $this->serialize());

        foreach ($remainingAmounts as $resourceId => $resourceAmount) {
            $this->_currentPlanet[$resourceId] = $resourceAmount;
        }

        return $this;
    }
}
