<?php

/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Core_Model_Session
    extends Wootook_Object
{
    const CRIT    = 0x80;
    const ERR     = 0x40;
    const WARN    = 0x20;
    const INFO    = 0x10;
    const DEBUG   = 0x01;
    const SUCCESS = 0;

    protected static $_instances = null;

    protected static $_levels = array(
        self::CRIT    => 'CRIT',
        self::ERR     => 'ERR',
        self::WARN    => 'WARN',
        self::INFO    => 'INFO',
        self::DEBUG   => 'DEBUG',
        self::SUCCESS => 'SUCCESS'
        );

    const COOKIE_LIFETIME_CONFIG_KEY = 'web/session/time';
    const COOKIE_DOMAIN_CONFIG_KEY   = 'web/session/domain';
    const COOKIE_PATH_CONFIG_KEY     = 'web/session/path';

    const IDENTIFIER_EXPIRES  = '__expires';
    const IDENTIFIER_MESSAGES = '__messages';

    public static function factory($namespace)
    {
        $namespace = (string) $namespace;
        if (!isset(self::$_instances[$namespace])) {
            self::$_instances[$namespace] = new self($namespace);
        }
        return self::$_instances[$namespace];
    }

    public static function destroy()
    {
        session_destroy();
    }

    public function __construct($namespace)
    {
        if (session_id() == '') {
            session_set_cookie_params($this->getCookieLifetime(), $this->getCookiePath(), $this->getCookieDomain(), false, true);
            session_start();
            session_regenerate_id(true);
        }

        if (!isset($_SESSION[$namespace]) || !isset($_SESSION[$namespace][self::IDENTIFIER_EXPIRES]) || $_SESSION[$namespace][self::IDENTIFIER_EXPIRES] < time()) {
            $_SESSION[$namespace] = array(
                self::IDENTIFIER_EXPIRES  => time() + $this->getCookieLifetime(),
                self::IDENTIFIER_MESSAGES => array()
                );
        }

        $this->_data = &$_SESSION[$namespace];
        if (!isset($this->_data[self::IDENTIFIER_MESSAGES]) || !is_array($this->_data[self::IDENTIFIER_MESSAGES])) {
            $this->_data[self::IDENTIFIER_MESSAGES] = array();
        }
    }

    public function getCookieLifetime()
    {
        $lifetime = Wootook::getWebsiteConfig(self::COOKIE_LIFETIME_CONFIG_KEY);
        if (!is_numeric($lifetime) || $lifetime <= 0) {
            return 900;
        }

        return $lifetime;
    }

    public function getCookieDomain()
    {
        $domain = Wootook::getWebsiteConfig(self::COOKIE_DOMAIN_CONFIG_KEY);
        if ($domain === null) {
            return $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }

    public function getCookiePath()
    {
        $path = Wootook::getWebsiteConfig(self::COOKIE_PATH_CONFIG_KEY);
        if ($path === null) {
            return '/';
        }
        return $path;
    }

    public function getMessages($clear = true)
    {
        $messages = $this->_data[self::IDENTIFIER_MESSAGES];
        if ($clear == true) {
            $this->_data[self::IDENTIFIER_MESSAGES] = array();
        }
        return $messages;
    }

    public function addMessage($message, $type = self::DEBUG)
    {
        if (!isset(self::$_levels[$type])) {
            $type = self::DEBUG;
        }

        if (!isset($this->_data[self::IDENTIFIER_MESSAGES])) {
            $this->_data[self::IDENTIFIER_MESSAGES] = array();
        }
        if (!isset($this->_data[self::IDENTIFIER_MESSAGES][self::$_levels[$type]])) {
            $this->_data[self::IDENTIFIER_MESSAGES][self::$_levels[$type]] = array();
        }
        $this->_data[self::IDENTIFIER_MESSAGES][self::$_levels[$type]][] = $message;

        return $this;
    }

    public function addCritical($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::CRIT);
    }

    public function addError($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::ERR);
    }

    public function addWarning($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::WARN);
    }

    public function addSuccess($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::SUCCESS);
    }

    public function addInfo($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::INFO);
    }

    public function addDebug($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->addMessage(vsprintf($message, $args), self::DEBUG);
    }

    public function initFormKey()
    {
        $key = $this->getData('form_key');
        $this->setData('form_key', uniqid());

        return $key;
    }

    public function getFormKey($reset = true)
    {
        if (!$this->getData('form_key')) {
            $this->initFormKey();
            return $this->getData('form_key');
        }

        if ($reset === true) {
            $this->initFormKey();
        }

        return $this->getData('form_key');
    }

    public function setFormData(Array $data = array())
    {
        if (isset($data['__formkey'])) {
            unset($data['__formkey']);
        }

        $this->setData('form_data', $data);

        return $this;
    }

    public function getFormData($key = null, $default = null)
    {
        $data = $this->getData('form_data');
        if ($key === null) {
            return $data;
        } else if (isset($data[$key])) {
            return $data[$key];
        }
        return $default;
    }
}