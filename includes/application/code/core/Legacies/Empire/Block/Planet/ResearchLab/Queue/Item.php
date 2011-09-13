<?php

class Legacies_Empire_Block_Planet_ResearchLab_Queue_Item
    extends Legacies_Empire_Block_Planet_Builder_Queue_ItemAbstract
{
    protected $_itemIdField = 'research_id';

    public function getLevel()
    {
        return $this->getUser()->getElement($this->getItemId());
    }

    public function getResourcesNeeded($level)
    {
        return $this->getPlanet()->getResearchLab()->getResourcesNeeded($this->getItemId(), $this->getQueuedLevel() + 1);
    }

    public function getItemQueuedLevel()
    {
        return $this->getItem()->getData('level');
    }

    public function getQueuedLevel()
    {
        return $this->getPlanet()->getResearchLab()->getResearchLevelQueued($this->getItemId());
    }

    public function getBuildingTime($level)
    {
        return $this->getPlanet()->getResearchLab()->getBuildingTime($this->getItemId(), $level);
    }

    public function getResourcesConfigForLevel($level)
    {
        $resources = $this->getResourcesNeeded($level);

        $resourceConfig = array();
        foreach ($resources as $resourceId => $resourceValue) {
            $resourceConfig[$resourceId] = new Legacies_Object(array(
                'resource_id'  => $resourceId,
                'value'        => $resourceValue
                ));

            $amount = $this->getPlanet()->getResourceAmount($resourceId);
            if (Math::comp($amount, $resourceValue) < 0) {
                $resourceConfig[$resourceId]->setData('requirement', Math::sub($amount, $resourceValue));
            } else {
                $resourceConfig[$resourceId]->setData('overflow', Math::sub($amount, $resourceValue));
            }
        }
        return $resourceConfig;
    }

    public function getResourcesConfigForNextLevel()
    {
        return $this->getResourcesConfigForLevel($this->getNextLevel());
    }

    public function getBuildingTimeForNextLevel()
    {
        return $this->getBuildingTime($this->getNextLevel());
    }
}