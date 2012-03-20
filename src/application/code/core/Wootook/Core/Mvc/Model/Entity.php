<?php

abstract class Wootook_Core_Mvc_Model_Entity
    extends Wootook_Core_Database_Resource
    implements Wootook_Core_Mvc_Model_EntityInterface
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

        if (func_num_args() >= 2) {
            $idFieldName = func_get_arg(1);
        } else {
            $idFieldName = $this->getIdFieldName();
        }

        $database = $this->getReadConnection();
        if ($database === null) {
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: no read connection configured.');
        }

        $select = $database->select()
            ->from(array('main_table' => $database->getTable($this->getTableName())))
            ->where("{$database->quoteIdentifier($idFieldName)}=:id")
            ->limit(1);

        $statement = $select->prepare();
        $statement->execute(array(
            'id' => $id
            ));

        $datas = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: this id could not be found.');
        }
        unset($datas[$this->getIdFieldName()]);

        $this->_data = array();
        $this->getDataMapper()->decode($this, $datas);
        $this->setId($id);

        return $this;
    }

    protected function _save()
    {
        $adapter = $this->getWriteConnection();

        if ($adapter === null) {
            throw new Wootook_Core_Exception_DataAccessException('Could not save data: no write connection configured.');
        }

        if ($this->getId() !== null) {
            $update = $adapter->update()
                ->into($adapter->getTable($this->getTableName()))
                ->where("{$adapter->quoteIdentifier($this->getIdFieldName())}=:id");

            foreach ($this->getDataMapper()->encode($this, $this->getChangedDatas()) as $field => $value) {
                $update->set($field, new Wootook_Core_Database_Sql_Placeholder_Param($field, $value));
            }
            try {
                $statement = $update->prepare();
                $statement->execute(array('id' => $this->getId()));
            } catch (Wootook_Core_Exception_Database_AdapterError $e) {
                throw new Wootook_Core_Exception_DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            } catch (Wootook_Core_Exception_Database_StatementError $e) {
                throw new Wootook_Core_Exception_DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            }
        } else {
            $insert = $adapter->insert()
                ->into($adapter->getTable($this->getTableName()));

            foreach ($this->getDataMapper()->encode($this, $this->getAllDatas()) as $field => $value) {
                $insert->set($field, new Wootook_Core_Database_Sql_Placeholder_Param($field, $value));
            }
            try {
                $statement = $insert->prepare();
                $statement->execute();

                $id = $adapter->lastInsertId($table);
                $this->setId($id);
            } catch (Wootook_Core_Exception_Database_AdapterError $e) {
                throw new Wootook_Core_Exception_DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            } catch (Wootook_Core_Exception_Database_StatementError $e) {
                throw new Wootook_Core_Exception_DataAccessException('Could not save data: ' . $e->getMessage(), null, $e);
            }
        }

        return $this;
    }

    protected function _delete()
    {
        $fields = array();
        foreach ($this->getAllDatas() as $field => $value) {
            if ($field == $this->getIdFieldName()) {
                continue;
            }
            $fields[] = "{$field}=:{$field}";
        }

        $fieldsImploded = implod(', ', $fields);
        $idFieldName = $this->getIdFieldName();
        $database = $this->getWriteConnection();
        if ($database === null) {
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: no write connection configured.');
        }

        $sql =<<<SQL_EOF
DELETE {$database->getTable($this->getTableName())}
    WHERE {$idFieldName}=:{$idFieldName}
SQL_EOF;

        $statement->execute($this->getAllDatas());

        return $this;
    }
}
