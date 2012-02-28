<?php

class Wootook_Core_Email
{
    protected static $_defaultHeadrs = array(
        );

    protected $_headers = array();

    public function send($to, $from, $subject, $body, Array $headers = array())
    {
        $this->_prepareHeaders($headers);
    }

    public function setHeader($name, $value)
    {
        $this->_headers[$name] = $value;
    }

    protected function _prepareHeaders($headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        $this->addHeader('X-Mailer', 'PHP/' . PHP_VERSION . ' Wootook/' . VERSION);
        $this->addHeader('Content-Transfer-Encoding', '7bit');
        $this->addHeader('Content-Type', 'text/plain; charset=utf-8');

        return $this;
    }
}