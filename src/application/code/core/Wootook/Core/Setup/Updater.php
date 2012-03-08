<?php

class Wootook_Core_Setup_Updater
{
    protected $_setupConnection = null;

    /**
     *
     * Enter description here ...
     * @param unknown_type $connection
     * @return Wootook_Core_Setup_Updater
     */
    public function setSetupConnection($connection)
    {
        if (is_string($connection)) {
            $this->_setupConnection = Wootook_Core_Database_ConnectionManager::getSingleton()
                ->getConnection($connection);
        } else {
            $this->_setupConnection = $connection;
        }

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Core_Database_Adapter_Pdo_Mysql
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
                throw new Wootook_Core_Setup_Exception_RuntimeException(
                    Wootook::__('Query failed: SQLSTATE %s: %s.', $info[1], $info[2]));
            }
        } catch (PDOException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);
            throw new Wootook_Core_Setup_Exception_RuntimeException($e->getMessage(), null, $e);
        }
    }

    public function grant($tableName, $connectionName, $perms = null)
    {
        $hostname = Wootook::getConfig("database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("database/{$connectionName}/params/username");
        $database = Wootook::getConfig("database/{$connectionName}/params/database");
        $password = Wootook::getConfig("database/{$connectionName}/params/password");

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
        $hostname = Wootook::getConfig("database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("database/{$connectionName}/params/username");
        $database = Wootook::getConfig("database/{$connectionName}/params/database");
        $password = Wootook::getConfig("database/{$connectionName}/params/password");

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
        return Wootook::getConfig($path);
    }

    public function getWebsiteConfig($path = null, $websiteId = null)
    {
        return Wootook::getWebsiteConfig($path, $websiteId);
    }

    public function getGameConfig($path = null, $gameId = null)
    {
        return Wootook::getGameConfig($path, $gameId);
    }
}
