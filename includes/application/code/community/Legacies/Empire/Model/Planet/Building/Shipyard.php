<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Shipyard building, manages ship and defenses building queue on each planet
 *
 * @access     public
 * @category   Empire
 * @category   Planet
 * @package    Legacies
 * @subpackage Legacies_Empire
 */
class Legacies_Empire_Model_Planet_Building_Shipyard
    implements Legacies_Empire_Model_Planet_BuildingInterface
{
    /**
     * Planet instance
     * @var Legacies_Empire_Model_Planet
     */
    protected $_currentPlanet = null;

    /**
     * User instance
     * @var Legacies_Empire_Model_User
     */
    protected $_currentUser = null;

    /**
     * construction queue
     * @var array
     */
    protected $_queue = null;

    /**
     * Current timestamp
     *
     * @var int
     * @deprecated
     */
    private $_now = 0;

    /**
     * Resource list
     *
     * @var array
     */
    protected $_resourcesTypes = array(
        Legacies_Empire::RESOURCE_METAL,
        Legacies_Empire::RESOURCE_CRISTAL,
        Legacies_Empire::RESOURCE_DEUTERIUM,
        Legacies_Empire::RESOURCE_ENERGY
        );

    /**
     * Multiton instances
     * @var array
     */
    protected static $_instances = array();

    /**
     * Multiton factory. Retruns the planet's shipyard instance or created it if
     * it doesn't yet exist.
     *
     * @param Legacies_Empire_Model_Planet $currentPlanet
     * @param Legacies_Empire_Model_User $currentUser
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public static function factory($currentPlanet, $currentUser)
    {
        if ($currentPlanet->getId()) {
            return null;
        }

        if (!isset(self::$_instances[$currentPlanet->getId()])) {
            self::$_instances[$currentPlanet->getId()] = new self($currentPlanet, $currentUser);
        }
        return self::$_instances[$currentPlanet->getId()];
    }

    /**
     * Constructor. Used for specific usage, use the factory for standard usage.
     *
     * @see Legacies_Empire_Model_Planet_Building_Shipyard::factory()
     *
     * @param Legacies_Empire_Model_Planet $currentPlanet
     * @param Legacies_Empire_Model_User $currentUser
     */
    public function __construct($currentPlanet, $currentUser)
    {
        $this->_currentPlanet = $currentPlanet;
        $this->_currentUser = $currentUser;

        $this->_queue = new Legacies_Empire_Model_Planet_Building_Shipyard_Builder($currentPlanet, $currentUser);

        $this->_now = time();
    }

    /**
     * Returns the timestamp at the instance creation.
     *
     * @deprecated
     * @return int
     */
    protected function _now()
    {
        return $this->_now;
    }

    /**
     * @deprecated
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function save()
    {
        $this->_currentPlanet->save();
        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $shipId
     * @param int|string $qty
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function appendQueue($shipId, $qty)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();

        if (Math::comp($qty, 0) <= 0) {
            return $this;
        }

        if (!$types->is($shipId, Legacies_Empire::TYPE_SHIP) && !$types->is($shipId, Legacies_Empire::TYPE_DEFENSE)) {
            return $this;
        }

        if (!$this->checkAvailability($shipId)) {
            return $this;
        }

        $qty = $this->_checkMaximumQuantity($shipId, $qty);

        if (MAX_FLEET_OR_DEFS_PER_ROW > 0 && Math::comp($qty, MAX_FLEET_OR_DEFS_PER_ROW) > 0) {
            $qty = MAX_FLEET_OR_DEFS_PER_ROW;
        }

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.append-queue.before', array(
            'ship_id'  => $shipId,
            'qty'      => $qty,
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        $resourcesNeeded = $this->_getResourcesNeeded($shipId, $qty);
        $buildTime = $this->getBuildTime($shipId, $qty);

        $this->_queue->enqueue($shipId, $qty);

        foreach ($this->_resourcesTypes as $resourceType) {
            $this->_currentPlanet[$resourceType] = Math::sub($this->_currentPlanet[$resourceType], $resourcesNeeded[$resourceType]);
        }

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.append-queue.after', array(
            'ship_id'  => $shipId,
            'qty'      => $qty,
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        return $this;
    }

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function updateQueue($time = null)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        if ($time === null) {
            $time = $this->_now();
        }
        $elapsedTime = $time - $this->_currentPlanet['b_hangar'];

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.update-queue.before', array(
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        foreach ($this->_queue as $id => &$element) {
            $shipId = $element->getData('ship_id');
            $qty = $element->getData('qty');
            $buildTime = $this->getBuildTime($shipId, $qty);

            if ($elapsedTime >= $buildTime) {
                $this->_currentPlanet[$fields[$shipId]] = Math::add($this->_currentPlanet[$fields[$shipId]], $qty);
                $elapsedTime -= $buildTime;
                unset($this->_queue[$id]);
                continue;
            }

            $timeRatio = $elapsedTime / $buildTime;
            $itemsBuilt = Math::mul($timeRatio, $qty);

            $element->setData('updated_at', $time);
            $element->setData('qty', Math::sub($qty, $itemsBuilt));
            $this->_currentPlanet->setData($fields[$shipId], Math::add($this->_currentPlanet->getData($fields[$shipId]), $itemsBuilt));
            break;
        }
        unset($element);

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.update-queue.after', array(
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        return $this;
    }

    /**
     * Return the construction queue
     * @see Legacies_Empire_Model_Planet_Building_Shipyard_Item
     *
     * @return array
     */
    public function getQueue()
    {
        return $this->_queue;
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
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $requirements = Legacies_Empire_Model_Game_Requirements::getSingleton();

        if (!isset($requirements[$shipId]) || empty($requirements[$shipId])) {
            return true;
        }

        foreach ($requirements[$shipId] as $requirement => $level) {
            if ($types->is($requirement, Legacies_Empire::TYPE_BUILDING) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_RESEARCH) && $this->_currentUser->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_DEFENSE) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_SHIP) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            }
            return false;
        }

        try {
            // Dispatch event. Throw an exception to break the avaliability.
            Legacies::dispatchEvent('planet.shipyard.check-availability', array(
                'ship_id'  => $shipId,
                'shipyard' => $this,
                'planet'   => $this->_currentPlanet,
                'user'     => $this->_currentUser
                ));
        } catch (Exception $e) {
            return false;
        }

        return true;
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
        $max = $this->getMaximumBuildableElementsCount($shipId);

        if (Math::comp($qty, $max) > 0) {
            return $max;
        }

        return $qty;
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
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        $resources = array(
            Legacies_Empire::RESOURCE_METAL,
            Legacies_Empire::RESOURCE_CRISTAL,
            Legacies_Empire::RESOURCE_DEUTERIUM,
            Legacies_Empire::RESOURCE_ENERGY
            );

        $qty = 0;
        foreach ($resources as $resourceId) {
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
            foreach ($this->_queue as $element) {
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

    protected function _getResourcesNeeded($shipId, $qty)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        $resourcesNeeded = array();
        foreach ($this->_resourcesTypes as $resourceId) {
            if (isset($prices[$shipId]) && isset($prices[$shipId][$resourceId]) && $prices[$shipId][$resourceId] > 0) {
                $resourcesNeeded[$resourceId] = Math::mul($prices[$shipId][$resourceId], $qty);
            }
        }

        return $resourcesNeeded;
    }

    public function getBuildTime($shipId, $qty)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $gameConfig = Legacies_Core_Model_Config::getSingleton();

        $scale = 30;

        $totalCost = Math::mul(Math::add($prices[$shipId][Legacies_Empire::RESOURCE_METAL], $prices[$shipId][Legacies_Empire::RESOURCE_CRISTAL]), $qty, $scale);
        $speedFactor = $gameConfig->getData('game_speed');

        $shipyardSpeedup = Math::div(1, Math::add($this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_SHIPYARD]], 1, $scale), $scale);
        $naniteSpeedup = Math::pow(.5, $this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_NANITE_FACTORY]], $scale);
        $structuresSpeedup = Math::mul($shipyardSpeedup, $naniteSpeedup, $scale);

        $officerSpeedup = 1;
        if (in_array($shipId, $types[Legacies_Empire::TYPE_SHIP])) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_technocrate'] * .05);
        } else if (in_array($shipId, $types[Legacies_Empire::TYPE_SPECIAL])) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_technocrate'] * .05);
        } else if (in_array($shipId, $types[Legacies_Empire::TYPE_DEFENSE])) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_defenseur'] * .375);
        }

        $baseTime = ($totalCost / $speedFactor) * $structuresSpeedup;

        return $baseTime * $officerSpeedup * 3600;
    }

    public static function planetUpdateListener($eventData)
    {
        if (isset($eventData['planet'])) {
            $planet = $eventData['planet'];

            if ($planet === null || !$planet instanceof Legacies_Empire_Model_Planet || !$planet->getId()) {
                return;
            }

            $time = null;
            if (isset($eventData['time'])) {
                $time = $eventData['time'];
            }

            $shipyard = self::factory($planet, $planet->getUser());
            if ($shipyard !== null) {
                $shipyard->updateQueue();
            }
        }
    }
}