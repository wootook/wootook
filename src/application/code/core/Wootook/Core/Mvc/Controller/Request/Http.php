<?php

class Wootook_Core_Mvc_Controller_Request_Http
    extends Wootook_Core_Mvc_Controller_Request_Request
{
    protected $_baseUrl = null;

    protected $_actionKey = 'action';
    protected $_controllerKey = 'controller';
    protected $_moduleKey = 'module';

    public function __construct(Array $options = array())
    {
        parent::__construct($options);

        $this->_baseUrl = Wootook::getBaseUrl();
        $baseUri = substr($this->_baseUrl, strpos($this->_baseUrl, '/', 8)); // Get the path from the DocumentRoot

        $params = '';
        if (($offset = strpos($this->getServer('REQUEST_URI'), $baseUri)) !== false) {
            $path = substr($this->getServer('REQUEST_URI'), $offset + strlen($baseUri));

            $moduleOffset = strpos($path, '/');
            if ($moduleOffset !== false) {
                $this->setModuleName(substr($path, 0, $moduleOffset));

                $controllerOffset = strpos($path, '/', $moduleOffset + 1);
                if ($controllerOffset !== false) {
                    $this->setControllerName(substr($path, $moduleOffset + 1, $controllerOffset - $moduleOffset - 1));

                    $actionOffset = strpos($path, '/', $controllerOffset + 1);
                    if ($actionOffset !== false) {
                        $this->setActionName(substr($path, $controllerOffset + 1, $actionOffset - $controllerOffset - 1));

                        $params = substr($path, $actionOffset);
                    } else if ($controllerOffset !== false) {
                        $this->setActionName(substr($path, $controllerOffset + 1));
                    }
                } else if ($moduleOffset !== false) {
                    $this->setControllerName(substr($path, $moduleOffset + 1));
                }
            } else {
                $this->setModuleName($path);
            }
        }

        if (!empty($params)) {
            $paramOffset = 0;
            $valueOffset = 0;
            while (true) {
                $paramOffset = strpos($params, '/', $valueOffset + 1);
                if ($paramOffset === false) {
                    break;
                }
                $paramKey = substr($params, $valueOffset + 1, $paramOffset - $valueOffset - 1);

                $valueOffset = strpos($params, '/', $paramOffset + 1);
                if ($valueOffset === false) {
                    $this->setParam($paramKey, substr($params, $paramOffset + 1));
                    break;
                }
                $this->setParam($paramKey, substr($params, $paramOffset + 1, $valueOffset - $paramOffset - 1));
            }
        }
    }

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
        if ($name === null || is_string($name)) {
            return $this->setParam($this->getModuleKey(), $name);
        }
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
        return $this->getParam($this->getControllerKey());
    }

    public function setControllerName($name)
    {
        if ($name === null || is_string($name)) {
            return $this->setParam($this->getControllerKey(), $name);
        }
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
        return $this->getParam($this->getActionKey());
    }

    public function setActionName($name)
    {
        if ($name === null || is_string($name)) {
            return $this->setParam($this->getActionKey(), $name);
        }
    }
}