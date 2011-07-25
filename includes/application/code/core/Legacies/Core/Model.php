<?php

abstract class Legacies_Core_Model
    extends Legacies_Object
{
    protected $_originalData = array();

    protected $_eventPrefix = 'model';
    protected $_eventObject = 'model';

    public function __construct(Array $data = array())
    {
        $this->_data = $data;

        $this->_init();
        $this->_setOriginalData($this->_data);
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
        Legacies::dispatchEvent('model.before-save', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.before-save', array($this->_eventObject => $this));

        return $this;
    }

    protected function _afterSave()
    {
        Legacies::dispatchEvent('model.after-save', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.after-save', array($this->_eventObject => $this));

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
        Legacies::dispatchEvent('model.before-load', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.before-load', array($this->_eventObject => $this));

        return $this;
    }

    protected function _afterLoad()
    {
        Legacies::dispatchEvent('model.after-load', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.after-load', array($this->_eventObject => $this));

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
        Legacies::dispatchEvent('model.before-delete', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.before-delete', array($this->_eventObject => $this));

        return $this;
    }

    protected function _afterDelete()
    {
        Legacies::dispatchEvent('model.after-delete', array($this->_eventObject => $this));
        Legacies::dispatchEvent($this->_eventPrefix . '.after-delete', array($this->_eventObject => $this));

        return $this;
    }

    public function __set($key, $value)
    {
        return $this->setData($key, $value);
    }

    public function __get($key)
    {
        return $this->getData($key);
    }

    public function __unset($key)
    {
        return $this->unsetData($key);
    }

    public function __isset($key)
    {
        return $this->hasData($key);
    }
}