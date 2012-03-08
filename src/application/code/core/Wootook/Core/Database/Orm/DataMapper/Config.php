<?php

class Wootook_Core_Database_Orm_DataMapper_Config
    extends Wootook_Core_Database_Orm_DataMapper_FieldMapper
{
    public function encode($value)
    {
        return parent::encode($value->toArray());
    }

    public function decode($value)
    {
        return new Wootook_Core_Config_Node(parent::decode($value));
    }
}