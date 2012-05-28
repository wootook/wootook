<?php

interface Wootook_Core_Email_Transport_Transport
{
    public function setFrom($from);
    public function addRecipient($recipient);
    public function setSubject($subject);
    public function addHeader($name, $value);
    public function addPart(Wootook_Core_Email_Part_Part $part);

    public function reset();
    public function clearFrom();
    public function clearHeaders();
    public function clearRecipients();
    public function clearSubject();
    public function clearParts();

    public function connect();
    public function disconnect();

    public function send();
}