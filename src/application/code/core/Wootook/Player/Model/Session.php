<?php

class Wootook_Player_Model_Session
    extends Wootook_Core_Model_Session
    implements Wootook_Core_Singleton
{
    protected static $_singleton = null;

    protected $_player = null;

    public function __construct()
    {
        parent::__construct(Wootook_Player_Model_Entity::SESSION_KEY);
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Player_Model_Session
     */
    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            self::$_singleton = new self();
        }

        return self::$_singleton;
    }

    public function getPlayerId()
    {
        return $this->getData('player_id');
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Player_Model_Entity
     */
    public function getPlayer()
    {
        if ($this->_player === null) {
            $this->_player = new Wootook_Player_Model_Entity();

            try {
                if ($this->hasData('player_id')) {
                    $id = intval($this->getData('player_id'));
                } else if (Wootook::getRequest() !== null && ($cookieData = Wootook::getRequest()->getCookie($this->_player->getCookieName())) !== null) {
                    if (is_array($cookieData)) {
                        $adapter = $this->_player->getReadConnection();
                        $select = $adapter->select(array('user' => 'users'));
                        $cookieData = array(
                            'id' => (isset($cookieData['id']) ? intval($cookieData['id']) : 0),
                            'key' => (isset($cookieData['key']) ? $collection->quote($cookieData['key']) : null)
                            );

                        $select
                            ->column('id')
                            ->where('user.id=:id')
                            ->where(':key=CONCAT((@salt:=MID(:key, 0, 4)), SHA1(CONCAT(user.username, user.password, @salt)))')
                        ;
                        try {
                            $statement = $adapter->prepare($select);
                            if (!$statement->execute($cookieData) || $statement->rowCount() <= 0) {
                                throw new Wootook_Player_Exception_Session('Your session has expired, please login.');
                            }
                        } catch (PDOException $e) {
                            throw new Wootook_Core_Exception_DataAccessException('Session error.', null, $e);
                        }

                        $this->setData(self::SESSION_KEY, $cookieData['id']);
                    } else {
                        throw new Wootook_Player_Exception_Session('Your session has expired, please login.');
                    }
                } else {
                    throw new Wootook_Player_Exception_Session('Your session has expired, please login.');
                }
            } catch (Wootook_Player_Exception_Session $e) {
                $this->addError($e->getMessage());
                return $this->_player;
            }

            try {
                $this->_player->load($id);
            } catch (Wootook_Core_Exception_DataAccessException $e) {
                $this->addError('Session error.');
                return $this->_player;
            }
            $this->_player->updateActivity();
        }
        return $this->_player;
    }

    public function isLoggedIn()
    {
        if ($this->getPlayerId()) {
            return true;
        }
        return false;
    }

    public function logout()
    {
        $this->clearData();

        return $this;
    }

    public function login($username, $password, $remember = false)
    {
        try {
            if ($this->getPlayer()->getId()) {
                return $this->_player;
            }
            $adapter = $this->_player->getReadConnection();
            $select = $adapter->select(array('user' => 'users'));

            $passwordHash = md5($password);
            $select
                ->column('user.id')
                ->column('user.username')
                ->column('user.password')
                ->column('user.banaday')
                ->column('CONCAT((@salt:=MID(MD5(RAND()), 0, 4)), SHA1(CONCAT(user.username, user.password, @salt))) AS login_rememberme')
                ->column('(CASE WHEN user.password="' . $passwordHash . '" THEN 1 ELSE 0 END) AS login_success')
                ->where('user.username=:username');

            $statement = $adapter->prepare($select);
            if (!$statement->execute(array('username' => $username))) {
                $this->addError(Wootook::__('No such user.'));
                return $this->_player;
            }
        } catch (PDOException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            $this->addError(Wootook::__('No such user.'));
            return $this->_player;
        } catch (Wootook_Core_Exception_DataAccessException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            $this->addError(Wootook::__('No such user.'));
            return $this->_player;
        }

        $login = $statement->fetch();

        if (intval($login['login_success']) == 1) {
            $this->_player->load($login['id']);

            if ($login['banaday'] != 0) {
                if ($login['banaday'] <= time()) {
                    $this->_player->setData('banaday', 0)
                        ->setData('bana', 0)
                        ->setData('urlaubs_modus', 0)
                        ->setData('urlaubs_until', null)
                        ->save()
                    ;
                } else {
                    $this->addError(Wootook::__('You were banned, please contact admin for more information.'));
                    return $this->_player;
                }
            }

            if (isset($_POST["rememberme"]) && Wootook::getResponse() !== null) {
                Wootook::getResponse()->setCookie(
                    $this->_player->getCookieName(),
                    array('id' => $this->_player->getId(), 'key' => $login['login_rememberme']),
                    $this->_player->getCookieLifetime(),
                    $this->_player->getCookiePath(),
                    $this->_player->getCookieDomain()
                    );
            }

            $this->setLoggedIn($this->_player);
            return $this->_player;
        }

        $this->addError(Wootook::__('Your username or credential is invalid, please check your input.'));
        return $this->_player;
    }

    public function setLoggedIn(Wootook_Player_Model_Entity $player)
    {
        $player->updateActivity();

        $this->setData('player_id', intval($player->getId()));

        return $this;
    }
}