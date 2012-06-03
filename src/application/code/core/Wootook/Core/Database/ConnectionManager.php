<?php

class Wootook_Core_Database_ConnectionManager
    extends Wootook_Core_PluginLoader_PluginLoader
    implements Wootook_Core_Singleton
{
    const PROFILER                      = 'profiler';
    const CASE_FOLDING                  = 'case-folding';
    const AUTO_QUOTE_IDENTIFIERS        = 'auto-quote-identifiers';
    const ALLOW_SERIALIZATION           = 'allow-serialization';
    const AUTO_RECONNECT_ON_UNSERIALIZE = 'auto-reconnect-on-unserialize';

    const INT_TYPE    = 0;
    const BIGINT_TYPE = 1;
    const FLOAT_TYPE  = 2;

    const ATTR_AUTOCOMMIT          = 0;
    const ATTR_PREFETCH            = 1;
    const ATTR_TIMEOUT             = 2;
    const ATTR_ERRMODE             = 3;
    const ATTR_SERVER_VERSION      = 4;
    const ATTR_CLIENT_VERSION      = 5;
    const ATTR_SERVER_INFO         = 6;
    const ATTR_CONNECTION_STATUS   = 7;
    const ATTR_CASE                = 8;
    const ATTR_CURSOR_NAME         = 9;
    const ATTR_CURSOR              = 10;
    const ATTR_ORACLE_NULLS        = 11;
    const ATTR_PERSISTENT          = 12;
    const ATTR_STATEMENT_CLASS     = 13;
    const ATTR_FETCH_TABLE_NAMES   = 14;
    const ATTR_FETCH_CATALOG_NAMES = 15;
    const ATTR_DRIVER_NAME         = 16;
    const ATTR_STRINGIFY_FETCHES   = 17;
    const ATTR_MAX_COLUMN_LEN      = 18;

    const CASE_NATURAL = 0;
    const CASE_UPPER   = 1;
    const CASE_LOWER   = 2;

    const CURSOR_FWDONLY = 0;
    const CURSOR_SCROLL  = 1;

    const ERR_ALREADY_EXISTS  = null;
    const ERR_CANT_MAP        = null;
    const ERR_CONSTRAINT      = null;
    const ERR_DISCONNECTED    = null;
    const ERR_MISMATCH        = null;
    const ERR_NO_PERM         = null;
    const ERR_NONE            = '00000';
    const ERR_NOT_FOUND       = null;
    const ERR_NOT_IMPLEMENTED = null;
    const ERR_SYNTAX          = null;
    const ERR_TRUNCATED       = null;

    const ERRMODE_SILENT    = 0;
    const ERRMODE_WARNING   = 1;
    const ERRMODE_EXCEPTION = 2;

    const FETCH_LAZY      = 0x000001;
    const FETCH_ASSOC     = 0x000002;
    const FETCH_NUM       = 0x000003;
    const FETCH_BOTH      = 0x000004;
    const FETCH_OBJ       = 0x000005;
    const FETCH_BOUND     = 0x000006;
    const FETCH_COLUMN    = 0x000007;
    const FETCH_CLASS     = 0x000008;
    const FETCH_INTO      = 0x000009;
    const FETCH_FUNC      = 0x00000A;
    const FETCH_NAMED     = 0x00000B;
    const FETCH_GROUP     = 0x010000;
    const FETCH_UNIQUE    = 0x030000;
    const FETCH_CLASSTYPE = 0x040000;
    const FETCH_SERIALIZE = 0x080000;

    const FETCH_ORI_NEXT  = 0;
    const FETCH_ORI_PRIOR = 1;
    const FETCH_ORI_FIRST = 2;
    const FETCH_ORI_LAST  = 3;
    const FETCH_ORI_ABS   = 4;
    const FETCH_ORI_REL   = 5;

    const PARAM_BOOL         = 5;
    const PARAM_INPUT_OUTPUT = 0xFFFFFFFF80000000;
    const PARAM_INT          = 1;
    const PARAM_LOB          = 3;
    const PARAM_NULL         = 0;
    const PARAM_STMT         = 4;
    const PARAM_STR          = 2;

    const NULL_EMPTY_STRING = 1;
    const NULL_NATURAL      = 0;
    const NULL_TO_STRING    = null;

    const DEFAULT_CONNECTION_NAME = 'default';

    protected $_connections = array();
    protected $_connectionAliases = array();

    protected static $_singleton = null;

    public function __construct()
    {
        $this->registerNamespace('Wootook_Core_Database_Adapter_');
    }

    /**
     * @return Wootook_Core_Database_ConnectionManager
     */
    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }

    protected $_defaultOptions = array(
        self::ATTR_ERRMODE => self::ERRMODE_EXCEPTION
        );

    /**
     * @param $connectionName
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getConnection($connectionName)
    {
        if (empty($connectionName) || $connectionName === null) {
            return null;
        }

        if (!isset($this->_connections[$connectionName])) {
            if (isset($this->_connectionAliases[$connectionName])) {
                return $this->_connectionAliases[$connectionName];
            }

            $connectionConfig = Wootook::getConfig("resource/database/{$connectionName}");
            if ($connectionConfig === null) {
                return null;
            }
            if ($alias = $connectionConfig->use) {
                $this->_connectionAliases[$connectionName] = self::getConnection($alias);

                return $this->_connectionAliases[$connectionName];
            }

            $this->_connections[$connectionName] = self::_initConnection($connectionName,
                $connectionConfig->engine, $connectionConfig->params, $connectionConfig->options);
        }

        return $this->_connections[$connectionName];
    }

    private function _initConnection($connectionName, $engine, $params, $options = array())
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

        if (empty($params->hostname) || empty($params->username) || empty($params->database)) {
            return null;
        }

        $event = Wootook::dispatchEvent('database.prepare-options', array(
            'name'    => $connectionName,
            'options' => array_merge($this->_defaultOptions, $options)
            ));

        $options = $event->getData('options');

        $connection = $this->load($engine, false, array($params, $options));

        if (!$connection) {
            throw new Wootook_Core_Exception_Database_AdapterError('Could not find data connection handler.');
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

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new ReflectionClass($className);
        return $reflection->newInstanceArgs($constructorParams);
    }
}
