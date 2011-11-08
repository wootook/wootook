<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Empire_Model_User
    extends Wootook_Core_Entity
{
    protected static $_instances = array();

    protected static $_singleton = null;

    protected $_eventPrefix = 'user';
    protected $_eventObject = 'user';

    public static $hashCallback = 'md5';

    protected $_homePlanet = null;
    protected $_currentPlanet = null;

    const SESSION_KEY     = 'user';
    const COOKIE_NAME     = 'Wootook';
    const COOKIE_LIFETIME = 2592000;

    const PLANET_SORT_DATE     = 0;
    const PLANET_SORT_POSITION = 1;
    const PLANET_SORT_NAME     = 2;

    protected static $_cookieName = self::COOKIE_NAME;

    public static function setCookieName($name)
    {
        self::$_cookieName = $name;
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

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            $session = Wootook::getSession(self::SESSION_KEY);
            if ($session->hasData('user_id')) {
                $id = intval($session->getData('user_id'));
            } else if (Wootook::getRequest() !== null && ($cookieData = Wootook::getRequest()->getCookie(self::$_cookieName)) !== null) {
                //$cookieData = unserialize(stripslashes($cookie));
                if (is_array($cookieData)) {
                    $collection = new Wootook_Core_Collection(array('user' => 'users'));
                    $cookieData = array(
                        'id' => (isset($cookieData['id']) ? intval($cookieData['id']) : 0),
                        'key' => (isset($cookieData['key']) ? $collection->quote($cookieData['key']) : null)
                        );

                    $collection
                        ->column('id')
                        ->where('user.id=:id')
                        ->where(':key=CONCAT((@salt:=MID(:key, 0, 4)), SHA1(CONCAT(user.username, user.password, @salt)))')
                        ->load($cookieData)
                    ;

                    if ($collection->count() > 0) {
                        $session->setData(self::SESSION_KEY, $cookieData['id']);
                    } else {
                        $session->addError('Your session has expired, please login.');
                        return null;
                    }
                }
            } else {
                $session->addError('Your session has expired, please login.');
                return null;
            }

            try {
                self::$_singleton = self::factory($id);
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                $session->addError('Session error.');
                return null;
            }
            self::$_singleton->_updateActivity();
        }
        return self::$_singleton;
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
    }

    protected function _updateActivity()
    {
        $this
            ->setData('current_page', $_SERVER['REQUEST_URI'])
            ->setData('user_lastip', $_SERVER['REMOTE_ADDR'])
            ->setData('user_agent', $_SERVER['HTTP_USER_AGENT'])
            ->setData('onlinetime', time())
            ->save()
        ;
        return $this;
    }

    public function logout()
    {
        Wootook::getResponse()->unsetCookie(self::$_cookieName);
        Wootook_Core_Model_Session::destroy();
    }

    public static function login($username, $password, $remember = false)
    {
        $session = Wootook::getSession(self::SESSION_KEY);

        try {
            $collection = new Wootook_Core_Collection(array('user' => 'users'));

            $passwordHash = md5($password);
            $collection
                ->column('user.id')
                ->column('user.username')
                ->column('user.password')
                ->column('user.banaday')
                ->column('CONCAT((@salt:=MID(MD5(RAND()), 0, 4)), SHA1(CONCAT(user.username, user.password, @salt))) AS login_rememberme')
                ->column('(CASE WHEN user.password="' . $passwordHash . '" THEN 1 ELSE 0 END) AS login_success')
                ->where('user.username=:username')
                ->load(array(
                    'username' => $username
                    ))
            ;
        } catch (Wootook_Core_Exception_DataAccessException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            $session->addError('No such user.');
            return null;
        }

        if ($collection->count() <= 0) {
            $session->addError('No such user.');
            return null;
        }
        $login = $collection->current();

        if (intval($login['login_success']) == 1) {
            if ($login['banaday'] != 0) {
                if($login['banaday'] <= time()) {
                     $user->setData('banaday', 0)
                         ->setData('bana', 0)
                         ->setData('urlaubs_modus', 0)
                         ->save()
                     ;
                } else {
                    $session->addError('You were banned, please contact admin for more information.');
                    return null;
                }
            }

            if (isset($_POST["rememberme"]) && Wootook::getResponse() !== null) {
                Wootook::getResponse()->setCookie(self::$_cookieName, array('id' => $login['id'], 'key' => $login['login_rememberme']), self::COOKIE_LIFETIME);
            }

            self::$_singleton = self::factory($login['id']);
            self::$_singleton->_updateActivity();

            $session->setData('user_id', intval($login['id']));

            return self::$_singleton;
        }

        $session->addError('Your username or credential is invalid, please check your input.');
        return null;
    }

    public static function register($username, $email, $password)
    {
        try {
            $request = Wootook::getRequest();
            $user = new self(array(
                'username' => $username,
                'password' => md5($password),
                'email'    => $email,
                'email_2'  => $email,

                'register_time' => Wootook::now(),
                'onlinetime'    => Wootook::now(),
                'ip_at_reg'     => $request->getServer('REMOTE_ADDR'),
                'user_lastip'   => $request->getServer('REMOTE_ADDR'),
                'user_agent'    => $request->getServer('HTTP_USER_AGENT')
                ));

            $user->save();

            Wootook::dispatchEvent('user.init', array(
                'user' => $user
                ));

            $user->save();
        } catch (Wootook_Core_Exception_DataAccessException $e) {
            $session = Wootook_Core_Model_Session::factory(Wootook_Empire_Model_User::SESSION_KEY);

            trigger_error($e->getMessage(), E_USER_ERROR);
            $session->addError($e->getMessage());
            return null;
        }

        return $user;
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

        if ($planet->getUserId() != $this->getId() || $planet->isDestroyed()) {
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
        if ($planet->getUserId() != $this->getId() || $planet->isDestroyed()) {
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
        if ($planet->getUserId() != $this->getId() || $planet->isDestroyed()) {
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

            if ($planet->getUserId() != $this->getId() || $planet->isDestroyed()) {
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
                $this->setData('current_planet', $this->_currentPlanet->getId())->save();

                return $this->_currentPlanet;
            }

            $planet = Wootook_Empire_Model_Planet::factory($planetId);

            if ($planet->getUserId() != $this->getId() || $planet->isDestroyed()) {
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
        $planetCollection = new Wootook_Core_Collection(array('planet' => 'planets'), 'Wootook_Empire_Model_Planet');
        $planetCollection->where('id_owner=:user');

        $order = ($this->getData('planet_sort_order') == 1) ? 'DESC' : 'ASC';

        switch ($this->getData('planet_sort')) {
        case self::PLANET_SORT_POSITION:
            $planetCollection
                ->order('planet.galaxy', $order)
                ->order('planet.system', $order)
                ->order('planet.planet', $order)
                ->order('planet.planet_type', $order)
            ;
            break;

        case self::PLANET_SORT_NAME:
            $planetCollection->order('planet.name', $order);
            break;

        case self::PLANET_SORT_DATE:
        default:
            $planetCollection->order('planet.id', $order);
            break;
        }
        return $planetCollection;
    }

    public function getPlanetCollection(Array $typeFilter = array())
    {
        $planetCollection = $this->_preparePlanetCollection();

        if (!empty($typeFilter)) {
            $planetCollection->where('planet.planet_type IN(' . implode(',', $typeFilter) . ')');
        }

        $planetCollection->load(array(
            'user' => $this->getId()
            ));

        return $planetCollection;
    }

    protected function _prepareFleetCollection()
    {
        $collection = new Wootook_Core_Collection(array('fleet' => 'fleets'));
        $collection
            ->setEntityClassName('Wootook_Empire_Model_Fleet')
            ->where('fleet_owner <= :user_id')
        ;
        return $collection;
    }

    public function getFleets()
    {
        return $this->_prepareFleetCollection()->load(array('user_id' => $this->getId()));
    }

    public function getFleetCount()
    {
        return $this->_prepareFleetCollection()->getSize(array('user_id' => $this->getId()));
    }

    public function getVisibleFleets()
    {

        $user = Wootook_Empire_Model_User::getSingleton();
        $firstCollection = new Wootook_Core_Collection(array('fleet' => 'fleets'));
        $firstCollection
//            ->column('*')
            ->column('fleet.fleet_start_galaxy', 'galaxy')
            ->column('fleet.fleet_start_system', 'system')
            ->column('fleet.fleet_start_planet', 'planet')
            ->column('fleet.fleet_start_type',   'planet_type')
            ->where('fleet_end_time <= :now')
        ;
        $backCollection = new Wootook_Core_Collection(array('fleet' => 'fleets'));
        $backCollection
//            ->column('*')
            ->column('fleet.fleet_end_galaxy', 'galaxy')
            ->column('fleet.fleet_end_system', 'system')
            ->column('fleet.fleet_end_planet', 'planet')
            ->column('fleet.fleet_end_type',   'planet_type')
            ->where('fleet_end_time <= :now')
        ;
        $collection = new Wootook_Core_Collection();
        $collection
            ->setEntityClassName('Wootook_Empire_Model_Fleet')
            ->union($firstCollection)
            ->union($backCollection)
            ->load(array('now' => time()));

        return $collection;
    }

    public function getElement($elementId)
    {
        $fields = Wootook_Empire_Model_Game_FieldsAlias::getSingleton();

        return $this->getData($fields[$elementId]);
    }

    public function setElement($elementId, $level)
    {
        $fields = Wootook_Empire_Model_Game_FieldsAlias::getSingleton();

        return $this->setData($fields[$elementId], $level);
    }

    public function hasElement($elementId, $levelRequired = 0)
    {
        $fields = Wootook_Empire_Model_Game_FieldsAlias::getSingleton();

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

    public function getFleetCollection($time = null)
    {
        $firstCollection = new Wootook_Core_Collection(array('fleet' => 'fleets'));
        $firstCollection
            ->column('*')
            ->where('fleet.fleet_owner = :user')
        ;
        $backCollection = new Wootook_Core_Collection(array('fleet' => 'fleets'));
        $backCollection
            ->column('*')
            ->where('fleet.fleet_target_owner = :user')
        ;

        $options = array(
            'user' => $this->getId()
            );

        if ($time !== null) {
            $options['now'] = $time;
            $firstCollection->where('fleet_start_time <= :now');
            $backCollection->where('fleet_end_time <= :now');
        }

        $collection = new Wootook_Core_Collection();
        $collection
            ->setEntityClassName('Wootook_Empire_Model_Fleet')
            ->union($firstCollection)
            ->union($backCollection)
            ->load($options)
        ;

        return $collection;
    }

    public function getNewMessagesCount()
    {
        $messageCollection = new Wootook_Core_Collection('messages');
        $messageCollection->where('message_owner=:user');
        $messageCollection->where('message_read_at<=0');

        $newMessages = null;
        if (($count = $messageCollection->getSize(array('user' => $this->getId()))) > 0) {
            return $count;
        }
        return null;
    }

    public static function layoutPrepareAfterListener($eventData)
    {
        $player = self::getSingleton();
        if ($player === null || !$player->getId() || !in_array($player->getData('authlevel'), array(LEVEL_ADMIN, LEVEL_OPERATOR, LEVEL_MODERATOR))) {
            return;
        }

        if (!isset($eventData['layout']) || !$eventData['layout'] instanceof Wootook_Core_Layout) {
            return;
        }
        $layout = $eventData['layout'];
        $navigation = $layout->getBlock('navigation');

        if (!defined('IN_ADMIN')) {
            $navigation->addLink('tools/admin', 'Admin Panel', 'Admin Panel', 'admin/overview.php', array(), array('admin'));
        } else {
            $navigation->addLink('tools/back', 'Go back to the game', 'Go back to the game', 'overview.php', array(), array('admin'));
        }
    }
}