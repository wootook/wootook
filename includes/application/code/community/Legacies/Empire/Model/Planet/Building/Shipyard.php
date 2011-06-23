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
    private $_eventPrefix = 'planet.shipyard.';

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
    protected $_builder = null;

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

        $this->_builder = new Legacies_Empire_Model_Planet_Building_Shipyard_Builder($currentPlanet, $currentUser);
    }

    /**
     * Returns the timestamp at the instance creation.
     *
     * @deprecated
     * @return int
     */
    protected function _now()
    {
        return Legacies::now();
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
    public function appendQueue($shipId, $qty, $time = null)
    {
        if ($time === null) {
            $time = Legacies::now();
        }

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . 'append-queue.before', array(
            'ship_id'  => $shipId,
            'qty'      => &$qty,
            'time'     => &$time,
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        $this->_builder->appendQueue($shipId, $qty, $time);

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . 'append-queue.before', array(
            'ship_id'  => $shipId,
            'qty'      => $qty,
            'time'     => $time,
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
        if ($time === null) {
            $time = Legacies::now();
        }

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . 'update-queue.before', array(
            'time'     => &$time,
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        $this->_builder->updateQueue($time);

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . 'update-queue.after', array(
            'time'     => $time,
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
        try {
            // Dispatch event
            Legacies::dispatchEvent($this->_eventPrefix . 'check-availability', array(
                'ship_id'  => $shipId,
                'shipyard' => $this,
                'planet'   => $this->_currentPlanet,
                'user'     => $this->_currentUser
                ));
        } catch (Legacies_Core_Event_Break $e) {
            return false;
        }

        return $this->_builder->checkAvailability($shipId);
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
        return $this->_builder->getMaximumBuildableElementsCount($shipId);
    }

    public function getResourcesNeeded($shipId, $qty)
    {
        return $this->_builder->getResourcesNeeded($shipId, $qty);
    }

    public function getBuildingTime($shipId, $qty)
    {
        $this->_builder->getBuildingTime($shipId, $qty);
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