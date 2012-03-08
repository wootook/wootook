<?php

abstract class Wootook_Core_Database_Statement_Statement
    implements Iterator
{
    protected $_adapter = null;

    protected $_currentIndex = 0;
    protected $_currentRow = null;

    public function __construct(Wootook_Core_Database_Adapter_Adapter $adapter, $sql)
    {
        $this->_adapter = $adapter;

        $this->_init($sql);
    }

    abstract protected function _init($sql);

    /**
     *
     * @param string|int $column
     * @param mixed $param
     * @param int $type
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function bindColumn($column, &$param, $type = null);

    /**
     *
     * @param string|int $parameter
     * @param mixed $variable
     * @param int $type
     * @param int $length
     * @param unknown_type $options
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null);

    /**
     *
     * @param string|int $parameter
     * @param mixed $value
     * @param int $type
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function bindValue($parameter, $value, $type = null);

    /**
     *
     * @param array $params
     * @return bool
     */
    abstract public function execute(Array $params = null);

    /**
     *
     * @param int $style
     * @param int $col
     * @return mixed
     */
    abstract public function fetch($style = null, $orientation = Wootook_Core_Database_ConnectionManager::FETCH_ORI_NEXT, $cursorOffset = 0);

    /**
     *
     * @param int $style
     * @param int $col
     * @return mixed
     */
    abstract public function fetchAll($style = null, $col = null);

    /**
     *
     * @param int $col
     * @return mixed
     */
    abstract public function fetchColumn($col);

    /**
     *
     * @param string $class
     * @param array $config
     * @return Wootook_Object
     */
    abstract public function fetchObject($class = 'Wootook_Object', Array $constructorArgs = array());

    /**
     *
     * @param string $class
     * @param array $config
     * @return Wootook_Object
     */
    public function fetchEntity($class = 'Wootook_Core_Entity', Array $constructorArgs = array())
    {
        $reflection = new ReflectionClass($class);
        $object = $reflection->newInstanceArgs($constructorArgs);

        if (!$object instanceof Wootook_Object) {
            throw new Wootook_Core_Exception_Database_StatementError($this, 'Destination object should be a Wootook_Core_Entity instance.');
        }

        $data = $this->fetch(Wootook_Core_Database_ConnectionManager::FETCH_ASSOC);
        $object->getDataMapper()->decode($object, $data);

        return $object;
    }

    /**
     *
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    abstract public function getAdapter();

    /**
     *
     * @param string $key
     */
    abstract public function getAttribute($key);

    /**
     *
     * @param string $key
     * @param mixed $value
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function setAttribute($key, $value);

    /**
     * @param unknown_type $mode
     * @return Wootook_Core_Database_Statement_Statement
     */
    abstract public function setFetchMode($mode);

    /**
     * @return int
     */
    abstract public function columnCount();

    /**
     * @return int
     */
    abstract public function rowCount();

    public function current()
    {
        return $this->_currentRow;
    }

    public function next()
    {
        $this->_currentRow = $this->fetch();
        $this->_currentIndex++;
    }

    public function key()
    {
        return $this->_currentIndex;
    }

    public function rewind()
    {
        return $this->_currentIndex = 0;
    }

    public function valid()
    {
        return $this->_currentRow !== null;
    }
}