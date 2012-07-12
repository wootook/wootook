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

namespace Wootook\Core\Database\Resource;

use Wootook\Core,
    Wootook\Core\Database,
    Wootook\Core\Database\Adapter as DatabaseAdapter,
    Wootook\Core\Exception as CoreException;

/**
 *
 */
trait ReadConnection
{
    protected $_readConnection = null;

    /**
     * @return DatabaseAdapter\Adapter
     */
    public function getReadConnection()
    {
        if ($this->_readConnection === null) {
            $this->_readConnection = $this->app()->getConnectionManager()
                ->getConnection('core_read');
        }

        return $this->_readConnection;
    }

    public function setReadConnection($connection)
    {
        if ($connection instanceof DatabaseAdapter\Adapter) {
            $this->_readConnection = $connection;
        } else if (is_string($connection)) {
            $this->_readConnection = $this->app()->getConnectionManager()
                ->getConnection($connection);
        } else {
            throw new CoreException\RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }
}
