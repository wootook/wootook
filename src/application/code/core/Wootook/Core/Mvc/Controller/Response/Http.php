<?php

class Wootook_Core_Mvc_Controller_Response_Http
    extends Wootook_Core_Mvc_Controller_Response_Response
{
    const STATUS_OK = 200;

    const REDIRECT_MOVED_PERMANENTLY = 301;
    const REDIRECT_FOUND             = 302;
    const REDIRECT_SEE_OTHER         = 303;
    const REDIRECT_TEMPORARY         = 307;

    protected static $_redirectCodes = array(
        self::REDIRECT_MOVED_PERMANENTLY => 'Moved Permanently',
        self::REDIRECT_FOUND             => 'Found',
        self::REDIRECT_SEE_OTHER         => 'See Other',
        self::REDIRECT_TEMPORARY         => 'Temporary Redirect'
        );

    protected $_returnCode = self::STATUS_OK;

    public function setBody($data)
    {
        $this->clearBody();

        return $this->appendBody($data);
    }

    public function appendBody($data)
    {
        if (!isset($this->_data['body']) || !is_array($this->_data['body'])) {
            $this->clearBody();
        }
        $this->_data['body'][] = $data;

        return $this;
    }

    public function clearBody()
    {
        $this->_data['body'] = array();

        return $this;
    }

    public function sendBody()
    {
        if (!isset($this->_data['body']) || empty($this->_data['body'])) {
            return '';
        }
        return implode('', $this->_data['body']);
    }

    public function clearHeaders()
    {
        $this->_data['headers'] = array();

        return $this;
    }

    public function clearRawHeaders()
    {
        $this->_data['raw_headers'] = array();

        return $this;
    }

    public function clearAllHeaders()
    {
        $this->clearHeaders();
        $this->clearRawHeaders();

        return $this;
    }

    public function setHeader($name, $value)
    {
        if (!isset($this->_data['headers']) || !is_array($this->_data['headers'])) {
            $this->clearHeaders();
        }
        $this->_data['headers'][$name] = $value;

        return $this;
    }

    public function setRawHeader($value)
    {
        if (!isset($this->_data['raw_headers']) || !is_array($this->_data['raw_headers'])) {
            $this->clearRawHeaders();
        }
        $this->_data['raw_headers'][] = $value;

        return $this;
    }

    public function sendHeaders()
    {
        if (isset($this->_data['raw_headers'])) {
            foreach ($this->_data['raw_headers'] as $header) {
                header($header);
            }
        }
        if (isset($this->_data['headers'])) {
            foreach ($this->_data['headers'] as $headerName => $headerValue) {
                header("{$headerName}: {$headerValue}");
            }
        }
        $this->clearAllHeaders();

        return $this;
    }

    public function isRedirect()
    {
        return $this->_returnCode >= 300 && $this->_returnCode < 400;
    }

    public function setCookie($name, $value, $lifetime = null, $path = null, $domain = null)
    {
        return $this->setRawCookie($name, serialize($value), $lifetime, $path, $domain);
    }

    public function unsetCookie($name, $path = null, $domain = null)
    {
        return $this->setRawCookie($name, null, 0, $path, $domain);
    }

    public function setRawCookie($name, $value, $lifetime = null, $path = null, $domain = null)
    {
        $date = new Wootook_Core_DateTime();
        $date->add($lifetime);

        setcookie($name, $value, $date->getTimestamp(), $path, $domain);
        return $this;
    }

    public function setRedirect($url, $code = self::REDIRECT_FOUND)
    {
        if (!in_array($code, self::$_redirectCodes)) {
            $code = self::REDIRECT_FOUND;
        }

        $this->_returnCode = $code;

        $statusText = self::$_redirectCodes[$code];
        $this->setRawHeader("HTTP/1.1 {$code} {$statusText}")
            ->setHeader('Location', $url);

        return $this;
    }

    public function render($send = true)
    {
        if ($send) {
            $this->sendHeaders();
            echo $this->sendBody();
        } else {
            return $this->sendBody();
        }
    }
}
