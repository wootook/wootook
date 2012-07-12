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

namespace Wootook\Core\Email\Transport;

use Wootook\Core\Email\Part,
    Wootook\Core\Exception as CoreException;

class Sendmail
    implements Transport
{
    protected static $_defaultHeaders = array();

    protected $_headers = array();
    protected $_parts = array();
    protected $_from = null;
    protected $_recipients = array();
    protected $_subject = array();

    public function __construct()
    {
        $this->_prepareHeaders();
    }

    public function setFrom($from)
    {
        if (is_array($from)) {
            $this->_from =  sprintf('"%s" <%s>', $this->_formatUnicode(current($from)), key($from));
        } else {
            $this->_from = $from;
        }
        return $this;
    }

    public function clearFrom()
    {
        $this->_from = null;

        return $this;
    }

    public function addRecipient($recipient)
    {
        if (is_array($recipient)) {
            foreach ($recipient as $email => $name) {
                $this->_recipients[] =  sprintf('"%s" <%s>', $this->_formatUnicode($name), $email);
            }
        } else {
            $this->_recipients[] = $recipient;
        }
        return $this;
    }

    public function clearRecipients()
    {
        $this->_recipients = array();

        return $this;
    }

    public function setSubject($subject)
    {
        $this->_subject = $this->_formatUnicode($subject);

        return $this;
    }

    public function clearSubject()
    {
        $this->_subject = null;

        return $this;
    }

    public function addHeader($name, $value)
    {
        $this->_headers[$name] = $value;

        return $this;
    }

    public function addHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            $this->_headers[$name] = $value;
        }

        return $this;
    }

    public function clearHeaders()
    {
        $this->_headers = array();

        return $this;
    }

    public function addPart(Part\Part $part)
    {
        $this->_parts[] = $part;

        return $this;
    }

    public function clearParts()
    {
        $this->_parts = array();

        return $this;
    }

    protected function _formatUnicode($string)
    {
        return '=?UTF-8?B?' . base64_encode($string) . '?=';
    }

    protected function _prepareHeaders(Array $headers = array())
    {
        $this->addHeader('MIME-Version', '1.0');

        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        $this->addHeader('X-Mailer', 'PHP/' . PHP_VERSION . ' Wootook/' . VERSION);
        $this->addHeader('Content-Transfer-Encoding', '8bit');
        $this->addHeader('Content-Type', 'text/plain; charset=utf-8; format=flowed');

        return $this;
    }

    public function reset()
    {
        $this->clearHeaders();
        $this->clearParts();
        $this->clearFrom();
        $this->clearRecipients();
        $this->clearSubject();

        return $this;
    }

    public function connect()
    {
        return $this;
    }

    public function disconnect()
    {
        return $this;
    }

    public function send()
    {
        $content = '';
        foreach ($this->_parts as $part) {
            $content .= $part->render();
        }

        $this->addHeader('To', implode(',', $this->_recipients));

        if (!mail(implode(',', $this->_recipients), $this->_subject, $content, implode("\r\n", $this->_headers))) {
            throw new CoreException\RuntimeException('Could not send mail.');
        }

        return $this;
    }
}
