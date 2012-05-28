<?php

class Wootook_Core_Mvc_Controller_Request_Http
    extends Wootook_Core_Mvc_Controller_Request_Request
{
    protected $_baseUrl = null;

    protected $_actionKey = 'action';
    protected $_controllerKey = 'controller';
    protected $_moduleKey = 'module';

    protected $_queryVars = array();
    protected $_postVars = array();
    protected $_cookieVars = array();
    protected $_serverVars = array();
    protected $_postFiles = array();

    public function __construct(Array $options = array())
    {
        parent::__construct($options);

        $this->_baseUrl = Wootook::getBaseUrl('link');
        if ($this->_baseUrl === null) {
            $this->_baseUrl = 'http://' . $this->getServer('HTTP_HOST') . $this->getServer('REQUEST_URI');
        }
        $offset = strpos($this->_baseUrl, '/', 8);
        if ($offset !== false) {
            $baseUri = substr($this->_baseUrl, $offset); // Get the path from the DocumentRoot
        } else {
            $baseUri = '/';
        }

        $params = '';
        if (($offset = strpos($this->getServer('REQUEST_URI'), $baseUri)) !== false) {
            $queryParamsOffset = strpos($this->getServer('REQUEST_URI'), '?');
            if ($queryParamsOffset !== false) {
                $path = substr($this->getServer('REQUEST_URI'), $offset + strlen($baseUri), $queryParamsOffset - $offset - 1);
            } else {
                $path = substr($this->getServer('REQUEST_URI'), $offset + strlen($baseUri));
            }

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

    public function init()
    {
        $this->_queryVars = $_GET;
        $this->_postVars = $_POST;
        $this->_cookieVars = $_COOKIE;
        $this->_serverVars = $_SERVER;
        $this->_postFiles = $_FILES;

        $this->clearData();

        $this->addData($this->_serverVars);
        $this->addData($this->_cookieVars);
        $this->addData($this->_queryVars);
        $this->addData($this->_postVars);
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
        if ($this->hasPost($key)) {
            return $this->getPost($key);
        }
        if ($this->hasQuery($key)) {
            return $this->getQuery($key);
        }
        if ($this->hasCookie($key)) {
            return $this->getCookie($key);
        }

        return $this->getServer($key, $default);
    }

    public function getQuery($key, $default = null)
    {
        if (!isset($this->_queryVars[$key])) {
            return $default;
        }
        return $this->_queryVars[$key];
    }

    public function getAllQueryData()
    {
        return $this->_queryVars;
    }

    public function hasQuery($key)
    {
        return isset($this->_queryVars[$key]);
    }

    public function setQuery($key, $value)
    {
        $this->_queryVars[$key] = (string) $value;

        return $this;
    }

    public function unsetQuery($key)
    {
        unset($this->_queryVars[$key]);

        return $this;
    }

    public function getPost($key, $default = null)
    {
        if (!isset($this->_postVars[$key])) {
            return $default;
        }
        return $this->_postVars[$key];
    }

    public function getAllPostData()
    {
        return $this->_postVars;
    }

    public function hasPost($key)
    {
        return isset($this->_postVars[$key]);
    }

    public function setPost($key, $value)
    {
        $this->_postVars[$key] = (string) $value;

        return $this;
    }

    public function unsetPost($key)
    {
        unset($this->_postVars[$key]);

        return $this;
    }

    public function getCookie($key, $default = null)
    {
        if (!isset($this->_cookieVars[$key])) {
            return $default;
        }
        return unserialize(stripslashes($this->_cookieVars[$key]));
    }

    public function getRawCookie($key, $default = null)
    {
        if (!isset($this->_cookieVars[$key])) {
            return $default;
        }
        return $this->_cookieVars[$key];
    }

    public function getAllCookieData()
    {
        $data = array();
        foreach ($this->_cookieVars as $key => $value) {
            $data[$key] = unserialize(stripslashes($value));
        }
        return $data;
    }

    public function getAllRawCookieData()
    {
        return $this->_cookieVars;
    }

    public function hasCookie($key)
    {
        return isset($this->_cookieVars[$key]);
    }

    public function setCookie($key, $value)
    {
        $this->_cookieVars[$key] = addslashes(serialize((string) $value));

        return $this;
    }

    public function setRawCookie($key, $value)
    {
        $this->_cookieVars[$key] = (string) $value;

        return $this;
    }

    public function unsetCookie($key)
    {
        unset($this->_cookieVars[$key]);

        return $this;
    }

    public function getServer($key, $default = null)
    {
        if (!isset($this->_serverVars[$key])) {
            return $default;
        }
        return $this->_serverVars[$key];
    }

    public function getAllServerData()
    {
        return $this->_serverVars;
    }

    public function getFile($key, $default = null)
    {
        if (!isset($this->_postFiles[$key])) {
            return $default;
        }
        return $this->_postFiles[$key];
    }

    public function getAllFilesData()
    {
        return $this->_postFiles;
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
