<?php

class Wootook_Empire_Block_Planet_Buildings_Item
    extends Wootook_Empire_Block_Planet_Builder_ItemAbstract
{
    public function getLevel()
    {
        return $this->getPlanet()->getElement($this->getItemId());
    }

    public function getResourcesNeeded($level)
    {
        return $this->getPlanet()->getResourcesNeeded($this->getItemId(), $level);
    }

    public function isBuildable($level = null)
    {
        if ($level === null) {
            $level = $this->getNextLevel();
        }
        $resources = $this->getResourcesNeeded($level);

        foreach ($resources as $resourceId => $resourceValue) {
            $amount = $this->getPlanet()->getResourceAmount($resourceId);
            if (Math::comp($amount, $resourceValue) < 0) {
                return false;
            }
        }
        return true;
    }

    public function getQueuedLevel()
    {
        return $this->getPlanet()->getBuildingLevelQueued($this->getItemId());
    }

    public function getBuildingTime($level)
    {
        return $this->getPlanet()->getBuildingTime($this->getItemId(), $level);
    }

    public function getResourcesConfigForLevel($level)
    {
        $resources = $this->getResourcesNeeded($level);

        $resourceConfig = array();
        foreach ($resources as $resourceId => $resourceValue) {
            $resourceConfig[$resourceId] = new Wootook_Object(array(
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