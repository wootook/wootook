<?php

class Legacies_Empire_Block_Planet_Shipyard_Item
    extends Legacies_Empire_Block_Planet_Builder_ItemAbstract
{
    public function getQty()
    {
        return $this->getPlanet()->getElement($this->getItemId());
    }

    public function getResourcesNeeded($qty)
    {
        return $this->getPlanet()->getShipyard()->getResourcesNeeded($this->getItemId(), $qty);
    }

    public function getBuildingTime($qty)
    {
        return $this->getPlanet()->getShipyard()->getBuildingTime($this->getItemId(), $qty);
    }

    public function getMaximumBuildableElementsCount()
    {
        return $this->getPlanet()->getShipyard()->getMaximumBuildableElementsCount($this->getItemId());
    }

    public function getResourcesConfigForQty($qty)
    {
        $resources = $this->getResourcesNeeded($qty);

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
}