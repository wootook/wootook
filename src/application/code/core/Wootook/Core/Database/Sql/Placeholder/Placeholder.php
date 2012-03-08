<?php

abstract class Wootook_Core_Database_Sql_Placeholder_Placeholder
{
    abstract public function __toString();

    abstract public function beforePrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    abstract public function afterPrepare(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    abstract public function beforeExcute(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }

    abstract public function afterExecute(Wootook_Core_Database_Statement_Statement $statement)
    {
        return $this;
    }
}