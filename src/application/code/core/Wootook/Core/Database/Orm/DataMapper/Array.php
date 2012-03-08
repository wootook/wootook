<?php

class Wootook_Core_Database_Orm_DataMapper_Array
    extends Wootook_Core_Database_Orm_DataMapper_FieldMapper
{
    public function encode($value)
    {
        return serialize($value);
    }

    public function decode($value)
    {
        return unserialize($value);
    }
}