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

use Wootook\Core\Database\Adapter,
    Wootook\Core\Database\Sql\Placeholder,
    Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Statement;

abstract class Condition
{
    protected $_query = null;
    protected $_placeholders = array();

    abstract public function render();

    public function __toString()
    {
        return (string) $this->render();
    }

    abstract public function reset();

    public function setQuery(Dml\Dml $query)
    {
        $this->_query = $query;

        return $this;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    public function addPlaceholder(Placeholder\Placeholder $placeholder)
    {
        $this->_placeholders[] = $placeholder;

        return $this;
    }

    public function clearPlaceholders()
    {
        $this->_placeholders = array();

        return $this;
    }

    public function getAllPlaceholders()
    {
        return $this->_placeholders;
    }

    public function beforePrepare(Statement\Statement $statement)
    {
        foreach ($this->getAllPlaceholders() as $placeholder) {
            /** @var \Wootook\Core\Database\Sql\Placeholder\Placeholder $placeholder */
            $placeholder->beforePrepare($statement);
        }

        return $this;
    }

    public function afterPrepare(Statement\Statement $statement)
    {
        foreach ($this->getAllPlaceholders() as $placeholder) {
            /** @var \Wootook\Core\Database\Sql\Placeholder\Placeholder $placeholder */
            $placeholder->afterPrepare($statement);
        }

        return $this;
    }

    public function beforeExecute(Statement\Statement $statement)
    {
        foreach ($this->getAllPlaceholders() as $placeholder) {
            /** @var \Wootook\Core\Database\Sql\Placeholder\Placeholder $placeholder */
            $placeholder->beforeExecute($statement);
        }

        return $this;
    }

    public function afterExecute(Statement\Statement $statement)
    {
        foreach ($this->getAllPlaceholders() as $placeholder) {
            /** @var \Wootook\Core\Database\Sql\Placeholder\Placeholder $placeholder */
            $placeholder->afterExecute($statement);
        }

        return $this;
    }

}
