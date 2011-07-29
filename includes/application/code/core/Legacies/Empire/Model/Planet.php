<?php

/**
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 * @uses Legacies_Empire_User
 */
class Legacies_Empire_Model_Planet
    extends Legacies_Core_Entity
{
    const TYPE_PLANET = 1;
    const TYPE_DEBRIS = 2;
    const TYPE_MOON   = 3;

    protected $_eventPrefix = 'planet';
    protected $_eventObject = 'planet';

    /**
     * @var Legacies_Empire_Model_User
     */
    protected $_user = null;

    /**
     * @var Legacies_Empire_Model_Planet
     */
    protected $_moon = null;

    /**
     * @var Legacies_Empire_Model_Planet_Builder
     */
    protected $_builder = null;

    /**
     * @var Legacies_Empire_Model_Planet_Building_Shipyard
     */
    protected $_shipyard = null;

    /**
     * @var Legacies_Empire_Model_Planet_Building_ResearchLab
     */
    protected $_laboratory = null;

    /**
     * @var array
     */
    protected static $_instances = array();

    protected static $_productionInstances = array();

    public static function factory($id)
    {
        if ($id === null) {
            return new self();
        }

        $id = intval($id);
        if (!isset(self::$_instances[$id])) {
            $instance = new self();
            $params = func_get_args();
            call_user_func_array(array($instance, 'load'), $params);
            self::$_instances[$id] = $instance;
        }
        return self::$_instances[$id];
    }

    public function _init()
    {
        $this->_tableName = 'planets';
        $this->_idFieldName = 'id';
    }

    public function _afterLoad()
    {
        parent::_afterLoad();

        $this->getBuildingQueue()->init();

        return $this;
    }

    public function getLastUpdate()
    {
        return $this->getData('last_update');
    }

    public function setLastUpdate($time)
    {
        return $this->setData('last_update', $time);
    }

    public function getBuildingFields()
    {
        return $this->getData('field_max');
    }

    public function getUsedFields()
    {
        return $this->getData('field_current');
    }

    public function getGalaxyData()
    {
        $entity = new Legacies_Empire_Model_Galaxy_Position();
        $entity->load(array('id_planet' => $this->getId()));

        return $entity;
    }

    public function updateStorages($time = null)
    {
        Math::setPrecision(50);
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();
        foreach ($resources->getAllDatas() as $resource => $resourceData) {
            if (!isset($resourceData['storage_field']) || $resourceData['storage_field'] === null) {
                continue;
            }

            //$officerEnhancement = (.5 * $this->getUser()->getData('rpg_stockeur')) + 1;
            $officerEnhancement = 1;

            $storageEnhancementFactor = Math::floor(Math::pow(1.6, $this[Legacies_Empire::getFieldName($resourceData['storage'])]));
            $storageEnhancement = Math::mul(BASE_STORAGE_SIZE / 2, $storageEnhancementFactor);

            $value = Math::mul(MAX_OVERFLOW, Math::mul($officerEnhancement, Math::add(BASE_STORAGE_SIZE, $storageEnhancement)));
            $this->setData($resourceData['storage_field'], Math::floor($value));
        }
        Math::setPrecision();

        return $this;
    }

    public function updateBuildingFields()
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();

        if ($this->isPlanet()) {
            $filter = Legacies_Empire::TYPE_BUILDING_PLANET;
        } else if ($this->isMoon()) {
            $filter = Legacies_Empire::TYPE_BUILDING_MOON;
        } else {
            return $this;
        }

        $usedFields = 0;
        foreach ($types[$filter] as $buildingId) {
            $usedFields += $this->getElement($buildingId);
        }

        $this->setData('field_current', $usedFields);

        return $this;
    }

    public function updateResourceProduction($time = null)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();
        $production = Legacies_Empire_Model_Game_Production::getSingleton();

        if ($time === null) {
            $time = Legacies::now();
        }

        if (!$this->isPlanet()) {
            return $this;
        }

        /*
         * Compute resources consumers and resources producers
         */
        $resourcesTotals = array();
        $resourcesProductionTotals = array();
        $resourcesConsumptionTotals = array();
        $resourcesProducers = array();
        $resourcesConsumers = array();
        $productionRatios = array();
        foreach ($resources->getAllDatas() as $resource => $resourceData) {
            foreach ($resourceData['production'] as $productionUnit => $ratioField) {
                if (!$types->is($productionUnit, Legacies_Empire::TYPE_PRODUCTION)) {
                    continue;
                }

                $level = $this->getData(Legacies_Empire::getFieldName($productionUnit));
                $ratio = $this->getData($ratioField);
                $element = self::getProductionElementInstance($productionUnit);

                $productionRatios[$productionUnit] = $element->getProductionRatios($level, $ratio, $this, $this->getUser());
                foreach ($productionRatios[$productionUnit] as $resourceId => $resourceProduction) {
                    $comp = Math::comp($resourceProduction, 0);
                    if ($comp < 0) {
                        if (!isset($resourcesConsumers[$resourceId])) {
                            $resourcesConsumers[$resourceId] = array();
                        }
                        $resourcesConsumers[$resourceId][] = $productionUnit;

                        if (!isset($resourcesConsumptionTotals[$resourceId])) {
                            $resourcesConsumptionTotals[$resourceId] = 0;
                        }
                        $resourcesConsumptionTotals[$resourceId] = Math::add($resourcesConsumptionTotals[$resourceId], $resourceProduction);

                        if (!isset($resourcesTotals[$resourceId])) {
                            $resourcesTotals[$resourceId] = 0;
                        }
                        $resourcesTotals[$resourceId] = Math::add($resourcesTotals[$resourceId], $resourceProduction);
                    } else if ($comp > 0) {
                        if (!isset($resourcesProducers[$resourceId])) {
                            $resourcesProducers[$resourceId] = array();
                        }
                        $resourcesProducers[$resourceId][] = $productionUnit;

                        if (!isset($resourcesProductionTotals[$resourceId])) {
                            $resourcesProductionTotals[$resourceId] = 0;
                        }
                        $resourcesProductionTotals[$resourceId] = Math::add($resourcesProductionTotals[$resourceId], $resourceProduction);

                        if (!isset($resourcesTotals[$resourceId])) {
                            $resourcesTotals[$resourceId] = 0;
                        }
                        $resourcesTotals[$resourceId] = Math::add($resourcesTotals[$resourceId], $resourceProduction);
                    }
                }
            }
        }

        /*
         * Compute resource consumption
         */
        $actualProduction = $resourcesTotals;
        foreach ($resourcesTotals as $resource => $total) {
            if (Math::isPositiveOrZero($total)) {
                continue;
            }

            $productionRatio = 0;
            if (isset($resourcesProductionTotals[$resource]) && Math::isPositive($resourcesProductionTotals[$resource])) {
                $consumptions = Math::mul(-1, $resourcesConsumptionTotals[$resource]);
                Math::setPrecision(50);
                $productionRatio = Math::div($resourcesProductionTotals[$resource], $consumptions);
                Math::setPrecision();
            }

            foreach ($resourcesConsumers[$resource] as $consumer) {
                foreach ($productionRatios[$consumer] as $resourceId => $ratio) {
                    Math::setPrecision(50);
                    $productionOverhead = Math::mul(Math::sub(1, $productionRatio), $ratio);
                    Math::setPrecision();

                    $actualProduction[$resourceId] = Math::sub($actualProduction[$resourceId], $productionOverhead);
                }
            }
        }

        // Dispatch 'planet.update-production.before' event
        Legacies::dispatchEvent($this->_eventPrefix . '.update-production.before', array(
            $this->_eventObject => $this,
            'user'              => $this->getUser(),
            'productions'       => $actualProduction
            ));

        foreach ($actualProduction as $resource => $production) {
            if ($resources[$resource]['storage_field']) {
                $this->setData($resources[$resource]['production_field'], $production);
            } else if (isset($resourcesProductionTotals[$resource])) {
                $this->setData($resources[$resource]['field'], $production);
                $this->setData($resources[$resource]['production_field'], $resourcesProductionTotals[$resource]);
            }
        }

        // Dispatch 'planet.update-production.after' event
        Legacies::dispatchEvent($this->_eventPrefix . '.update-production.after', array(
            $this->_eventObject => $this,
            'user'              => $this->getUser()
            ));

        return $this;
    }

    public function updateResources($time = null)
    {
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        if ($time === null) {
            $time = Legacies::now();
        }

        $timeDiff = ($time - $this->getLastUpdate()) / 3600;
        foreach ($resources->getAllDatas() as $resourceId => $resourceConfig) {
            if (!$resourceConfig['storage_field']) {
                continue;
            }

            $production = Math::mul($this->getData($resourceConfig['production_field']), $timeDiff);
            $resourceValue = Math::add($this->getData($resourceConfig['field']), $production);
            $this->setData($resourceConfig['field'], Math::min($resourceValue, $this->getData($resourceConfig['storage_field'])));
        }
        $this->setLastUpdate($time);

        return $this;
    }

    public static function getProductionElementInstance($buildingId)
    {
        $production = Legacies_Empire_Model_Game_Production::getSingleton();

        if (!isset(self::$_productionInstances[$buildingId])) {
            if (!isset($production[$buildingId])) {
                return null;
            }
            $class = $production[$buildingId][Legacies_Empire::RESOURCE_CLASS];
            $reflection = new ReflectionClass($class);
            self::$_productionInstances[$buildingId] = $reflection->newInstance();
        }

        return self::$_productionInstances[$buildingId];
    }

    public function getFleetCollection($time = null)
    {
        $firstCollection = new Legacies_Core_Collection(array('fleet' => 'fleets'));
        $firstCollection
            ->column('*')
            ->where('fleet.fleet_start_galaxy = :galaxy')
            ->where('fleet.fleet_start_system = :system')
            ->where('fleet.fleet_start_planet = :position')
            ->where('fleet.fleet_start_type   = :planet_type')
        ;
        $backCollection = new Legacies_Core_Collection(array('fleet' => 'fleets'));
        $backCollection
            ->column('*')
            ->where('fleet.fleet_end_galaxy = :galaxy')
            ->where('fleet.fleet_end_system = :system')
            ->where('fleet.fleet_end_planet = :position')
            ->where('fleet.fleet_end_type   = :planet_type')
        ;

        $options = array(
            'galaxy'      => $this->getSystem(),
            'system'      => $this->getSystem(),
            'position'    => $this->getPosition(),
            'planet_type' => $this->getType()
            );

        if ($time !== null) {
            $options['now'] = $time;
            $firstCollection->where('fleet_start_time <= :now');
            $backCollection->where('fleet_end_time <= :now');
        }

        $collection = new Legacies_Core_Collection();
        $collection
            ->setEntityClassName('Legacies_Empire_Model_Fleet')
            ->union($firstCollection)
            ->union($backCollection)
            ->load($options)
        ;

        return $collection;
    }

    public function getUserId()
    {
        return (int) $this->getData('id_owner');
    }

    public function getUser()
    {
        if ($this->_user === null && $this->getUserId()) {
            $this->_user = Legacies_Empire_Model_User::factory($this->getUserId());
        }
        return $this->_user;
    }

    public function setUser(Legacies_Empire_Model_User $user)
    {
        $this->_user = $user;

        return $this;
    }

    public function getShipyard()
    {
        if ($this->_shipyard === null) {
            $this->_shipyard = new Legacies_Empire_Model_Planet_Building_Shipyard($this, $this->getUser());
        }
        return $this->_shipyard;
    }

    public function setShipyard(Legacies_Empire_Model_Planet_Building_Shipyard $shipyard)
    {
        $this->_shipyard = $shipyard;

        return $this;
    }

    public function getResearchLab()
    {
        if ($this->_laboratory === null) {
            $this->_laboratory = new Legacies_Empire_Model_Planet_Building_ResearchLab($this, $this->getUser());
        }
        return $this->_laboratory;
    }

    public function setResearchLab(Legacies_Empire_Model_Planet_Building_ResearchLab $laboratory)
    {
        $this->_laboratory = $laboratory;

        return $this;
    }

    public function isPlanet()
    {
        return (bool) ($this->getData('planet_type') == self::TYPE_PLANET);
    }

    public function isDebris()
    {
        return (bool) ($this->getData('planet_type') == self::TYPE_DEBRIS);
    }

    public function isMoon()
    {
        return (bool) ($this->getData('planet_type') == self::TYPE_MOON);
    }

    public function getMoon()
    {
        static $statement = null;

        if ($this->isMoon()) {
            return null;
        }

        if ($this->_moon === null) {
            if ($statement === null) {
                $statement = new Legacies_Core_Collection(array('planet' => 'planets'), get_class($this));
                $statement
                    ->where('galaxy=:galaxy')
                    ->where('system=:system')
                    ->where('planet=:position')
                    ->where('planet_type=' . strval(self::TYPE_MOON))
                ;
            }
            $statement->load(array(
                'galaxy' => $this->getGalaxy(),
                'system' => $this->getSystem(),
                'position' => $this->getPosition()
                ));

            $this->_moon = $statement->current();
        }
        return $this->_moon;
    }

    public function setGalaxy($galaxy)
    {
        $this->setData('galaxy', $galaxy);

        return $this;
    }

    public function getGalaxy()
    {
        return (int) $this->getData('galaxy');
    }

    public function setSystem($system)
    {
        $this->setData('system', $system);

        return $this;
    }

    public function getSystem()
    {
        return (int) $this->getData('system');
    }

    public function setPosition($position)
    {
        $this->setData('planet', $position);

        return $this;
    }

    public function getPosition()
    {
        return (int) $this->getData('planet');
    }

    public function getCoords()
    {
        return sprintf('%d:%d:%d', $this->getGalaxy(), $this->getSystem(), $this->getPosition());
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function setName($name)
    {
        $this->setData('name', $name);

        return $this;
    }

    public function getImage()
    {
        return 'graphics/planeten/' . $this->getData('image') . '.jpg';
    }

    public function getSmallImage()
    {
        return 'graphics/planeten/small/s_' . $this->getData('image') . '.jpg';
    }

    public function setType($type)
    {
        $this->setData('planet_type', $type);

        return $this;
    }

    public function getType()
    {
        return (int) $this->getData('planet_type');
    }

    public function isDestroyed()
    {
        return (bool) $this->getData('destruyed');
    }

    public function destroy()
    {
        if ($this->isDebris()) {
            return $this;
        }

        if ($this->isDestroyed()) {
            throw new Legacies_Empire_Model_Planet_Exception(Legacies::__('Planet is already destroyed.'));
        }

        if ($this->getFleetCollection()->count() > 0) {
            throw new Legacies_Empire_Model_Planet_Exception(Legacies::__("Could not delete planet until fleets aten't retuned."));
        }

        $user = $this->getUser();
        if ($user->getHomePlanet()->getId() == $this->getId()) {
            throw new Legacies_Empire_Model_Planet_Exception(Legacies::__("You can't destroy your home planet."));
        }

        if ($user->getCurrentPlanet()->getId() == $this->getId()) {
            $homePlanet = $user->getHomePlanet();

            if ($homePlanet !== null) {
                $user->updateCurrentPlanet($homePlanet);
            }
        }

        if ($this->isPlanet() && ($moon = $this->getMoon())) {
            $moon->destroy();
        }

        $this
            ->setData('destruyed', true)
            ->setData('id_owner', 0)
            ->save();

        return $this;
    }

    public function getElement($elementId)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        return $this->getData($fields[$elementId]);
    }

    public function setElement($elementId, $level)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        return $this->setData($fields[$elementId], $level);
    }

    public function hasElement($elementId, $levelRequired = 0)
    {
        $fields = Legacies_Empire_Model_Game_FieldsAlias::getSingleton();

        return $this->hasData($fields[$elementId]) && Math::comp($this->getElement($elementId), $levelRequired) > 0;
    }

    public function appendBuildingQueue($buildingId, $destroy = false, $time = null)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $prices = Legacies_Empire_Model_Game_Prices::getSingleton();

        if ($time === null) {
            $time = Legacies::now();
        }

        if (!$types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return $this;
        }

        if ($destroy === false && !$this->checkAvailability($buildingId)) {
            return $this;
        }

        // Dispatch 'planet.building.append-queue.before' event
        Legacies::dispatchEvent($this->_eventPrefix . '.building.append-queue.before', array(
            'bulding_id'        => $buildingId,
            'level'             => $this->getElement($buildingId),
            $this->_eventObject => $this,
            'user'              => $this->getUser(),
            'destroy'           => $destroy
            ));

        if ($destroy === false) {
            $level = $this->getElement($buildingId) + 1;
        } else {
            $level = max($this->getElement($buildingId) - 1, 0);
        }

        $this->getBuildingQueue()->appendQueue($buildingId, $level, $time);

        // Dispatch 'planet.shipyard.append-queue.after' event
        Legacies::dispatchEvent($this->_eventPrefix . '.building.append-queue.after', array(
            'bulding_id'        => $buildingId,
            'level'             => $this->getElement($buildingId),
            $this->_eventObject => $this,
            'user'              => $this->getUser(),
            'destroy'           => $destroy
            ));

        return $this;
    }

    public function updateBuildingQueue($time = null)
    {
        if ($time === null) {
            $time = Legacies::now();
        }

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . '.update-queue.before', array(
            'time'              => &$time,
            $this->_eventObject => $this->_currentPlanet,
            'user'              => $this->_currentUser
            ));

        $this->getBuildingQueue()->updateQueue($time);

        // Dispatch event
        Legacies::dispatchEvent($this->_eventPrefix . '.update-queue.after', array(
            'time'              => $time,
            'shipyard'          => $this,
            $this->_eventObject => $this->_currentPlanet,
            'user'              => $this->_currentUser
            ));

        return $this;
    }

    /**
     *
     * @return Legacies_Empire_Model_Planet_Builder
     */
    public function getBuildingQueue()
    {
        if ($this->_builder === null) {
            $this->_builder = new Legacies_Empire_Model_Planet_Builder($this, $this->getUser());
        }
        return $this->_builder;
    }

    /**
     *
     * Enter description here ...
     * @return Legacies_Empire_Model_Planet
     */
    public function dequeueBuilding()
    {
        $this->getBuildingQueue()->rewind();
        $item = $this->getBuildingQueue()->current();

        $this->getBuildingQueue()->dequeueFirstItem(Legacies::now());

        return $this;
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
        $this->getBuildingQueue()->checkAvailability($buildingId);

        try {
            // Dispatch event. Throw an exception to break the avaliability.
            Legacies::dispatchEvent($this->_eventPrefix . '.check-availability', array(
                'ship_id'           => $buildingId,
                'shipyard'          => $this,
                $this->_eventObject => $this->_currentPlanet,
                'user'              => $this->_currentUser
                ));
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Get the resources needed to build a specific building
     *
     * @param int $buildingId
     * @return bool
     */
    public function getResourcesNeeded($buildingId, $level)
    {
        return $this->getBuildingQueue()->getResourcesNeeded($buildingId, $level);
    }

    /**
     * Get the time needed to build a specific building
     *
     * @param int $buildingId
     * @return bool
     */
    public function getBuildingTime($buildingId, $level)
    {
        return $this->getBuildingQueue()->getBuildingTime($buildingId, $level);
    }

    public static function registrationListener($eventData)
    {
        if (isset($eventData['user'])) {
            $user = $eventData['user'];
            $request = $eventData['request'];

            if ($user === null || !$user instanceof Legacies_Empire_Model_User || $user->getId()) {
                return;
            }
            if ($request === null || !$request instanceof Legacies_Core_Controller_Request) {
                return;
            }

            $collection = new Legacies_Core_Collection('planets');
            $collection
                ->column(array(
                    'galaxy' => 'planet.galaxy',
                    'system' => 'planet.system',
                    'count'  => 'COUNT(planet.id)'
                    ))
                ->group('planet.galaxy')
                ->group('planet.system')
                ->where('planet.planet_type=1')
                ->order('COUNT(planet.id)', 'ASC')
                ->order('RAND()', 'ASC')
                ->limit(1)
            ;

            $params = array();
            $galaxy = $request->getParam('system');
            if ($galaxy !== null) {
                $collection->where('planet.galaxy=:galaxy');
                $params['galaxy'] = $galaxy;

                $systems = explode(',', $request->getParam('system'));
                if (is_array($systems) && count($systems) == 2 && is_int($systems[0]) && is_int($systems[1])) {
                    $collection->where('planet.system IN(' . implode(', ', range($systems[0], $systems[1])) . ')');
                }
            }
            $collection->load($params);

            if ($collection->count() == 0) {
                throw new Exception('No planet to colonize there!'); // FIXME
            }

            $systemInfo = $collection->current();
            if ($systemInfo->getData('count') >= MAX_PLANET_IN_SYSTEM) {
                throw new Exception('No planet to colonize there!'); // FIXME
            }
            $system = $systemInfo->getData('system');
            $galaxy = $systemInfo->getData('galaxy');

            $collection = new Legacies_Core_Collection('planets');
            $collection
                ->column(array('position' => 'planet.position'))
                ->where('planet.planet_type=1')
                ->where('planet.planet_type=:system')
                ->load()
            ;
            $positions = range(1, MAX_PLANET_IN_SYSTEM);
            foreach ($collection as $planet) {
                $key = array_search($planet->getData('position'), $positions);
                if ($key !== false) {
                    unset($positions[$key]);
                }
            }
            $key = array_rand($positions, 1);
            $finalPosition = $positions[$key];

            $planet = new self();
            $planet
                ->setData('id_owner', $user->getId())
                ->setData('name', $request->getParam('planet'))
                ->setData('galaxy', $galaxy)
                ->setData('system', $system)
                ->setData('position', $finalPosition)
                ->setData('planet_type', 1)
            ;

            Legacies::dispatchEvent('planet.init', array(
                'planet' => $planet,
                'user'   => $user
                ));

            $planet
                ->setData('field_max', 163)
                ->setData('field_current', 0)
                ->save()
            ;

            $user
                ->setData('id_planet', $planet->getId())
                ->setData('current_planet', $planet->getId())
            ;
        }
    }

    public static function planetUpdateListener($eventData)
    {
        if (isset($eventData['planet'])) {
            $planet = $eventData['planet'];

            if (isset($eventData['time'])) {
                $time = $eventData['time'];
            } else {
                $time = time();
            }

            if ($planet === null || !$planet instanceof Legacies_Empire_Model_Planet) {
                return;
            }

            $planet->updateBuildingQueue($time);
            $planet->updateResources($time);
            $planet->getShipyard()->updateQueue($time);
            $planet->setLastUpdate($time);
        }
    }

    public function getResourceAmount($resourceType)
    {
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        $resourceConfig = $resources->getData($resourceType);
        if ($resourceConfig === null) {
            return null;
        }
        return $this->getData($resourceConfig['field']);
    }

    public function getResourceCap($resourceType)
    {
        $resources = Legacies_Empire_Model_Game_Resources::getSingleton();

        $resourceConfig = $resources->getData($resourceType);
        if ($resourceConfig === null) {
            return null;
        }

        if ($resourceConfig['storage_field'] === null) {
            return $this->getData($resourceConfig['production_field']);
        }
        return $this->getData($resourceConfig['storage_field']);
    }

    public function isResourceCapped($resourceType)
    {
        if (Math::comp($this->getResourceAmount($resourceType), $this->getResourceCap($resourceType)) >= 0) {
            return true;
        }
        return false;
    }
}