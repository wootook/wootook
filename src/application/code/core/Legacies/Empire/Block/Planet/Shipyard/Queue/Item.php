<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Legacies_Empire_Block_Planet_Shipyard_Queue_Item
    extends Wootook_Empire_Block_Planet_Builder_Queue_ItemAbstract
{
    protected $_itemIdField = 'ship_id';

    public function getQty()
    {
        return $this->getPlanet()->getElement($this->getItemId());
    }

    public function getResourcesNeeded($level)
    {
        return $this->getPlanet()->getShipyard()->getResourcesNeeded($this->getItemId(), 1);
    }

    public function getItemQueuedQty()
    {
        return $this->getItem()->getData('qty');
    }

    public function getBuildingTime($qty)
    {
        return $this->getPlanet()->getShipyard()->getBuildingTime($this->getItemId(), $qty);
    }

    public function getBuildingRemainingTime()
    {
        $totalTime = $this->getPlanet()->getShipyard()
            ->getBuildingTime($this->getItemId(), $this->getItemQueuedQty());
        $item = $this->getItem();

        return $totalTime - ($item->getData('created_at') - $item->getData('updated_at'));
    }

    public function getResourcesConfigForQty($qty)
    {
        $resources = $this->getResourcesNeeded($qty);

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
