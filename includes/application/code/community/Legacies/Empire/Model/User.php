<?php

/**
 *
 * Enter description here ...
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 */
class Legacies_Empire_Model_User
    extends Legacies_Core_Entity
{
    protected static $_instances = array();

    protected static $_singleton = null;

    const SESSION_KEY     = 'user';
    const COOKIE_NAME     = 'legacies';
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

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            $session = Legacies::getSession(self::SESSION_KEY);
            if ($session->hasData('user_id')) {
                $id = intval($session->getData('user_id'));
            } else if (Legacies::$request !== null && ($cookie = Legacies::$request->getCookie(self::$_cookieName)) !== null) {
                $cookieData = unserialize(stripslashes($cookie));
                if (is_array($cookieData)) {
                    $collection = new Legacies_Core_Collection(array('user' => 'users'));
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
            } catch (Legacies_Core_Model_Exception $e) {
                $session->addError('Session error.');
                return null;
            }
            self::$_singleton->_updateActivity();
        }
        return self::$_singleton;
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
        if (Legacies::$response !== null) {
            Legacies::$response->unsetCookie(self::$_cookieName);
        }
        Legacies_Core_Model_Session::destroy();
    }

    public static function login($username, $password, $remember = false)
    {
        static $statement = null;

        $session = Legacies::getSession(self::SESSION_KEY);

        $collection = new Legacies_Core_Collection(array('user' => 'users'));
        $collection
            ->column('user.id')
            ->column('user.username')
            ->column('user.banaday')
            ->column('(CASE WHEN MD5(:password)=user.password THEN 1 ELSE 0 END) AS login_success')
            ->column('CONCAT((@salt:=MID(MD5(RAND()), 0, 4)), SHA1(CONCAT(user.username, user.password, @salt))) AS login_rememberme')
            ->where('user.username=:username')
            ->load(array(
                'username' => $username,
                'password' => $password
                ))
        ;

        if ($collection->count() <= 0) {
            $session->addError('No such user.');
            return null;
        }
        $login = $collection->current();

        if (intval($login['login_success']) == 1) {
            if ($login['banaday'] != 0) {
                if($login['banaday'] <= time() && $login['banaday'] != '0') {
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

            if (isset($_POST["rememberme"]) && Legacies::$request !== null) {
                Legacies::$response->setCookie(self::$_cookieName, array('id' => $login['id'], 'key' => $login['login_rememberme']), self::COOKIE_LIFETIME);
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
            $user = new self(array(
                'username' => $username,
                'password' => md5($password),
                'email'    => $email,
                'email_2'  => $email
                ));

            $user->save();

            Legacies::dispatchEvent('user.init', array(
                'user' => $user
                ));

            $user->save();
        } catch (Legacies_Core_Model_Exception $e) {
            $session->addError($e->getMessage());
            return null;
        }

        return $user;
    }

    public function updateCurrentPlanet($planetId)
    {
        $planetColelction = $this->_preparePlanetCollection()->where('id=:id');

        $planetCollection->load(array(
            'id'    => $planetId,
            'user' => $this->getId()
            ));

        if ($planetCollection->count() !== 1) {
            return false;
        }

        $planet = $planetCollection->current();
        $this->setData('current_planet', $planet->getId());

        return true;
    }

    public function getCurrentPlanet()
    {
        $planetId = $this->getData('current_planet');
        if (!$planetId) {
            $planetId = $this->getData('id_planet');
            $this->setData('current_planet', $planetId)->save();
        }
        return Legacies_Empire_Model_Planet::factory($planetId);
    }

    protected function _preparePlanetCollection()
    {
        $planetCollection = new Legacies_Core_Collection(array('planet' => 'planets'), 'Legacies_Empire_Model_Planet');
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

    public function getPlanetCollection()
    {
        $planetCollection = $this->_preparePlanetCollection();

        $planetCollection->load(array(
            'user' => $this->getId()
            ));

        return $planetCollection;
    }

    public function getFleets()
    {
        $collection = new Legacies_Core_Collection(array('fleet' => 'fleets'));
        $collection
            ->setEntityClassName('Legacies_Empire_Model_Fleet')
            ->where('fleet_owner <= :user_id')
            ->load(array('user_id' => $this->getId()))
        ;

        return $collection;
    }

    public function getVisibleFleets()
    {

        $user = Legacies_Empire_Model_User::getSingleton();
        $firstCollection = new Legacies_Core_Collection(array('fleet' => 'fleets'));
        $firstCollection
//            ->column('*')
            ->column('fleet.fleet_start_galaxy', 'galaxy')
            ->column('fleet.fleet_start_system', 'system')
            ->column('fleet.fleet_start_planet', 'planet')
            ->column('fleet.fleet_start_type',   'planet_type')
            ->where('fleet_end_time <= :now')
        ;
        $backCollection = new Legacies_Core_Collection(array('fleet' => 'fleets'));
        $backCollection
//            ->column('*')
            ->column('fleet.fleet_end_galaxy', 'galaxy')
            ->column('fleet.fleet_end_system', 'system')
            ->column('fleet.fleet_end_planet', 'planet')
            ->column('fleet.fleet_end_type',   'planet_type')
            ->where('fleet_end_time <= :now')
        ;
        $collection = new Legacies_Core_Collection();
        $collection
            ->setEntityClassName('Legacies_Empire_Model_Fleet')
            ->union($firstCollection)
            ->union($backCollection)
            ->load(array('now' => time()));

        return $collection;
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
}