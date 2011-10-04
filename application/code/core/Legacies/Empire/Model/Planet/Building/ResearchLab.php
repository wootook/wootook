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
 * Research lab building, manages researches queue on each planet
 *
 * @access     public
 * @category   Empire
 * @category   Planet
 * @package    Legacies
 * @subpackage Legacies_Empire
 */
class Legacies_Empire_Model_Planet_Building_ResearchLab
    implements Wootook_Empire_Model_Planet_BuildingInterface
{
    private $_eventPrefix = 'planet.laboratory.';

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
     * Multiton instances
     * @var array
     */
    protected static $_instances = array();

    /**
     * Multiton factory. Retruns the planet's research lab instance or created it if
     * it doesn't yet exist.
     *
     * @param Legacies_Empire_Model_Planet $currentPlanet
     * @param Legacies_Empire_Model_User $currentUser
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
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
     * @see Legacies_Empire_Model_Planet_Building_ResearchLab::factory()
     *
     * @param Legacies_Empire_Model_Planet $currentPlanet
     * @param Legacies_Empire_Model_User $currentUser
     */
    public function __construct($currentPlanet, $currentUser)
    {
        $this->_currentPlanet = $currentPlanet;
        $this->_currentUser = $currentUser;

        $this->_builder = new Legacies_Empire_Model_Planet_Building_ResearchLab_Builder($currentPlanet, $currentUser);
    }

    /**
     * @deprecated
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function save()
    {
        $this->_currentPlanet->save();
        return $this;
    }

    /**
     * Append items to build to the construction list
     *
     * @param int $researchId
     * @param int|string $level
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function appendQueue($researchId, $destroy = false, $time = null)
    {
        if ($time === null) {
            $time = Wootook::now();
        }

        if ($destroy === false) {
            $level = $this->_currentUser->getElement($researchId) + 1;
        } else {
            $level = max($this->_currentUser->getElement($researchId) - 1, 0);
        }

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . 'append-queue.before', array(
            'research_id' => $researchId,
            'level'       => &$level,
            'time'        => &$time,
            'laboratory'  => $this,
            'planet'      => $this->_currentPlanet,
            'user'        => $this->_currentUser
            ));

        $this->_builder->appendQueue($researchId, $level, $time);

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . 'append-queue.after', array(
            'research_id' => $researchId,
            'level'       => $level,
            'time'        => $time,
            'laboratory'  => $this,
            'planet'      => $this->_currentPlanet,
            'user'        => $this->_currentUser
            ));

        return $this;
    }

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    public function updateQueue($time = null)
    {
        if ($time === null) {
            $time = Wootook::now();
        }

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . 'update-queue.before', array(
            'time'       => &$time,
            'laboratory' => $this,
            'planet'     => $this->_currentPlanet,
            'user'       => $this->_currentUser
            ));

        $this->_builder->updateQueue($time);

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . 'update-queue.after', array(
            'time'       => $time,
            'laboratory' => $this,
            'planet'     => $this->_currentPlanet,
            'user'       => $this->_currentUser
            ));

        return $this;
    }

    /**
     * Return the construction queue
     * @see Legacies_Empire_Model_Planet_Building_ResearchLab_Builder
     *
     * @return array
     */
    public function getBuilder()
    {
        return $this->_builder;
    }

    /**
     * Check if a ship or defense type is actually buildable on the current
     * planet, depending on the technology and buildings requirements.
     *
     * @param int $resourceId
     * @return bool
     */
    public function checkAvailability($researchId)
    {
        try {
            // Dispatch event
            Wootook::dispatchEvent($this->_eventPrefix . 'check-availability', array(
                'research_id'  => $researchId,
                'laboratory'   => $this,
                'planet'       => $this->_currentPlanet,
                'user'         => $this->_currentUser
                ));
        } catch (Legacies_Core_Event_Break $e) {
            return false;
        }

        return $this->_builder->checkAvailability($researchId);
    }

    public function getResourcesNeeded($researchId, $level)
    {
        return $this->_builder->getResourcesNeeded($researchId, $level);
    }

    public function getResearchTime($researchId, $level)
    {
        $this->_builder->getBuildingTime($researchId, $level);
    }

    public function getResearchLevelQueued($researchId)
    {
        $level = $this->_currentUser->getElement($researchId);
        foreach ($this->_builder as $item) {
            if ($item->getData('research_id') != $researchId) {
                continue;
            }

            $level = $item->getData('level');
        }
        return $level;
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

            $shipyard = $planet->getResearchLab();
            if ($shipyard !== null) {
                $shipyard->updateQueue();
            }
        }
    }
}