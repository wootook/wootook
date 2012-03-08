<?php

class Wootook_Core_Database_Orm_DataMapper_Object
    extends Wootook_Core_Database_Orm_DataMapper_FieldMapper
{
    protected $_entityClass = null;

    protected $_reflectionClass = null;

    public function setEntityClass($class)
    {
        $this->_entityClass = $class;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->_entityClass;
    }

    protected function _newInstance(Array $args = array())
    {
        if ($this->_reflectionClass === null && $this->_entityClass !== null) {
            $this->_reflectionClass = new ReflectionClass($this->_entityClass);
        }

        return $this->newInstanceArgs($args);
    }

    public function encode($value)
    {
        return parent::encode($value->getAllDatas());
    }

    public function decode($value)
    {
        $object = $this->_newInstance();
        $object->addData(parent::decode($value));

        return $object;
    }
}