<?php

class Legacies_Empire_Block_Planet_Shipyard_Queue
    extends Legacies_Empire_Block_Planet_Builder_QueueAbstract
{
    public function getQueue()
    {
        return $this->getPlanet()->getShipyard()->getQueue();
    }

    public function isEmpty()
    {
        return $this->getQueue()->count() == 0;
    }
}