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
            ->where($idFieldName, new Wootook_Core_Database_Sql_Placeholder_Param('id', $id))
            ->limit(1);

        $statement = $select->prepare();
        $statement->execute(array(
            'id' => $id
            ));

        $datas = $statement->fetch(PDO::FETCH_ASSOC);
        if (!is_array($datas) || empty($datas)) {
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: this id could not be found.');
        }
        $realId = $datas[$this->getIdFieldName()];
        unset($datas[$this->getIdFieldName()]);

        $this->_data = array();
        $this->getDataMapper()->decode($this, $datas);
        $this->setId($realId);

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
                ->where(new Wootook_Core_Database_Sql_Placeholder_Expression("{$adapter->quoteIdentifier($this->getIdFieldName())}=:id", array('id' => $this->getId())));

            foreach ($this->getDataMapper()->encode($this, $this->getChangedDatas()) as $field => $value) {
                $update->set($field, new Wootook_Core_Database_Sql_Placeholder_Param($field, $value));
            }
            try {
                $statement = $update->prepare();
                $statement->execute();
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

                $id = $adapter->lastInsertId($adapter->getTable($this->getTableName()));
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
        $adapter = $this->getWriteConnection();
        if ($adapter === null) {
            throw new Wootook_Core_Exception_DataAccessException('Could not delete data: no write connection configured.');
        }
        $delete = $adapter
            ->delete()
            ->from($adapter->getTable($this->getTableName()))
            ->where($this->getIdFieldName(), $this->getId())
            ->limit(1);

        try {
            $statement = $adapter->prepare($delete);
            $statement->execute();
        } catch (Wootook_Core_Exception_Database_AdapterError $e) {
            throw new Wootook_Core_Exception_DataAccessException('Could not delete data: ' . $e->getMessage(), null, $e);
        } catch (Wootook_Core_Exception_Database_StatementError $e) {
            throw new Wootook_Core_Exception_DataAccessException('Could not delete data: ' . $e->getMessage(), null, $e);
        }

        return $this;
    }
}
