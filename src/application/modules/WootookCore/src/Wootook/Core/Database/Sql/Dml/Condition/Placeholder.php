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

namespace Wootook\Core\Database\Sql\Dml\Condition;

use Wootook\Core\Database\Sql\Placeholder as SqlPlaceholder;

class Placeholder
    extends Condition
{
    protected $_placeholder = null;

    public function __construct(SqlPlaceholder\Placeholder $placeholder)
    {
        $this->_placeholder = $placeholder;

        $this->_addPlaceholder($placeholder);
    }

    public function render()
    {
        return $this->_placeholder->toString();
    }

    public function reset()
    {
        $this->_placeholder = null;

        $this->_clearPlaceholders();

        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->_placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->_placeholder;
    }
}
