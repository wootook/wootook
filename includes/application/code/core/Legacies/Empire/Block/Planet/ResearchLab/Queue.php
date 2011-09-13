<?php

class Legacies_Empire_Block_Planet_ResearchLab_Queue
    extends Legacies_Empire_Block_Planet_Builder_QueueAbstract
{
    public function getQueue()
    {
        return $this->getPlanet()->getResearchLab()->getBuilder();
    }

    public function isEmpty()
    {
        return $this->getQueue()->count() == 0;
    }
}