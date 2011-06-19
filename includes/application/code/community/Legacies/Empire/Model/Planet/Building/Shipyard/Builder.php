<?php

class Legacies_Empire_Model_Planet_Building_Shipyard_Builder
    extends Legacies_Empire_Model_BuilderAbstract
{
    public function init()
    {
        $this->_unserializeQueue($this->_currentPlanet->getData('b_hangar_id'));
    }

    /**
     * @param int $buildingId
     * @param int $qty
     * @param int $time
     */
    public function _initItem()
    {
        $shipId = func_get_arg(0);
        $qty = func_get_arg(1);
        $time = func_get_arg(2);

        return new Legacies_Empire_Model_Planet_Building_Shipyard_Item(array(
            'ship_id'    => $shipId,
            'qty'        => $qty,
            'created_at' => $time,
            'updated_at' => $time
            ));
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

    /**
     * Returns the time needed to build $qty of $shipId
     *
     * @param int $shipId
     * @param int $qty
     */
    public function getBuildingTime($shipId, $qty)
    {
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $gameConfig = Legacies_Core_Model_Config::getSingleton();

        Math::setPrecision(50);

         // FIXME: Resource dependency
        $totalCost = Math::mul(Math::add($prices[$shipId][Legacies_Empire::RESOURCE_METAL], $prices[$shipId][Legacies_Empire::RESOURCE_CRISTAL]), $qty);
        $speedFactor = $gameConfig->getData('game_speed');

        // FIXME: Building dependency
        $shipyardSpeedup = Math::div(1, Math::add($this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_SHIPYARD]], 1));
        $naniteSpeedup = Math::pow(.5, $this->_currentPlanet[$fields[Legacies_Empire::ID_BUILDING_NANITE_FACTORY]]);
        $structuresSpeedup = Math::mul($shipyardSpeedup, $naniteSpeedup);

        // FIXME: officers
        $officerSpeedup = 1;
        if ($types->is($shipId, Legacies_Empire::TYPE_SHIP)) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_technocrate'] * .05);
        } else if ($types->is($shipId, Legacies_Empire::TYPE_SPECIAL)) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_technocrate'] * .05);
        } else if ($types->is($shipId, Legacies_Empire::TYPE_DEFENSE)) {
            $officerSpeedup = 1 - ($this->_currentUser['rpg_defenseur'] * .375);
        }

        Math::setPrecision();

        $baseTime = ($totalCost / $speedFactor) * $structuresSpeedup;

        return (int) Math::floor($baseTime * $officerSpeedup * 3600);
    }

    /**
     * (non-PHPdoc)
     * @see Legacies_Empire_Model_BuilderAbstract::getResourcesNeeded()
     */
    public function getResourcesNeeded($shipId, $qty)
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

    /**
     * Update the contruction queue.
     *
     * @return Legacies_Empire_Model_Planet_Building_Shipyard
     */
    public function updateQueue($time = null)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        if ($time === null) {
            $time = Legacies::now();
        }
        $elapsedTime = $time - $this->_currentPlanet->getLastUpdate();

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.update-queue.before', array(
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));


        foreach ($this as $element) {
            $shipId = $element->getData('ship_id');
            $qty = $element->getData('qty');
            $buildTime = $this->getBuildingTime($shipId, $qty);

            if ($elapsedTime >= $buildTime) {
                $this->_currentPlanet[$fields[$shipId]] = Math::add($this->_currentPlanet[$fields[$shipId]], $qty);
                $elapsedTime -= $buildTime;
                $this->dequeue($element->getIndex());
                continue;
            }

            $timeRatio = $elapsedTime / $buildTime;
            $itemsBuilt = Math::mul($timeRatio, $qty);

            $element->setData('updated_at', $time);
            $element->setData('qty', Math::sub($qty, $itemsBuilt));
            $this->_currentPlanet->setData($fields[$shipId], Math::add($this->_currentPlanet->getData($fields[$shipId]), $itemsBuilt));
            break;
        }

        $this->_currentPlanet->setData('b_hangar_id', $this->serialize());

        // Dispatch event
        Legacies::dispatchEvent('planet.shipyard.update-queue.after', array(
            'shipyard' => $this,
            'planet'   => $this->_currentPlanet,
            'user'     => $this->_currentUser
            ));

        return $this;
    }
};