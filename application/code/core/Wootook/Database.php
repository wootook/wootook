<?php

class Wootook_Database
    extends PDO
{
    const DEFAULT_CONNECTION_NAME = 'default';

    protected static $_connections = array();
    protected static $_connectionAliases = array();

    protected $_tablePrefix = null;

    public static $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

    public static function getSingleton()
    {
        return self::getConnection(self::DEFAULT_CONNECTION_NAME);
    }

    public static function getConnection($connectionName)
    {
        if (empty($connectionName) || $connectionName === null) {
            return null;
        }

        if (!isset(self::$_connections[$connectionName])) {
            if (isset(self::$_connectionAliases[$connectionName])) {
                return self::$_connectionAliases[$connectionName];
            }

            if ($alias = Wootook::getConfig("global/database/{$connectionName}/use")) {
                self::$_connectionAliases[$connectionName] = self::getConnection($alias);
            }

            self::$_connections[$connectionName] = self::_initConnection($connectionName);
        }

        return self::$_connections[$connectionName];
    }

    private static function _initConnection($connectionName)
    {
        $hostname = Wootook::getConfig("global/database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("global/database/{$connectionName}/params/username");
        $password = Wootook::getConfig("global/database/{$connectionName}/params/password");
        $database = Wootook::getConfig("global/database/{$connectionName}/params/database");

        if (empty($hostname) || empty($username) || empty($database)) {
            return null;
        }

        if (!($port = Wootook::getConfig("global/database/{$connectionName}/params/port")) || !is_numeric($port)) {
            $port = 3306;
        }

        $event = Wootook::dispatchEvent('database.prepare-options', array(
            'name'    => $connectionName,
            'options' => array_merge(self::$options, Wootook::getConfig("global/database/{$connectionName}/options"))
            ));

        $options = $event->getData('options');

        $connection = new self("mysql:dbname={$database};host={$hostname};port={$port}", $username, $password, $options);

        if (($prefix = Wootook::getConfig("global/database/{$connectionName}/table_prefix")) !== null) {
            $connection->setTablePrefix($prefix);
        }

        Wootook::dispatchEvent('database.init', array(
            'name'    => $connectionName,
            'handler' => $connection
            ));

        return $connection;
    }

    public function setTablePrefix($prefix)
    {
        $this->_tablePrefix = $prefix;

        return $this;
    }

    public function getTablePrefix()
    {
        return $this->_tablePrefix;
    }

    public function getTable($name)
    {
        return $this->getTablePrefix() . $name;
    }
}