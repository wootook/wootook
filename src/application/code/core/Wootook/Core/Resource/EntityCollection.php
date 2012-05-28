<?php

abstract class Wootook_Core_Resource_EntityCollection
    implements Iterator, Countable
{
    protected $_connection = null;

    /** @var Wootook_Core_Database_Sql_Select */
    protected $_select = null;

    protected $_entityTable = null;
    protected $_entityClass = null;

    protected $_eventObject = null;
    protected $_eventPrefix = null;

    protected $_dataMapper = null;

    protected $_curPage = null;
    protected $_pageSize = null;

    protected $_items = array();

    public function __construct(Wootook_Core_Database_Adapter_Adapter $connection = null)
    {
        $this->setReadConnection($connection);

        $this->_construct();
    }

    abstract protected function _construct();

    protected function _init($entityTable, $entityClass)
    {
        $this->_entityTable = $entityTable;
        $this->_entityClass = $entityClass;
    }

    public function getSelect()
    {
        if ($this->_select === null) {
            $this->_select = $this->getReadConnection()
                ->select()
                ->from(array('main_table' => $this->getReadConnection()->getTable($this->_entityTable)));

            $this->_prepareSelect($this->_select);
        }

        return $this->_select;
    }

    public function _prepareSelect(Wootook_Core_Database_Sql_Select $select)
    {
    }

    /**
     *
     * @return Wootook_Core_Database_Orm_DataMapper
     */
    public function getDataMapper()
    {
        if ($this->_dataMapper === null) {
            $this->_dataMapper = new Wootook_Core_Database_Orm_DataMapper();
        }
        return $this->_dataMapper;
    }

    /**
     * @return Wootook_Core_Database_Adapter_Adapter
     */
    public function getReadConnection()
    {
        return $this->_connection;
    }

    public function setReadConnection($connection)
    {
        if ($connection instanceof Wootook_Core_Database_Adapter_Pdo_Mysql) {
            $this->_connection = $connection;
        } else if (is_string($connection)) {
            $this->_connection = Wootook_Core_Database_ConnectionManager::getSingleton()
                ->getConnection($connection);
        } else {
            throw new Wootook_Core_Exception_RuntimeException(
                'First parameter should be either a database connection object or a string identifier.');
        }

        return $this;
    }

    final public function load()
    {
        $this->_beforeLoad();
        $this->_load();
        $this->_afterLoad();

        return $this;
    }

    protected function _beforeLoad()
    {
        Wootook::dispatchEvent('resource.collection.before-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.before-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterLoad()
    {
        Wootook::dispatchEvent('resource.collection.after-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.after-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _load()
    {
        $select = clone $this->getSelect();

        if ($this->_pageSize !== null) {
            if ($this->_curPage !== null) {
                $select->limit($this->_pageSize, ($this->_curPage - 1) * $this->_pageSize);
            } else {
                $select->limit($this->_pageSize);
            }
        }

        $statement = $select->prepare();

        $args = func_get_args();
        $statement->execute(array_shift($args));

        $this->_items = array();
        if (func_num_args() > 1) {
            $reflection = new ReflectionClass(array_shift($args));
        } else {
            $reflection = new ReflectionClass($this->_entityClass);
        }

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $item = $reflection->newInstanceArgs();
            $item->getDataMapper()->decode($item, $row);

            $this->_items[] = $item;
        }

        return $this;
    }

    public function getSize($maintainGrouping = array())
    {
        $clone = clone $this->_select;
        $clone->reset(Wootook_Core_Database_Sql_Select::COLUMNS);
        if (empty($maintainGrouping)) {
            $clone->reset(Wootook_Core_Database_Sql_Select::GROUP);
        } else {
            $groupingFields = $clone->getPart(Wootook_Core_Database_Sql_Select::GROUP);
            $clone->reset(Wootook_Core_Database_Sql_Select::GROUP);
            foreach ($groupingFields as $field) {
                if (in_array($field, $maintainGrouping)) {
                    $clone->group($field);
                }
            }
        }
        $clone->column(new Wootook_Core_Database_Sql_Placeholder_Expression('COUNT(*)'));

        $database = $this->getReadConnection();
        $statement = $database->prepare($clone);

        $statement->execute();
        $count = $statement->fetchColumn();
        $statement->closeCursor();

        return $count;
    }

    public function count()
    {
        return count($this->_items);
    }

    public function current()
    {
        return current($this->_items);
    }

    public function next()
    {
        next($this->_items);

        return $this;
    }

    public function rewind()
    {
        reset($this->_items);

        return $this;
    }

    public function key()
    {
        return key($this->_items);
    }

    public function valid()
    {
        return current($this->_items) !== false;
    }

    public function getFirstItem()
    {
        if (count($this->_items) > 0) {
            return $this->_items[0];
        }
        return null;
    }

    public function getLastItem()
    {
        $size = count($this->_items);
        if ($size > 0) {
            return $this->_items[$size - 1];
        }
        return null;
    }

    public function addOrderBy($field, $type = 'ASC')
    {
        $this->getSelect()->order($field, $type);

        return $this;
    }

    public function addFieldToFilter($field, $value = null)
    {
        $this->getSelect()->where($field, $value);

        return $this;
    }

    public function setPage($curPage, $pageSize)
    {
        $this->setCurPage($curPage)->setPageSize($pageSize);

        return $this;
    }

    public function setCurPage($curPage)
    {
        $this->_curPage = $curPage;

        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->_pageSize = $pageSize;

        return $this;
    }
}
