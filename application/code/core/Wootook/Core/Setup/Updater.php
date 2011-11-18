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
            $this->_setupConnection = Wootook_Database::getConnection($connection);
        } else {
            $this->_setupConnection = $connection;
        }

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Database
     */
    public function getSetupConnection()
    {
        if ($this->_setupConnection === null) {
            return Wootook_Database::getSingleton();
        }
        return $this->_setupConnection;
    }

    public function getTable($tableName)
    {
        $this->getSetupConnection()->getTable($tableName);
    }

    public function grant($tableName, $connectionName, $perms = null)
    {
        $hostname = Wootook::getConfig("global/database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("global/database/{$connectionName}/params/username");
        $database = Wootook::getConfig("global/database/{$connectionName}/params/database");
        $password = Wootook::getConfig("global/database/{$connectionName}/params/password");

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
        $hostname = Wootook::getConfig("global/database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("global/database/{$connectionName}/params/username");
        $database = Wootook::getConfig("global/database/{$connectionName}/params/database");
        $password = Wootook::getConfig("global/database/{$connectionName}/params/password");

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

    public function getGameConfig($path)
    {
        $config = Wootook_Core_Model_Config::getSingleton();

        return $config->getData($path);
    }
}
