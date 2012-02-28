<?php

abstract class Wootook_Core_Form_ElementAbstract
{
    protected $_name = null;
    protected $_form = null;
    protected $_value = null;

    protected $_params = null;

    protected $_validators = array();

    public function __construct($name, $params = array(), Wootook_Core_Form $form = null)
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

    public function setForm(Wootook_Core_Form $form)
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

    public function addValidator(Wootook_Core_Form_ValidatorAbstract $validator, $name = null)
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