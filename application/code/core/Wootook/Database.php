<?php

class Wootook_Database
    extends PDO
{
    const DEFAULT_CONNECTION_NAME = 'default';

    protected static $_connections = array();
    protected static $_connectionAliases = array();

    protected $_tablePrefix = null;
    protected $_dsn = null;
    protected $_username = null;
    protected $_password = null;

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

            if ($alias = Wootook::getConfig("database/{$connectionName}/use")) {
                self::$_connectionAliases[$connectionName] = self::getConnection($alias);

                return self::$_connectionAliases[$connectionName];
            }

            self::$_connections[$connectionName] = self::_initConnection($connectionName);
        }

        return self::$_connections[$connectionName];
    }

    private static function _initConnection($connectionName)
    {
        $hostname = Wootook::getConfig("database/{$connectionName}/params/hostname");
        $username = Wootook::getConfig("database/{$connectionName}/params/username");
        $password = Wootook::getConfig("database/{$connectionName}/params/password");
        $database = Wootook::getConfig("database/{$connectionName}/params/database");

        if (empty($hostname) || empty($username) || empty($database)) {
            return null;
        }

        $dsn = "mysql:dbname={$database};host={$hostname}";
        if (($port = Wootook::getConfig("database/{$connectionName}/params/port")) && is_numeric($port)) {
            $dsn .= ";port={$port}";
        }

        $event = Wootook::dispatchEvent('database.prepare-options', array(
            'name'    => $connectionName,
            'options' => array_merge(self::$options, Wootook::getConfig("database/{$connectionName}/options")->toArray())
            ));

        $options = $event->getData('options');

        $connection = new self($dsn, $username, $password, $options);
        $connection->_dsn = $dsn;
        $connection->_username = $username;
        $connection->_password = $password;

        if (($prefix = Wootook::getConfig("database/{$connectionName}/table_prefix")) !== null) {
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