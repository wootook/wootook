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

    public function quoteIdentifier($identifier)
    {
        return "`$identifier`";
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

            $connectionConfig = Wootook::getConfig("resource/database/{$connectionName}");
            if ($alias = $connectionConfig->use) {
                self::$_connectionAliases[$connectionName] = self::getConnection($alias);

                return self::$_connectionAliases[$connectionName];
            }

            self::$_connections[$connectionName] = self::_initConnection($connectionName,
                $connectionConfig->engine, $connectionConfig->params, $connectionConfig->options);
        }

        return self::$_connections[$connectionName];
    }

    private static function _initConnection($connectionName, $engine, $params, $options = array())
    {
        if (is_array($params)) {
            $params = new Wootook_Core_Config_Node($params);
        } else if (!$params instanceof Wootook_Core_Config_Node) {
            $params = new Wootook_Core_Config_Node(array());
        }

        if ($options instanceof Wootook_Core_Config_Node) {
            $options = $options->toArray();
        } else  if (!is_array($options)) {
            $options = array();
        }

        $hostname = $params->hostname;
        $username = $params->username;
        $password = $params->password;
        $database = $params->database;

        if (empty($params->hostname) || empty($params->username) || empty($params->database)) {
            return null;
        }

        $dsn = "mysql:dbname={$params->database};host={$params->hostname}";
        if (is_numeric($params->port)) {
            $dsn .= ";port={$params->port}";
        }

        $event = Wootook::dispatchEvent('database.prepare-options', array(
            'name'    => $connectionName,
            'options' => array_merge(self::$options, $options)
            ));

        $options = $event->getData('options');

        $connection = new self($dsn, $params->username, $params->password, $options);
        $connection->_dsn = $dsn;
        $connection->_username = $username;

        if (defined('DEBUG')) {
            $connection->_password = $password;
        }

        if (($prefix = Wootook::getConfig("resource/database/{$connectionName}/table_prefix")) !== null) {
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