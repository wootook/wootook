<?php

abstract class Wootook_Core_Entity
    extends Wootook_Core_Database_Resource
    implements Wootook_Core_EntityInterface
{
    protected $_idFieldName = null;

    public function setIdFieldName($fieldName)
    {
        $this->_idFieldName = $fieldName;

        return $this;
    }

    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    public function setId($id)
    {
        $this->setData($this->getIdFieldName(), $id);

        return $this;
    }

    public function getId()
    {
        return $this->getData($this->getIdFieldName());
    }

    protected function _load()
    {
        $id = func_get_arg(0);

        $idFieldName = self::getIdFieldName();
        $database = $this->getReadConnection();

        $sql =<<<SQL_EOF
SELECT * FROM {$database->getTable($this->getTableName())}
    WHERE {$idFieldName}=:id
    LIMIT 1
SQL_EOF;
        $statement = $database->prepare($sql);
        $statement->execute(array(
            'id' => $id
            ));

        $datas = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: this id could not be found.');
        }
        unset($datas[self::getIdFieldName()]);

        $this->_data = $datas;
        $this->setId($id);

        return $this;
    }

    protected function _save()
    {
        if ($this->getId() !== null) {
            $fields = array();
            $values = array();
            foreach ($this->getAllDatas() as $field => $value) {
                if ($field == self::getIdFieldName()) {
                    continue;
                }
                $fields[] = "{$field}=:{$field}";
                $values[$field] = $value;
            }

            $fieldsImploded = implode(', ', $fields);
            $idFieldName = self::getIdFieldName();
            $values[$idFieldName] = $this->getId();

            $database = $this->getWriteConnection();
            $sql =<<<SQL_EOF
UPDATE {$database->getTable($this->getTableName())}
    SET {$fieldsImploded}
    WHERE {$idFieldName}=:{$idFieldName}
SQL_EOF;
            $statement = $database->prepare($sql);

            $statement->execute($values);
        } else {
            $datas = $this->getAllDatas();
            $fieldsImploded = implode(', ', array_keys($datas));

            $tokens = array();
            $values = array();
            foreach ($datas as $field => $value) {
                if ($field == self::getIdFieldName()) {
                    continue;
                }
                $tokens[] = ":{$field}";
                $values[$field] = $value;
            }
            $tokensImploded = implode(', ', $tokens);

            $database = $this->getWriteConnection();
            $sql =<<<SQL_EOF
INSERT INTO {$database->getTable($this->getTableName())} ($fieldsImploded)
    VALUES ({$tokensImploded})
SQL_EOF;
            $statement = $database->prepare($sql);

            $statement->execute($values);

            $this->setId($database->lastInsertId());
        }

        return $this;
    }

    protected function _delete()
    {
        $fields = array();
        foreach ($this->getAllDatas() as $field => $value) {
            if ($field == self::getIdFieldName()) {
                continue;
            }
            $fields[] = "{$field}=:{$field}";
        }

        $fieldsImploded = implod(', ', $fields);
        $idFieldName = self::getIdFieldName();
        $database = $this->getWriteConnection();
        $sql =<<<SQL_EOF
DELETE {$database->getTable($this->getTableName())}
    WHERE {$idFieldName}=:{$idFieldName}
SQL_EOF;

        $statement->execute($this->getAllDatas());

        return $this;
    }
}