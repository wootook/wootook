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

    protected $_user = null;
    protected $_moon = null;
    protected $_now = null;

    protected static $_instances = array();

    protected static $_productionConfig = array(
        Legacies_Empire::RESOURCE_METAL     => array(
            'field'            => Legacies_Empire::RESOURCE_METAL,
            'production_field' => 'metal_perhour',
            'ratio_field'      => 'metal_porcent',
            'storage_field'    => 'metal_max',
            'production'       => array(
                Legacies_Empire::ID_BUILDING_METAL_MINE => 'metal_mine_porcent'
                ),
            'storage'          => Legacies_Empire::ID_BUILDING_METAL_STORAGE
            ),
        Legacies_Empire::RESOURCE_CRISTAL   => array(
            'field'            => Legacies_Empire::RESOURCE_CRISTAL,
            'production_field' => 'crystal_perhour',
            'ratio_field'      => 'crystal_porcent',
            'storage_field'    => 'crystal_max',
            'production'       => array(
                Legacies_Empire::ID_BUILDING_CRISTAL_MINE => 'crystal_mine_porcent'
                ),
            'storage'          => Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE
            ),
        Legacies_Empire::RESOURCE_DEUTERIUM => array(
            'field'            => Legacies_Empire::RESOURCE_DEUTERIUM,
            'production_field' => 'deuterium_perhour',
            'ratio_field'      => 'deuterium_porcent',
            'storage_field'    => 'deuterium_max',
            'production'       => array(
                Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => 'deuterium_sintetizer_porcent'
                ),
            'storage'          => Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK
            ),
        Legacies_Empire::RESOURCE_ENERGY => array(
            'field'            => 'energy_used',
            'production_field' => 'energy_max',
            'storage_field'    => null,
            'production'       => array(
                Legacies_Empire::ID_BUILDING_SOLAR_PLANT    => 'solar_plant_porcent',
                Legacies_Empire::ID_BUILDING_FUSION_REACTOR => 'fusion_plant_porcent',
                Legacies_Empire::ID_SHIP_SOLAR_SATELLITE    => 'solar_satelit_porcent'
                ),
            'storage'          => null
            )
        );

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
        $this->_now = time();
        $this->_tableName = 'planets';
        $this->_idFieldName = 'id';
    }

    /**
     * @deprecated
     */
    protected function _now()
    {
        return $this->_now;
    }

    public function updateResources($time = null)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();

        if ($time === null) {
            $time = $this->_now();
        }

        if ($this->getData('planet_type') != 1) {
            return $this;
        }

        $resourcesProductions = array();
        foreach (self::$_productionConfig as $resource => $resourceData) {
            if ($resourceData['storage_field'] !== null && $resourceData['storage_field'] !== null) {
                $officerEnhancement = Math::add(Math::mul(.5, $this->getUser('rpg_stockeur')), 1);
                $storageCapacity = Math::pow(1.5, $this->getData(Legacies_Empire::getFieldName($resourceData['storage'])));

                $value = Math::mul(MAX_OVERFLOW, Math::mul($officerEnhancement, Math::add(BASE_STORAGE_SIZE, $storageCapacity)));
                $this->setData($resourceData['storage_field'], $value);
            }

            foreach ($resourceData[production] as $productionUnit => $ratioField) {
                if (!in_array($productionUnit, $types['prod'])) {
                    continue;
                }

                $level = $this->getData(Legacies_Empire::getFieldName($productionUnit));
                $ratio = $this->getData($ratioField);
                $element = self::getProducitonElementInstance($productionUnit);

                foreach ($element->getRatios($level, $ratio, $this, $this->getUser()) as $resourceId => $resourceProduction) {
                    if (!isset($resourcesProductions[$resourceId])) {
                        $resourcesProductions[$resourceId] = $resourceProduction;
                    } else {
                        $resourcesProductions[$resourceId] = Math::add($resourcesProductions[$resourceId], $resourceProduction);
                    }
                }
            }
        }

        $timeDiff = ($time - $this->getData('last_update')) / 3600;
        foreach ($resourcesProductions as $resourceId => $productionPerHour) {
            if (!isset(self::$_productionConfig[$resource])) {
                continue;
            }
            $this->setData(self::$_productionConfig[$resource]['production_field'], $productionPerHour);

            $production = Math::add($this->getData(self::$_productionConfig[$resource]['field']), Math::mul($timeDiff, $productionPerHour));
            if (Math::diff($production, $this->getData(self::$_productionConfig[$resource]['storage_field'])) > 0) {
                $production = $this->getData(self::$_productionConfig[$resource]['storage_field']);
            }
            $this->setData(self::$_productionConfig[$resource]['field'], $production);
        }

        return $this;
    }

    public static function getProducitonElementInstance($buildingId)
    {
        global $ProdGrid; // FIXME

        if (!isset(self::$_productionInstances[$buildingId])) {
            if (!isset($ProdGrid[$buildingId])) {
                return null;
            }
            $class = $ProdGrid[$buildingId][Legacies_Empire::RESOURCE_FORMULA];
            self::$_productionInstances[$buildingId] = new $class;
        }

        return self::$_productionInstances[$buildingId];
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Legacies_Empire_Model_User::factory($this->getData('id_owner'));
        }
        return $this->_user;
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

    public function setType($type)
    {
        $this->setData('planet_type', $type);

        return $this;
    }

    public function getType()
    {
        return (int) $this->getData('planet_type');
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

            $time = null;
            if (isset($eventData['time'])) {
                $time = $eventData['time'];
            }

            if ($planet === null || !$planet instanceof Legacies_Empire_Model_Planet || !$planet->getId()) {
                return;
            }

            $user = $planet->getUser();
            if (($queue = $this->getData('b_building_id')) != '') {
                $explodedQueue = explode(';', $queue);
                foreach ($explodedQueue as $item) {
                    $partialTime = $this->getData('b_building');

                    if ($partialTime < $time) {
                        $planet->updateResources($partialTime);
                        if (CheckPlanetBuildingQueue($planet, $user)) {
                            SetNextQueueElementOnTop($planet, $user);
                        }
                    } else {
                        $planet->updateResources($time);
                        break;
                    }
                }
            } else {
                $planet->updateResources($time);
            }
        }
    }
}