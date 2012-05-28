<?php

class Wootook_Core_Database_Orm_DataMapper_DateTime
    extends Wootook_Core_Database_Orm_DataMapper_FieldMapper
{
    const DATE_FORMAT_MYSQL = 'Y-m-d G:i:s';

    protected $_format = self::DATE_FORMAT_MYSQL;

    public function setFormat($format = null)
    {
        if ($format === null) {
            $this->_format = self::DATE_FORMAT_MYSQL;
        } else {
            $this->_format = $format;
        }
        return $this;
    }

    public function getFormat()
    {
        return $this->_format;
    }

    public function encode($value)
    {
        if (!$value instanceof Wootook_Core_DateTime) {
            $value = new Wootook_Core_DateTime($value);
        }

        return $value->toString($this->getFormat());
    }

    public function decode($value)
    {
        return new Wootook_Core_DateTime($value, $this->getFormat());
    }
}
