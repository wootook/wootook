<?php

interface Wootook_Core_EntityInterface
{
    public function getId();
    public function setId($id);

    public function getIdFieldName();
    public function setIdFieldName($fieldName);

    public function getTableName();
    public function setTableName($tableName);
}