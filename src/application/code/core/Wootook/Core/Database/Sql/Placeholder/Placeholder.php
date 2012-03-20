<?php

abstract class Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    public function toString()
    {
        return $this->__toString();
    }

    abstract public function __toString();

    public function beforePrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    public function afterPrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    public function beforeExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    public function afterExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }
}
