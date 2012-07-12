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

class Wootook_Empire_Block_Overview_Fleet_List
    extends Wootook_Core_Block_Template
{
    protected $_itemTemplate = null;
    protected $_itemBlockType = null;

    protected $_fleetCollection = null;

    public function getFleetCollection()
    {
        if ($this->_fleetCollection === null) {
            $player = Wootook_Player_Model_Session::getSingleton()->getPlayer();
            $this->_fleetCollection = $player->getVisibleFleets();
        }
        return $this->_fleetCollection;
    }

    public function setItemTemplate($template)
    {
        $this->_itemTemplate = $template;

        return $this;
    }

    public function getItemTemplate()
    {
        return $this->_itemTemplate;
    }

    public function setItemBlockType($blockType)
    {
        $this->_itemBlockType = $blockType;

        return $this;
    }

    public function getItemBlockType()
    {
        return $this->_itemBlockType;
    }

    public function getItemBlock($fleet)
    {
        $blockName = $this->getNameInLayout() . ".item({$fleet->getId()})";

        return $this->getLayout()
            ->createBlock($this->getItemBlockType(), $blockName)
            ->setTemplate($this->getItemTemplate())
            ->setFleetItem($fleet);
    }

    public function prepareLayout()
    {
        parent::prepareLayout();

        $this->_initChildBlocks();

        return $this;
    }

    public function _initChildBlocks()
    {
        $parentBlock = $this->getLayout()
            ->createBlock('core/concat', $this->getNameInLayout() . '.item-list')
        ;
        $this->setPartial('item-list', $parentBlock);

        foreach ($this->getFleetCollection()->load() as $fleet) {
            $block = $this->getItemBlock($fleet);
            $parentBlock->setPartial($block->getNameInLayout(), $block);
        }

        return $this;
    }
}
