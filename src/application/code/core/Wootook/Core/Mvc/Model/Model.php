<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
abstract class Wootook_Core_Mvc_Model_Model
    extends Wootook_Object
{
    protected $_originalData = array();

    protected $_eventPrefix = null;
    protected $_eventObject = null;

    public function __construct(Array $data = array())
    {
        $this->_data = $data;

        $this->_init();
        $this->_setOriginalData();
    }

    abstract protected function _init();

    protected function _setOriginalData()
    {
        $this->_originalData = $this->_data;
    }

    public function getChangedDatas()
    {
        return array_diff_assoc($this->_data, $this->_originalData);
    }

    public function hasChangedDatas()
    {
        if (count($this->getChangedDatas()) > 0) {
            return true;
        }
        return false;
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
            throw new Wootook_Core_Exception_DataAccessException('Could not save data: ' . $e->getMessage(), 0);
        }
        return $this;
    }

    abstract protected function _save();

    protected function _beforeSave()
    {
        Wootook::dispatchEvent('model.before-save', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.before-save', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterSave()
    {
        Wootook::dispatchEvent('model.after-save', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.after-save', array($this->_eventObject => $this));
        }

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
            throw new Wootook_Core_Exception_DataAccessException('Could not load data: ' . $e->getMessage(), 0, $e);
        }
        return $this;
    }

    abstract protected function _load();

    protected function _beforeLoad()
    {
        Wootook::dispatchEvent('model.before-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.before-load', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterLoad()
    {
        Wootook::dispatchEvent('model.after-load', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.after-load', array($this->_eventObject => $this));
        }

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
            throw new Wootook_Core_Exception_DataAccessException('Could not delete entity: ' . $e->getMessage(), 0);
        }
        return $this;
    }

    abstract protected function _delete();

    protected function _beforeDelete()
    {
        Wootook::dispatchEvent('model.before-delete', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.before-delete', array($this->_eventObject => $this));
        }

        return $this;
    }

    protected function _afterDelete()
    {
        Wootook::dispatchEvent('model.after-delete', array('model' => $this));

        if ($this->_eventPrefix !== null && $this->_eventObject !== null) {
            Wootook::dispatchEvent($this->_eventPrefix . '.after-delete', array($this->_eventObject => $this));
        }

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
