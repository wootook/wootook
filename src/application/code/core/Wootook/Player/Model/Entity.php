<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Player_Model_Entity
    extends Wootook_Core_Mvc_Model_Entity
{
    protected static $_instances = array();

    protected static $_singleton = null;

    protected $_eventPrefix = 'player';
    protected $_eventObject = 'player';

    public static $hashCallback = 'md5';

    protected $_homePlanet = null;
    protected $_currentPlanet = null;

    const SESSION_KEY     = 'player';
    const COOKIE_NAME     = '__wtk';
    const COOKIE_LIFETIME = 2592000;

    const COOKIE_NAME_CONFIG_KEY     = 'web/cookie/name';
    const COOKIE_LIFETIME_CONFIG_KEY = 'web/cookie/time';
    const COOKIE_DOMAIN_CONFIG_KEY   = 'web/cookie/domain';
    const COOKIE_PATH_CONFIG_KEY     = 'web/cookie/path';

    const PLANET_SORT_DATE     = 0;
    const PLANET_SORT_POSITION = 1;
    const PLANET_SORT_NAME     = 2;

    public function getCookieName()
    {
        $cookieName = Wootook::getWebsiteConfig(self::COOKIE_NAME_CONFIG_KEY);

        if (is_null($cookieName)) {
            return self::COOKIE_NAME;
        }

        return $cookieName;
    }

    public function getCookieLifetime()
    {
        $cookieLifetime = Wootook::getWebsiteConfig(self::COOKIE_LIFETIME_CONFIG_KEY);

        if (is_null($cookieLifetime)) {
            return self::COOKIE_LIFETIME;
        }

        return $cookieLifetime;
    }

    public function getCookieDomain()
    {
        return Wootook::getWebsiteConfig(self::COOKIE_DOMAIN_CONFIG_KEY);
    }

    public function getCookiePath()
    {
        return Wootook::getWebsiteConfig(self::COOKIE_PATH_CONFIG_KEY);
    }

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

    public function getUsername()
    {
        return $this->getData('username');
    }

    public function getEmail()
    {
        return $this->getData('email');
    }

    public function loadByEmail($email)
    {
        return $this->load($email, 'email');
    }

    public function setPassword($newPassword)
    {
        return $this->setData('password', $this->hash($newPassword));
    }

    public static function hash($password, $salt = null)
    {
        if ($salt === null) {
            return call_user_func(self::$hashCallback, ($password));
        }

        return call_user_func(self::$hashCallback, ($password . $salt));
    }

    protected function _init()
    {
        $this->setIdFieldName('id');
        $this->setTableName('users');

        $this->getDataMapper()
            ->addRule('onlinetime', 'date-time')
            ->addRule('urlaubs_until', 'date-time')
            ->addRule('register_time', 'date-time')
        ;
    }

    public function updateActivity()
    {
        $this
            ->setData('current_page', $_SERVER['REQUEST_URI'])
            ->setData('user_lastip', $_SERVER['REMOTE_ADDR'])
            ->setData('user_agent', $_SERVER['HTTP_USER_AGENT'])
            ->setData('onlinetime', new Wootook_Core_DateTime())
            ->save()
        ;
        return $this;
    }

    public static function register($username, $email, $password)
    {
        try {
            $request = Wootook::getRequest();
            $player = new self(array(
                'username' => $username,
                'password' => md5($password),
                'email'    => $email,
                'email_2'  => $email,

                'register_time' => new Wootook_Core_DateTime(),
                'onlinetime'    => new Wootook_Core_DateTime(),
                'ip_at_reg'     => $request->getServer('REMOTE_ADDR'),
                'user_lastip'   => $request->getServer('REMOTE_ADDR'),
                'user_agent'    => $request->getServer('HTTP_USER_AGENT')
                ));

            //$player->getWriteConnection()->beginTransaction();

            $player->save();

            $select = Wootook_Empire_Model_Planet::searchMostFreeSystems();
            $select->limit(1);

            try {
                $statement = $select->prepare();
                if (!$statement->execute()) {
                    throw new Wootook_Empire_Exception_RuntimeException('No more planet to colonize!'); // Oops, no more free place
                }
            } catch (Wootook_Core_Exception_Database_Error $e) {
                Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
                //$this->getWriteConnection()->rollback();
                return null;
            }

            $systemInfo = $statement->fetch();
            if ($systemInfo['count'] >= Wootook::getGameConfig('engine/universe/positions')) {
                throw new Wootook_Empire_Exception_RuntimeException('No more planet to colonize!'); // Oops, no more free place
            }

            $collection = new Wootook_Empire_Resource_Planet_Collection($player->getReadConnection());
            $collection->addTypeToFilter(Wootook_Empire_Model_Planet::TYPE_PLANET)
                ->addFieldToFilter('galaxy', $systemInfo['galaxy'])
                ->addFieldToFilter('system', $systemInfo['system'])
            ;

            $positions = range(1, Wootook::getGameConfig('engine/universe/positions'));
            foreach ($collection as $planet) {
                $key = array_search($planet->getData('position'), $positions);
                if ($key !== false) {
                    unset($positions[$key]);
                }
            }
            $key = array_rand($positions, 1);
            $finalPosition = $positions[$key];

            $planet = $player->createNewPlanet(
                $systemInfo['galaxy'],
                $systemInfo['system'],
                $finalPosition,
                Wootook_Empire_Model_Planet::TYPE_PLANET,
                Wootook::__('Planet'),
                Wootook::getGameConfig('resource/initial/fields')
                );

            $player
                ->setData('id_planet', $planet->getId())
                ->setData('current_planet', $planet->getId())
                ->setData('galaxy', $planet->getGalaxy())
                ->setData('system', $planet->getSystem())
                ->setData('planet', $planet->getPosition())
            ;

            Wootook::dispatchEvent('player.init', array(
                'player' => $player
                ));

            $player->save();
        } catch (Wootook_Core_Exception_DataAccessException $e) {
            //$player->getWriteConnection()->rollback();
            $session = Wootook_Core_Model_Session::factory(Wootook_Player_Model_Entity::SESSION_KEY);

            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
            $session->addError($e->getMessage());
            return null;
        } catch (Wootook_Empire_Exception_RuntimeException $e) {
            //$player->getWriteConnection()->rollback();
            $session = Wootook_Core_Model_Session::factory(Wootook_Player_Model_Entity::SESSION_KEY);

            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
            $session->addError($e->getMessage());
            return null;
        }
        //$player->getWriteConnection()->commit();

        return $player;
    }

    public function createNewPlanet($galaxy, $system, $position, $type, $name, $size = null)
    {
        if ($size === null) {
            $size = Wootook::getGameConfig('planet/initial/fields');
        }
        if ($size === null) {
            $size = 200;
        }

        $now = new Wootook_Core_DateTime();
        $planet = new Wootook_Empire_Model_Planet(array(), $this);
        $planet
            ->setData('name', $name)
            ->setData('galaxy', $galaxy)
            ->setData('system', $system)
            ->setData('planet', $position)
            ->setData('planet_type', $type)
            ->setData('field_max', $size)
            ->setData('diameter', pow($size, 2) + mt_rand(0, $size * $position))
            ->setData('field_current', 0)
            ->setData('last_update', $now)
            ->setData('b_tech', $now)
            ->setData('b_hangar', $now)
            ->setData('b_building', $now)
        ;

        $resourceList = Wootook_Empire_Helper_Config_Resources::getSingleton();
        $resourceConfig = Wootook::getGameConfig('resources/initial');
        foreach ($resourceList as $resource => $resourceData) {
            if ($resourceData['field'] === null || !isset($resourceConfig[$resource])) {
                continue;
            }
            $planet->setData($resourceData['field'], $resourceConfig[$resource]);
        }

        $resourceConfig = Wootook::getGameConfig('resources/base-income');
        foreach ($resourceList as $resource => $resourceData) {
            if ($resourceData['production_field'] === null || !isset($resourceConfig[$resource])) {
                continue;
            }
            $planet->setData($resourceData['production_field'], $resourceConfig[$resource]);
        }

        $planet->updateStorages($now);
        $planet->updateBuildingQueue($now);
        $planet->updateResourceProduction($now);
        $planet->updateResources($now);

        $planet->save();

        if ($planet->isPlanet()) {
            $galaxy = new Wootook_Empire_Model_Galaxy_Position();
            $galaxy
                ->setData('galaxy', $planet->getGalaxy())
                ->setData('system', $planet->getSystem())
                ->setData('planet', $planet->getPosition())
                ->setData('id_planet', $planet->getId())
                ->save()
            ;
        }

        Wootook::dispatchEvent('planet.init', array(
            'planet' => $planet,
            'player' => $this
            ));

        $planet->save();

        return $planet;
    }

    /**
     *
     * Enter description here ...
     * @param int|Wootook_Empire_Model_Planet $planet
     */
    public function updateCurrentPlanet($planet)
    {
        if (!$planet instanceof Wootook_Empire_Model_Planet) {
            $planetCollection = $this->_preparePlanetCollection()->where('id=:id');

            $planetCollection->load(array(
                'id'   => $planet,
                'user' => $this->getId()
                ));

            if ($planetCollection->count() !== 1) {
                return $this;
            }

            $planet = $planetCollection->current();
        }

        if ($planet->getPlayerId() != $this->getId() || $planet->isDestroyed()) {
            return $this;
        }

        $this->setData('current_planet', $planet->getId())->save();

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @param Wootook_Empire_Model_Planet $planet
     */
    public function setHomePlanet(Wootook_Empire_Model_Planet $planet)
    {
        if ($planet->getPlayerId() != $this->getId() || $planet->isDestroyed()) {
            return $this;
        }

        $this->setData('id_planet', $planet->getId());
        $this->_homePlanet = $planet;

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @param Wootook_Empire_Model_Planet $planet
     */
    public function setCurrentPlanet(Wootook_Empire_Model_Planet $planet)
    {
        if ($planet->getPlayerId() != $this->getId() || $planet->isDestroyed()) {
            return $this;
        }

        $this->setData('current_planet', $planet->getId());
        $this->_currentPlanet = $planet;

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Empire_Model_Planet
     */
    public function getHomePlanet()
    {
        if ($this->_homePlanet === null) {
            $planetId = $this->getData('id_planet');
            if (!$planetId) {
                return null;
            }

            $planet = Wootook_Empire_Model_Planet::factory($planetId);

            if ($planet->getPlayerId() != $this->getId() || $planet->isDestroyed()) {
                return null;
            }

            $this->_homePlanet = $planet;
        }

        return $planet;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Empire_Model_Planet
     */
    public function getCurrentPlanet()
    {
        if ($this->_currentPlanet === null) {
            $planetId = $this->getData('current_planet');

            if (!$planetId) {
                $this->_currentPlanet = $this->getHomePlanet();
                if ($this->_currentPlanet === null) {
                    return null;
                }
                $this->setData('current_planet', $this->_currentPlanet->getId())->save();

                return $this->_currentPlanet;
            }

            $planet = Wootook_Empire_Model_Planet::factory($planetId);

            if ($planet->getPlayerId() != $this->getId() || $planet->isDestroyed()) {
                $this->_currentPlanet = $this->getHomePlanet();
                $this->setData('current_planet', $this->_currentPlanet->getId())->save();

                return $this->_currentPlanet;
            }

            $this->_currentPlanet = $planet;
        }

        return $this->_currentPlanet;
    }

    protected function _preparePlanetCollection()
    {
        $planetCollection = new Wootook_Empire_Resource_Planet_Collection($this->getReadConnection());
        $planetCollection->addPlayerToFilter($this);

        $order = ($this->getData('planet_sort_order') == 1) ? 'DESC' : 'ASC';

        switch ($this->getData('planet_sort')) {
        case self::PLANET_SORT_POSITION:
            $planetCollection
                ->addOrderBy('galaxy', $order)
                ->addOrderBy('system', $order)
                ->addOrderBy('planet', $order)
                ->addOrderBy('planet_type', $order)
            ;
            break;

        case self::PLANET_SORT_NAME:
            $planetCollection->addOrderBy('name', $order);
            break;

        case self::PLANET_SORT_DATE:
        default:
            $planetCollection->addOrderBy('id', $order);
            break;
        }
        return $planetCollection;
    }

    public function getPlanetCollection(Array $typeFilter = array())
    {
        $planetCollection = $this->_preparePlanetCollection();

        if (!empty($typeFilter)) {
            $planetCollection->addFieldToFilter('planet_type', array(array('in' => $typeFilter)));
        }

        return $planetCollection;
    }

    protected function _prepareFleetCollection($collection)
    {
        $collection->addFieldToFilter('fleet_owner', $this->getId());

        return $this;
    }

    /**
     * Returns all the flying fleets owned by the player
     *
     * @return Wootook_Empire_Resource_Fleet_Collection
     */
    public function getFleets()
    {
        $collection = new Wootook_Empire_Resource_Fleet_Collection($this->getReadConnection());

        $this->_prepareFleetCollection($collection);

        return $collection;
    }

    /**
     * Counts all the flying fleets owned by the player
     *
     * @return mixed
     */
    public function getFleetCount()
    {
        return $this->getFleets()->getSize();
    }

    /**
     * Returns all the visible fleets
     *
     * @param null $time
     * @return Wootook_Empire_Resource_Fleet_Collection
     */
    public function getVisibleFleets($time = null)
    {
        $collection = new Wootook_Empire_Resource_Fleet_Collection($this->getReadConnection());

        if ($time === null) {
            $time = new Wootook_Core_DateTime();
        }
        $collection->addIsVisibleToFilter($this, $time);

        return $collection;
    }

    /**
     * Returns all the visible fleets
     *
     * @param null $time
     * @return Wootook_Empire_Resource_Fleet_Collection
     * @deprecated
     * @alias getVisibleFleets
     */
    public function getFleetCollection($time = null)
    {
        return $this->getVisibleFleets($time);
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

    public function getSkinPath($default = null)
    {
        if ($path = $this->getData('dpath')) {
            return $path;
        }
        return $default;
    }

    public function setSkinPath($path)
    {
        $this->setData('dpath', $path);

        return $this;
    }

    public function checkPassword($password)
    {
        if (self::hash($password) == $this->getData('password')) {
            return true;
        }
        return false;
    }

    public function getNewMessagesCount()
    {
        $messageCollection = new Wootook_Player_Resource_Message_Collection($this->getReadConnection());
        $messageCollection->addPlayerToFilter($this)->addUnreadToFilter();

        $newMessages = null;
        if (($count = $messageCollection->getSize()) > 0) {
            return $count;
        }
        return null;
    }

    public static function layoutPrepareAfterListener($eventData)
    {
        $player = Wootook_Player_Model_Session::getSingleton()->getPlayer();
        if ($player === null || !$player->getId() || !in_array($player->getData('authlevel'), array(LEVEL_ADMIN, LEVEL_OPERATOR, LEVEL_MODERATOR))) {
            return;
        }

        if (!isset($eventData['layout']) || !$eventData['layout'] instanceof Wootook_Core_Model_Layout) {
            return;
        }
        $layout = $eventData['layout'];
        $navigation = $layout->getBlock('navigation');

        if ($navigation === null) {
            return;
        }

        if (!defined('IN_ADMIN')) {
            $navigation->addLink('tools/admin', 'Admin Panel', 'Admin Panel', 'admin/overview.php', array(), array('admin'));
        } else {
            $navigation->addLink('tools/back', 'Go back to the game', 'Go back to the game', 'overview.php', array(), array('admin'));
        }
    }

    public function isBanned()
    {
        return $this->getData('bana') ? true : false;
    }

    public function isVacation()
    {
        return $this->getData('urlaubs_modus') ? true : false;
    }

    public function getVacationEndDate()
    {
        return $this->getData('urlaubs_until');
    }

    public function getLastLoginDate()
    {
        return $this->getData('onlinetime');
    }

    public function setVacation($active = true)
    {
        $this->setData('urlaubs_modus', $active);

        foreach ($this->getPlanetCollection() as $planet) {
            $planet->updateResources();
            $planet->updateResourceProduction();
            $planet->save();
        }

        if ($active) {
            $this->setData('urlaubs_until', time() + Wootook::getConfig('engine/options/vacation-min-time'));
        } else {
            $this->setData('urlaubs_until', null);
        }
        $this->save();

        return $this;
    }

    public function isAuthorized($levels = array(LEVEL_ADMIN))
    {
        if (!is_array($levels)) {
            $levels = array($levels);
        }

        if (in_array($this->getData('authlevel'), $levels)) {
            return true;
        }
        return false;
    }
}
