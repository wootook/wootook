<?php

class Wootook_Core_Email
{
    /**
     * @var Wootook_Core_Email_Transport_Transport
     */
    protected $_transport = null;

    public function __construct(Wootook_Core_Email_Transport_Transport $transport = null)
    {
        if ($transport === null) {
            $transport = new Wootook_Core_Email_Transport_Sendmail();
        }
        $this->setTransport($transport);
    }

    public function setTransport(Wootook_Core_Email_Transport_Transport $transport)
    {
        $this->_transport = $transport;
    }

    /**
     * @return Wootook_Core_Email_Transport_Transport
     */
    public function getTransport()
    {
        return $this->_transport;
    }

    public function send($to, $from, $subject, $body, Array $headers = array())
    {
        try {
            $this->_transport
                ->addRecipient($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->addPart(new Wootook_Core_Email_Part_Part($body))
                ->addHeaders($headers)
                ->connect()
                ->send()
            ;
        } catch (Wootook_Core_Exception_RuntimeException $e) {
            throw new Wootook_Core_Exception_RuntimeException('Could not send mail.', null, $e);
        }

        $this->_transport->disconnect();

        return $this;
    }
}