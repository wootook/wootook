<?php

abstract class Legacies_Core_Entity
    extends Legacies_Core_Model
    implements Legacies_Core_EntityInterface
{
    protected $_idFieldName = null;
    protected $_tableName = null;

    public function setIdFieldName($fieldName)
    {
        $this->_idFieldName = $fieldName;

        return $this;
    }

    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;

        return $this;
    }

    public function getTableName()
    {
        return $this->_tableName;
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
        $database = Legacies_Database::getSingleton();

        $sql =<<<SQL_EOF
SELECT * FROM {$database->getTable(self::getTableName())}
    WHERE {$idFieldName}=:id
    LIMIT 1
SQL_EOF;
        $statement = $database->prepare($sql);
        $statement->execute(array(
            'id' => $id
            ));

        $datas = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new Legacies_Core_Model_Exception('Could not load data: this id could not be found.');
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

            $database = Legacies_Database::getSingleton();
            $sql =<<<SQL_EOF
UPDATE {$database->getTable(self::getTableName())}
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

            $database = Legacies_Database::getSingleton();
            $sql =<<<SQL_EOF
INSERT INTO {$database->getTable(self::getTableName())} ($fieldsImploded)
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
        $database = Legacies_Database::getSingleton();
        $sql =<<<SQL_EOF
DELETE {$database->getTable(self::getTableName())}
    WHERE {$idFieldName}=:{$idFieldName}
SQL_EOF;

        $statement->execute($this->getAllDatas());

        return $this;
    }
}