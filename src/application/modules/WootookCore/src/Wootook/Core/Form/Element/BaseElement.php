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

namespace Wootook\Core\Form\Element;

use Wootook\Core\Form,
    Wootook\Core\Form\Validator;

abstract class BaseElement
{
    protected $_name = null;
    protected $_form = null;
    protected $_value = null;

    protected $_params = null;

    protected $_validators = array();

    public function __construct($name, $params = array(), Form\Form $form = null)
    {
        if ($form !== null) {
            $this->setForm($form);
        }

        $this->setName($name);
        $this->setAllParams($params);
    }

    abstract public function getType();

    public function validate()
    {
        $data = $this->getData();

        foreach ($this->_validators as $validator) {
            if (!$validator->validate($this, $data)) {
                return false;
            }
        }
        return true;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setAllParams($params)
    {
        $this->_params = $params;

        return $this;
    }

    public function getAllParams()
    {
        return $this->_params;
    }

    public function setParam($param, $value)
    {
        $this->_params[$param] = $value;

        return $this;
    }

    public function getParam($param)
    {
        return $this->_params[$param];
    }

    public function hasParam($param)
    {
        return isset($this->_params[$param]);
    }

    public function unsetParam($param)
    {
        unset($this->_params[$param]);

        return $this;
    }

    public function setForm(Form\Form $form)
    {
        $this->_form = $form;

        return $this;
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function getData()
    {
        return $this->_value;
    }

    public function populate($value)
    {
        $this->_value = $value;

        return $this;
    }

    public function addValidator(Validator\BaseValidator $validator, $name = null)
    {
        if ($name === null) {
            $this->_validators = array();
            $name = 'default';
        }

        $this->_validators[$name] = $validator;

        return $this;
    }

    public function getValidator($name)
    {
        if (!isset($this->_validators[$name])) {
            return null;
        }

        return $this->_validators[$name];
    }

    public function clearValidator($name)
    {
        if (isset($this->_validators[$name])) {
            unset($this->_validators[$name]);
        }

        return $this;
    }

    public function clearAllValidators()
    {
        $this->_validators = array();

        return $this;
    }
}
