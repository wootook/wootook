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

namespace Wootook\Core\Setup;

use Wootook\Core\Profiler,
    Wootook\Core\Database,
    Wootook\Core\Database\Adapter as DatabaseAdapter,
    Wootook\Core\Setup\Exception as SetupException;

class Updater
{
    protected $_setupConnection = null;

    /**
     *
     * Enter description here ...
     * @param unknown_type $connection
     * @return Updater
     */
    public function setSetupConnection($connection)
    {
        if (is_string($connection)) {
            $this->_setupConnection = Database\ConnectionManager::getSingleton()
                ->getConnection($connection);
        } else {
            $this->_setupConnection = $connection;
        }

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return DatabaseAdapter\Adapter
     */
    public function getSetupConnection()
    {
        if ($this->_setupConnection === null) {
            $this->setSetupConnection('default');
        }
        return $this->_setupConnection;
    }

    public function getTableName($tableName)
    {
        return $this->getSetupConnection()->getTable($tableName);
    }

    public function query($statement)
    {
        try {
            if (!$this->getSetupConnection()->query($statement)) {
                $info = $this->getSetupConnection()->errorInfo();
                throw new SetupException\RuntimeException(
                    \Wootook::__('Query failed: SQLSTATE %s: %s.', $info[1], $info[2]));
            }
        } catch (PDOException $e) {
            Profiler\ErrorProfiler::getSingleton()->exceptionManager($e);
            throw new SetupException\RuntimeException($e->getMessage(), null, $e);
        }
    }

    public function grant($tableName, $connectionName, $perms = null)
    {
        $hostname = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/hostname");
        $username = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/username");
        $database = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/database");
        $password = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/password");

        if ($perms === null) {
            $perms = 'ALL PRIVILEGES';
        } else if (is_array($perms)) {
            $perms = implode(' ', $perms);
        }

        $sql =<<<SQL_EOF
GRANT {$perms}
ON {$database}.{$this->getTable($tableName)}
TO '{$username}@{$hostname}' IDENTIFIED BY '$password'
SQL_EOF;

        $this->getSetupConnection()->query($sql);

        return $this;
    }

    public function revoke($tableName, $connectionName, $perms = null)
    {
        $hostname = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/hostname");
        $username = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/username");
        $database = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/database");
        $password = \Wootook::app()->getGlobalConfig("database/{$connectionName}/params/password");

        if ($perms === null) {
            $perms = 'ALL PRIVILEGES';
        } else if (is_array($perms)) {
            $perms = implode(' ', $perms);
        }

        $sql =<<<SQL_EOF
REVOKE {$perms}
ON {$database}.{$this->getTable($tableName)}
FROM '{$username}@{$hostname}'
SQL_EOF;

        $this->getSetupConnection()->query($sql);

        return $this;
    }

    public function run($script)
    {
        include $script;
    }

    public function getConfig($path = null)
    {
        return \Wootook::app()->getGlobalConfig($path);
    }

    public function getWebsiteConfig($path = null, $websiteId = null)
    {
        return \Wootook::app()->getWebsite($websiteId)->getConfig($path);
    }

    public function getGameConfig($path = null, $gameId = null)
    {
        return \Wootook::app()->getGame($gameId)->getConfig($path);
    }
}
