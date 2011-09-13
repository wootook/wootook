<?php

/**
 *
 * Enter description here ...
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 */
class Legacies_Core_Model_Session
    extends Legacies_Object
{
    const CRIT    = 0x80;
    const ERROR   = 0x40;
    const WARN    = 0x20;
    const INFO    = 0x10;
    const DEBUG   = 0x01;
    const SUCCESS = 0;

    protected static $_instances = null;

    protected static $_levels = null;

    public static function factory($namespace)
    {
        $namespace = (string) $namespace;
        if (!isset(self::$_instances[$namespace])) {
            if (self::$_levels === null) {
                $reflection = new ReflectionClass(__CLASS__);
                self::$_levels = array_flip($reflection->getConstants());
            }

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
            session_start();
        }

        if (!isset($_SESSION[$namespace])) {
            $_SESSION[$namespace] = array();
        }

        $this->_data = &$_SESSION[$namespace];
        $this->_data['messages'] = array();
    }

    public function getMessages($clear = true)
    {
        $messages = $this->_data['messages'];
        if ($clear == true) {
            $this->_data['messages'] = array();
        }
        return $messages;
    }

    public function addMessage($message, $type = self::DEBUG)
    {
        if (!isset(self::$_levels[$type])) {
            $type = self::DEBUG;
        }

        if (!isset($this->_data['messages'])) {
            $this->_data['messages'] = array();
        }
        if (!isset($this->_data['messages'][self::$_levels[$type]])) {
            $this->_data['messages'][self::$_levels[$type]] = array();
        }
        $this->_data['messages'][self::$_levels[$type]][] = $message;

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

        return $this->addMessage(vsprintf($message, $args), self::ERROR);
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

    public function getFormKey($reset = false)
    {
        if (!$this->getData('form_key')) {
            $this->setData('form_key', uniqid());
            return $this->getData('form_key');
        }

        $key = $this->getData('form_key');
        if ($reset == true) {
            $this->setData('form_key', uniqid());
        }

        return $key;
    }
}