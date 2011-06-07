<?php

abstract class Legacies_Core_Model
    extends Legacies_Object
{
    protected $_originalData = array();
    protected $_data = array();

    public function __construct(Array $data = array())
    {
        $this->_data = $data;
        $this->_setOriginalData($data);

        $this->_init();
    }

    abstract protected function _init();

    protected function _setOriginalData(Array $data)
    {
        $this->_originalData = $data;
    }

    final public function save()
    {
        try {
            $params = func_get_args();
            call_user_func_array(array($this, '_beforeSave'), $params);
            call_user_func_array(array($this, '_save'), $params);
            call_user_func_array(array($this, '_afterSave'), $params);
            $this->_setOriginalData($this->_data);
        } catch (PDOException $e) {
            throw new Legacies_Core_Model_Exception('Could not save data: ' . $e->getMessage(), 0);
        }
        return $this;
    }

    abstract protected function _save();

    protected function _beforeSave()
    {
        return $this;
    }

    protected function _afterSave()
    {
        return $this;
    }

    final public function load()
    {
        try {
            $params = func_get_args();
            call_user_func_array(array($this, '_beforeLoad'), $params);
            call_user_func_array(array($this, '_load'), $params);
            call_user_func_array(array($this, '_afterLoad'), $params);
            $this->_setOriginalData($this->_data);
        } catch (PDOException $e) {
            throw new Legacies_Core_Model_Exception('Could not load data: ' . $e->getMessage(), 0);
        }
        return $this;
    }

    abstract protected function _load();

    protected function _beforeLoad()
    {
        return $this;
    }

    protected function _afterLoad()
    {
        return $this;
    }

    final public function delete()
    {
        try {
            $params = func_get_args();
            call_user_func_array(array($this, '_beforeDelete'), $params);
            call_user_func_array(array($this, '_delete'), $params);
            call_user_func_array(array($this, '_afterDelete'), $params);
        } catch (PDOException $e) {
            throw new Legacies_Core_Model_Exception('Could not delete entity: ' . $e->getMessage(), 0);
        }
        return $this;
    }

    abstract protected function _delete();

    protected function _beforeDelete()
    {
        return $this;
    }

    protected function _afterDelete()
    {
        return $this;
    }
}