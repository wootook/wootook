<?php

class Wootook_Player_Model_Session
    extends Wootook_Core_Model_Session
    implements Wootook_Core_Singleton
{
    protected static $_singleton = null;

    /**
     * @var Wootook_Player_Model_Entity
     */
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

    public function setPlayer(Wootook_Player_Model_Entity $player)
    {
        $this->_player = $player;
        $this->setData('player_id', $player->getId());

        return $this;
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
                } else if (Wootook::getRequest() !== null &&
                        ($cookieData = Wootook::getRequest()->getCookie($this->_player->getCookieName())) !== null &&
                        is_array($cookieData)) {

                    $adapter = $this->_player->getReadConnection();
                    $select = $adapter->select(array('user' => $adapter->getTable('users')));

                    $select
                        ->column('id')
                        ->where('id', isset($cookieData['id']) ? intval($cookieData['id']) : 0)
                        ->where(new Wootook_Core_Database_Sql_Placeholder_Expression(
                            ':key=CONCAT((@salt:=MID(:key, 0, 4)), SHA1(CONCAT(user.username, user.password, @salt)))',
                            array('key' => isset($cookieData['key']) ? $adapter->quote($cookieData['key']) : null)))
                    ;
                    try {
                        $statement = $adapter->prepare($select);
                        if (!$statement->execute($cookieData) || $statement->rowCount() <= 0) {
                            throw new Wootook_Player_Exception_Session('Your session has expired, please login.');
                        }

                        $id = $statement->fetchColumn();
                    } catch (Wootook_Core_Exception_Database_AdapterError $e) {
                        throw new Wootook_Core_Exception_DataAccessException('Session error.', null, $e);
                    } catch (Wootook_Core_Exception_Database_StatementError $e) {
                        throw new Wootook_Core_Exception_DataAccessException('Session error.', null, $e);
                    }
                } else {
                    //throw new Wootook_Player_Exception_Session('Your session has expired, please login.');
                    return $this->_player;
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
            if ($this->_player === null) {
                $this->_player = new Wootook_Player_Model_Entity();
            }

            if ($this->_player->getId()) {
                return $this->_player;
            }
            $adapter = $this->_player->getReadConnection();
            $select = $adapter->select(array('user' => $adapter->getTable($this->_player->getTableName())));

            $passwordHash = $this->_player->hash($password);
            $select
                ->column(array(
                    'id'               => 'user.id',
                    'username'         => 'user.username',
                    'password_hash'    => 'user.password',
                    'is_banned'        => 'user.banaday',
                    'login_rememberme' => new Wootook_Core_Database_Sql_Placeholder_Expression('CONCAT((@salt:=MID(MD5(RAND()), 0, 4)), SHA1(CONCAT(user.username, user.password, @salt)))'),
                    'login_success'    => new Wootook_Core_Database_Sql_Placeholder_Expression("(CASE WHEN user.password={$adapter->quote($passwordHash)} THEN 1 ELSE 0 END)")
                    ))
                ->where(new Wootook_Core_Database_Sql_Placeholder_Expression('user.username=:username', array('username' => $username)));

            $statement = $adapter->prepare($select);
            if (!$statement->execute()) {
                $this->addError(Wootook::__('No such user.'));
                return $this->_player;
            }
        } catch (Wootook_Core_Exception_Database_StatementError $e) {
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

            if ($login['is_banned'] != 0) {
                if ($login['is_banned'] <= time()) {
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
