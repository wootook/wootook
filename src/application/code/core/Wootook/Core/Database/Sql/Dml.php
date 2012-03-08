<?php

interface Wootook_Core_Database_Sql_Dml
{
    function __construct(Wootook_Core_Database_Adapter_Adapter $connection, $param = null);

    function where($condition);
    function limit($limit, $offset = null);

    function renderWhere();
    function renderLimit();

    function render();
    function toString($part = null);

    function getPart($part = null);
    function reset($part = null);

    function quote($data);
    function quoteIdentifier($identifier);

    function setConnection(Wootook_Core_Database_Adapter_Adapter $connection);
    function getConnection();

    function beforePrepare(Wootook_Core_Database_Statement_Statement $statement);
    function afterPrepare(Wootook_Core_Database_Statement_Statement $statement);

    function beforeExecute(Wootook_Core_Database_Statement_Statement $statement);
    function afterExecute(Wootook_Core_Database_Statement_Statement $statement);
}