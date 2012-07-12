<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

namespace Wootook\Core\Mvc\Controller\Response;

use Wootook\Core;

class Http
    extends Response
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
        $date = new Core\DateTime();
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
