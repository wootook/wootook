<?php

class Wootook_Core_Controller_Request_Http
    extends Wootook_Core_Controller_Request_Abstract
{
    protected $_actionKey = 'action';
    protected $_controllerKey = 'controller';
    protected $_moduleKey = 'module';

    protected $_isDispatched = false;

    public function setParam($key, $value)
    {
        return $this->setData($key, $value);
    }

    public function getParam($key, $default = null)
    {
        if ($this->hasData($key)) {
            return $this->getData($key);
        }
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        return $default;
    }

    public function getQuery($key, $default = null)
    {
        if (!isset($_GET[$key])) {
            return $default;
        }
        return $_GET[$key];
    }

    public function getFile($key, $default = null)
    {
        if (!isset($_FILES[$key])) {
            return $default;
        }
        return $_FILES[$key];
    }

    public function getCookie($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            return $default;
        }
        return unserialize(stripslashes($_COOKIE[$key]));
    }

    public function getRawCookie($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            return $default;
        }
        return $_COOKIE[$key];
    }

    public function getPost($key, $default = null)
    {
        if (!isset($_POST[$key])) {
            return $default;
        }
        return $_POST[$key];
    }

    public function getServer($key, $default = null)
    {
        if (!isset($_SERVER[$key])) {
            return $default;
        }
        return $_SERVER[$key];
    }

    public function getAllQueryData()
    {
        return $_GET;
    }

    public function getAllFilesData()
    {
        return $_FILES;
    }

    public function getAllCookieData()
    {
        $data = array();
        foreach ($_COOKIE as $key => $value) {
            $data[$key] = unserialize(stripslashes($value));
        }
        return $data;
    }

    public function getAllRawCookieData()
    {
        return $_COOKIE;
    }

    public function getAllPostData()
    {
        return $_POST;
    }

    public function getAllServerData()
    {
        return $_SERVER;
    }

    public function isPost()
    {
        if (strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
            return true;
        }
        return false;
    }

    public function isPut()
    {
        if (strtoupper($this->getServer('REQUEST_METHOD')) == 'PUT') {
            return true;
        }
        return false;
    }

    public function isHead()
    {
        if (strtoupper($this->getServer('REQUEST_METHOD')) == 'HEAD') {
            return true;
        }
        return false;
    }

    public function isGet()
    {
        if (strtoupper($this->getServer('REQUEST_METHOD')) == 'GET') {
            return true;
        }
        return false;
    }

    public function getModuleKey()
    {
        return $this->_moduleKey;
    }

    public function setModuleKey($key)
    {
        $this->_moduleKey = $key;

        return $this;
    }

    public function getModuleName()
    {
        return $this->getParam($this->getModuleKey());
    }

    public function setModuleName($name)
    {
        return $this->setParam($this->getModuleKey(), $name);
    }

    public function getControllerKey()
    {
        return $this->_controllerKey;
    }

    public function setControllerKey($key)
    {
        $this->_controllerKey = $key;

        return $this;
    }

    public function getControllerName()
    {
        return $this->getParam($this->getControllerKey(), 'index');
    }

    public function setControllerName($name)
    {
        return $this->setParam($this->getControllerKey(), $name);
    }

    public function getActionKey()
    {
        return $this->_actionKey;
    }

    public function setActionKey($key)
    {
        $this->_actionKey = $key;

        return $this;
    }

    public function getActionName()
    {
        return $this->getParam($this->getActionKey(), 'index');
    }

    public function setActionName($name)
    {
        return $this->setParam($this->getActionKey(), $name);
    }

    public function setIsDispatched($dispatched = true)
    {
        $this->_isDispatched = $dispatched;

        return $this;
    }

    public function isDispatched()
    {
        return $this->_isDispatched;
    }
}