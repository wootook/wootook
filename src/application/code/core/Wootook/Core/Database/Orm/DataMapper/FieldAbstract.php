<?php

abstract class Wootook_Core_Database_Orm_DataMapper_FieldAbstract
{
    abstract public function encode($value);

    abstract public function decode($value);
}