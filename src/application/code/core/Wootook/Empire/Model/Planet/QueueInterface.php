<?php

interface Wootook_Empire_Model_Planet_QueueInterface
{
    public function appendQueue($elementId, $qty, Wootook_Core_DateTime $time = null);
    public function updateQueue(Wootook_Core_DateTime $time = null);
    public function getBuilder();
    public function checkAvailability($elementId);
}