<?php

class Wootook_Core_Email_Transport_Sendmail
    implements Wootook_Core_Email_Transport_Transport
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

    public function addPart(Wootook_Core_Email_Part_Part $part)
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
            throw new Wootook_Core_Exception_RuntimeException('Could not send mail.');
        }

        return $this;
    }
}