<?php

/**
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 * @uses Wootook_Player_Model_Entity
 */
class Wootook_Empire_Model_Planet
    extends Wootook_Core_Mvc_Model_Entity
{
    const TYPE_PLANET = 1;
    const TYPE_DEBRIS = 2;
    const TYPE_MOON   = 3;

    protected $_eventPrefix = 'planet';
    protected $_eventObject = 'planet';

    /**
     * @var Wootook_Player_Model_Entity
     */
    protected $_player = null;

    /**
     * @var Wootook_Empire_Model_Planet
     */
    protected $_moon = null;

    /**
     * @var Wootook_Empire_Model_Planet
     */
    protected $_planet = null;

    /**
     * @var Wootook_Empire_Model_Planet_Builder_Builder
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

    /** @var Wootook_Empire_Model_Planet_PluginLoader */
    protected $_pluginLoader = null;

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

    public static function factoryFromCoords($coords, $type = self::TYPE_PLANET)
    {
        if ($coords === null || empty($coords)) {
            return new self();
        }

        if (!in_array($type, array(self::TYPE_PLANET, self::TYPE_DEBRIS, self::TYPE_MOON))) {
            $type = self::TYPE_PLANET;
        }

        if (!is_array($coords)) {
            $coords = explode(':', $coords);

            if (count($coords) != 3) {
                return new self();
            }

            $coords = array(
                'galaxy'   => $coords[0],
                'system'   => $coords[1],
                'position' => $coords[2],
                'type'     => $type
                );
        }

        if (!isset($coords['type'])) {
            $coords['type'] = $type;
        }

        $adapter = Wootook_Core_Database_ConnectionManager::getSingleton()
            ->getConnection('core_read');
        $collection = new Wootook_Empire_Resource_Planet_Collection($adapter);
        $collection->addCoordsToFilter($coords['galaxy'], $coords['system'], $coords['position'], $coords['type'])
            ->setPage(1, 1)
            ->load();

        $planet = $collection->getFirstItem();
        if ($planet !== null) {
            return $planet;
        }

        return new self;
    }

    public function __construct(Array $data = array(), Wootook_Player_Model_Entity $player = null)
    {
        parent::__construct($data);

        if ($player !== null) {
            $this->setPlayer($player);
        }
    }

    public function _init()
    {
        $this->_tableName = 'planets';
        $this->_idFieldName = 'id';

        $this->getDataMapper()
            ->addRule('last_update', 'date-time')
            ->addRule('b_building', 'date-time')
            ->addRule('b_tech', 'date-time')
            ->addRule('b_hangar', 'date-time');
    }

    public function __call($method, $params)
    {
        $prefix = substr($method, 0, 3);
        $plugin = substr($method, 3);

        if ($this->_pluginLoader === null) {
            $this->_pluginLoader = new Wootook_Empire_Model_Planet_PluginLoader($this, $this->getPlayer());
        }

        switch ($prefix) {
        case 'get':
            return $this->_pluginLoader->getPlugin($plugin);
            break;

        case 'set':
            return $this->_pluginLoader->setPlugin($plugin, $params[0]);
            break;

        case 'uns':
            return $this->_pluginLoader->unsetPlugin($plugin);
            break;

        case 'has':
            return $this->_pluginLoader->hasPlugin($plugin);
            break;
        }

        throw new Wootook_Core_Exception_RuntimeException(Wootook::__('Undefined method %s::%s.', get_class($this), $method));
    }

    /**
     * @return Wootook_Core_DateTime
     */
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
        if ($this->isPlanet()) {
            $entity = new Wootook_Empire_Model_Galaxy_Position();
            $entity->load(array('id_planet' => $this->getId()));

            return $entity;
        }

        return $this->getPlanet()->getGalaxyData();
    }

    public function updateStorages()
    {
        Math::setPrecision(50);
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();
        foreach ($resources->getAllDatas() as $resource => $resourceData) {
            if (!isset($resourceData['storage']) || $resourceData['storage'] === null) {
                continue;
            }

            if ($this->getElement($resourceData['storage'])) {
                $storageEnhancementFactor = Math::floor(Math::pow(1.6, $this->getElement($resourceData['storage'])));
                $storageEnhancement = Math::mul(BASE_STORAGE_SIZE / 2, $storageEnhancementFactor);
            } else {
                $storageEnhancement = 0;
            }

            $storageSize = Math::add(BASE_STORAGE_SIZE, $storageEnhancement);

            $event = Wootook::dispatchEvent($this->_eventPrefix . 'update-storage.resource', array(
                'resource_id'     => $resource,
                'resource_data'   => $resourceData,
                'default_size'    => $storageSize,
                'calculated_size' => $storageSize
                ));

            $calculatedSize = $event->getData('calculated_size');

            $this->setData($resourceData['storage_field'], Math::floor($calculatedSize));
        }
        Math::setPrecision();

        return $this;
    }

    public function updateBuildingFields()
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();

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

    public function updateResourceProduction()
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();
        $production = Wootook_Empire_Helper_Config_Production::getSingleton();

        if (!$this->isPlanet() || !$this->getPlayer()) {
            return $this;
        }

        if ($this->getPlayer()->isVacation()) {
            $resourceConfig = Wootook::getGameConfig('resource/base-income');
            foreach ($resources->getAllDatas() as $resource => $resourceData) {
                $this->setData($resourceData['production_field'], $resourceConfig[$resource]);
            }
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

                $productionRatios[$productionUnit] = $element->getProductionRatios($level, $ratio, $this, $this->getPlayer());
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
        Wootook::dispatchEvent($this->_eventPrefix . '.update-production.before', array(
            $this->_eventObject => $this,
            'player'            => $this->getPlayer(),
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
        Wootook::dispatchEvent($this->_eventPrefix . '.update-production.after', array(
            $this->_eventObject => $this,
            'player'            => $this->getPlayer()
            ));

        return $this;
    }

    public function updateResources(Wootook_Core_DateTime $time = null)
    {
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }

        $timeDiff = $time->diff($this->getLastUpdate());

        foreach ($resources->getAllDatas() as $resourceId => $resourceConfig) {
            if (!$resourceConfig['storage_field']) {
                continue;
            }

            $production = Math::mul($this->getData($resourceConfig['production_field']), $timeDiff / 3600);
            $resourceValue = Math::add($this->getData($resourceConfig['field']), $production);
            $this->setData($resourceConfig['field'], Math::min($resourceValue, $this->getData($resourceConfig['storage_field'])));
        }
        $this->setLastUpdate($time);

        return $this;
    }

    public static function getProductionElementInstance($buildingId)
    {
        $production = Wootook_Empire_Helper_Config_Production::getSingleton();

        if (!isset(self::$_productionInstances[$buildingId])) {
            if (!isset($production[$buildingId])) {
                return null;
            }
            $class = $production[$buildingId];
            $reflection = new ReflectionClass($class);
            self::$_productionInstances[$buildingId] = $reflection->newInstance();
        }

        return self::$_productionInstances[$buildingId];
    }

    public function getFleetCollection($time = null)
    {
        $collection = new Wootook_Empire_Resource_Fleet_Collection($this->getReadConnection());
        $collection->addPlanetToFilter($this, $time);

        return $collection;
    }

    public function getPlayerId()
    {
        return (int) $this->getData('id_owner');
    }

    public function getPlayer()
    {
        if ($this->_player === null && $this->getPlayerId()) {
            $this->_player = new Wootook_Player_Model_Entity();
            $this->_player->load($this->getPlayerId());
        }
        return $this->_player;
    }

    public function setPlayer(Wootook_Player_Model_Entity $player)
    {
        $this->_player = $player;
        $this->setData('id_owner', $player->getId());

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
        if ($this->isMoon()) {
            return null;
        }

        if ($this->_moon === null) {
            $collection = new Wootook_Empire_Resource_Planet_Collection($this->getReadConnection());
            $collection->addFieldToFilter('galaxy', $this->getGalaxy())
                ->addFieldToFilter('system', $this->getGalaxy())
                ->addFieldToFilter('planet', $this->getPosition())
                ->addFieldToFilter('planet_type', self::TYPE_MOON)
                ->load()
            ;

            $this->_moon = $collection->getFirstItem();

            if ($this->_moon !== null && $this->_moon instanceof self) {
                $this->_moon->setPlanet($this);
            }
        }
        return $this->_moon;
    }

    public function setMoon(Wootook_Empire_Model_Planet $moon)
    {
        $this->_moon = $moon;

        return $this;
    }

    public function getPlanet()
    {
        if ($this->isPlanet()) {
            return null;
        }

        if ($this->_planet === null) {
            $this->_planet = Wootook_Empire_Model_Planet::factoryFromCoords(array(
                'galaxy' => $this->getGalaxy(),
                'system' => $this->getSystem(),
                'position' => $this->getPosition()
                ));

            if ($this->_planet !== null && $this->_planet instanceof self) {
                $this->_planet->setMoon($this);
            }
        }
        return $this->_planet;
    }

    public function setPlanet(Wootook_Empire_Model_Planet $planet)
    {
        $this->_planet = $planet;

        return $this;
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
        return (bool) ($this->getData('destruyed') > 0);
    }

    public function destroy()
    {
        if ($this->isDebris()) {
            return $this;
        }

        if ($this->isDestroyed()) {
            throw new Wootook_Empire_Exception_Planet(Wootook::__('Planet is already destroyed.'));
        }

        if ($this->getFleetCollection()->count() > 0) {
            throw new Wootook_Empire_Exception_Planet(Wootook::__("Could not delete planet until fleets aten't retuned."));
        }

        $player = $this->getPlayer();
        if ($player->getHomePlanet()->getId() == $this->getId()) {
            throw new Wootook_Empire_Exception_Planet(Wootook::__("You can't destroy your home planet."));
        }

        if ($player->getCurrentPlanet()->getId() == $this->getId()) {
            $homePlanet = $player->getHomePlanet();

            if ($homePlanet !== null) {
                $player->updateCurrentPlanet($homePlanet);
            }
        }

        if ($this->isPlanet() && ($moon = $this->getMoon())) {
            $moon->destroy();
        }

        $this
            ->setData('destruyed', time() + 172800)
            ->setData('id_owner', 0)
            ->save();

        return $this;
    }

    public function isErasable()
    {
        if ($this->isDestroyed() && $this->getData('destruyed') >= time()) {
            return true;
        }
        return false;
    }

    public function getElement($elementId)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        return $this->getData($fields[$elementId]);
    }

    public function setElement($elementId, $level)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        return $this->setData($fields[$elementId], $level);
    }

    public function hasElement($elementId, $levelRequired = 0)
    {
        $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        return $this->hasData($fields[$elementId]) && Math::comp($this->getElement($elementId), $levelRequired) > 0;
    }

    public function appendBuildingQueue($buildingId, $destroy = false, Wootook_Core_DateTime $time = null)
    {
        $types = Wootook_Empire_Helper_Config_Types::getSingleton();
        $prices = Wootook_Empire_Helper_Config_Prices::getSingleton();

        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }

        if (!$types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
            return $this;
        }

        if ($destroy === false && !$this->checkAvailability($buildingId)) {
            return $this;
        }

        // Dispatch 'planet.building.append-queue.before' event
        Wootook::dispatchEvent($this->_eventPrefix . '.building.append-queue.before', array(
            'bulding_id'        => $buildingId,
            'level'             => $this->getElement($buildingId),
            $this->_eventObject => $this,
            'player'            => $this->getPlayer(),
            'destroy'           => $destroy
            ));

        if ($destroy === false) {
            $level = $this->getBuildingLevelQueued($buildingId) + 1;
        } else {
            $level = max($this->getBuildingLevelQueued($buildingId) - 1, 0);
        }

        $this->getBuildingQueue()->appendQueue($buildingId, $level, $time);

        // Dispatch 'planet.shipyard.append-queue.after' event
        Wootook::dispatchEvent($this->_eventPrefix . '.building.append-queue.after', array(
            'bulding_id'        => $buildingId,
            'level'             => $this->getElement($buildingId),
            $this->_eventObject => $this,
            'player'            => $this->getPlayer(),
            'destroy'           => $destroy
            ));

        return $this;
    }

    public function updateBuildingQueue(Wootook_Core_DateTime $time = null)
    {
        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . '.update-queue.before', array(
            'time'              => &$time,
            $this->_eventObject => $this,
            'player'            => $this->getPlayer()
            ));

        $this->getBuildingQueue()->updateQueue($time);

        // Dispatch event
        Wootook::dispatchEvent($this->_eventPrefix . '.update-queue.after', array(
            'time'              => $time,
            $this->_eventObject => $this,
            'player'            => $this->getPlayer()
            ));

        return $this;
    }

    public function getBuildingLevelQueued($buildingId)
    {
        $level = $this->getElement($buildingId);
        foreach ($this->_builder as $item) {
            if ($item->getData('building_id') != $buildingId) {
                continue;
            }

            $level = $item->getData('level');
        }
        return $level;
    }

    /**
     *
     * @return Wootook_Empire_Model_Planet_Builder_Builder
     */
    public function getBuildingQueue()
    {
        if ($this->_builder === null && $this->getPlayer()) {
            $this->_builder = new Wootook_Empire_Model_Planet_Builder_Builder($this, $this->getPlayer());
        }
        return $this->_builder;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Empire_Model_Planet
     */
    public function dequeueBuilding()
    {
        $this->getBuildingQueue()->rewind();
        $item = $this->getBuildingQueue()->current();

        $this->getBuildingQueue()->dequeueFirstItem(new Wootook_Core_DateTime());

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Empire_Model_Planet
     */
    public function dequeueItem($itemId)
    {
        $this->getBuildingQueue()->dequeueItem($itemId);

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
        if (!$this->getBuildingQueue()->checkAvailability($buildingId)) {
            return false;
        }

        try {
            // Dispatch event. Throw an exception to break the avaliability.
            Wootook::dispatchEvent($this->_eventPrefix . '.check-availability', array(
                'ship_id'           => $buildingId,
                'shipyard'          => $this,
                $this->_eventObject => $this,
                'player'            => $this->getPlayer()
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

    public static function searchMostFreeSystems($galaxyList = null, $systemList = null)
    {
        $adapter = Wootook_Core_Database_ConnectionManager::getSingleton()
            ->getConnection('core_read');
        $select = $adapter->select(array('galaxy' => $adapter->getTable('galaxy')));
        $select
            ->column(array(
                'galaxy' => 'galaxy',
                'system' => 'system',
                'count'  => new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)')
                ))
            ->group('galaxy.galaxy')
            ->group('galaxy.system')
        ;

        if ($galaxyList === null && Wootook::getGameConfig('user/registration/galaxy_list')) {
            $galaxyList = explode(',', Wootook::getGameConfig('user/registration/galaxy_list'));
        }

        if ($galaxyList !== null) {
            array_walk($galaxyList, array(__CLASS__, '_cleanItemRanges'));
            $select->where('galaxy', array(array(Wootook_Core_Database_Sql_Select::OPERATOR_IN => $galaxyList)));
        }

        if ($systemList === null && Wootook::getGameConfig('user/registration/system_list')) {
            $systemList = explode(',', Wootook::getGameConfig('user/registration/system_list'));
        }

        if ($systemList !== null) {
            array_walk($systemList, array(__CLASS__, '_cleanItemRanges'));
            $select->where('system', array(array(Wootook_Core_Database_Sql_Select::OPERATOR_IN => $systemList)));
        }

        $orders = array(
            "COUNT(*) / {$adapter->quote(Wootook::getGameConfig('engine/universe/positions'))}",
            "1 + ABS(galaxy.galaxy - CEIL({$adapter->quote(Wootook::getGameConfig('engine/universe/galaxies'))} / 2))",
            "1 + 2 * ABS(galaxy.system - CEIL({$adapter->quote(Wootook::getGameConfig('engine/universe/systems'))} / 2))",
            "RAND() / 1000",
            );
        $select
            ->order(new Wootook_Core_Database_Sql_Placeholder_Expression('((' . implode(') * (', $orders) . '))'), 'ASC')
            //->order("ABS(galaxy.system - CEIL({$collection->quote(Wootook::getGameConfig('engine/universe/systems'))} / 2))", 'ASC')
            //->order("ABS(galaxy.galaxy - CEIL({$collection->quote(Wootook::getGameConfig('engine/universe/galaxies'))} / 2))", 'ASC')
            //->order("1.5 / COUNT(*)", 'ASC')
            ->order('RAND()', 'ASC');

        return $select;
    }

    private static function _cleanItemRanges(&$value, $index, $userdata = null)
    {
        return intval($value);
    }

    public static function planetChangeListener($eventData)
    {
        if (!isset($eventData['request']) || !$eventData['request'] instanceof Wootook_Core_Mvc_Controller_Request_Http) {
            return;
        }

        /** @var Wootook_Core_Mvc_Controller_Request_Http $request */
        $request = $eventData['request'];
        if (!($planetId = $request->getQuery('___planet'))) {
            return;
        }

        $session = Wootook_Player_Model_Session::getSingleton();
        if (!$session->isLoggedIn()) {
            return;
        }
        $planet = new Wootook_Empire_Model_Planet();
        $planet->load($planetId);
        if (!$planet->getId()) {
            return;
        }

        $player = $session->getPlayer();
        $player->setCurrentPlanet($planet);
    }

    public static function planetUpdateListener($eventData)
    {
        if (isset($eventData['planet'])) {
            $planet = $eventData['planet'];

            if (isset($eventData['time'])) {
                $time = $eventData['time'];
            } else {
                $time = new Wootook_Core_DateTime();
            }
            if (!$time instanceof Wootook_Core_DateTime) {
                $time = new Wootook_Core_DateTime($time);
            }

            if ($planet === null || !$planet instanceof Wootook_Empire_Model_Planet) {
                return;
            }

            $planet->updateBuildingQueue($time);
            $planet->updateResources($time);
            $planet->getShipyard()->updateQueue($time); // FIXME : update all plugin queues
            $planet->getResearchLab()->updateQueue($time); // FIXME : update all plugin queues
            $planet->setLastUpdate($time);

            $planet->getPlayer()->save();
        }
    }

    public function getResourceAmount($resourceType)
    {
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

        $resourceConfig = $resources->getData($resourceType);
        if ($resourceConfig === null) {
            return null;
        }
        return $this->getData($resourceConfig['field']);
    }

    public function getResourceCap($resourceType)
    {
        $resources = Wootook_Empire_Helper_Config_Resources::getSingleton();

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
