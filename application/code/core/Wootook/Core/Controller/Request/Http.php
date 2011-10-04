<?php

class Wootook_Core_Controller_Request_Http
    extends Wootook_Object
{
    public function __construct()
    {
        parent::__construct(array());
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

    public function isPost()
    {
        if (strtoupper($this->getServer('REQUEST_METHOD')) == 'POST') {
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
}