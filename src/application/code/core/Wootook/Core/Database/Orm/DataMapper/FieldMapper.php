<?php

abstract class Wootook_Core_Database_Orm_DataMapper_FieldMapper
{
    protected $_mapper = null;

    public function __construct(Wootook_Core_Database_Orm_DataMapper $mapper = null)
    {
        $this->_mapper = $mapper;
    }

    abstract public function encode($value);

    abstract public function decode($value);
}
