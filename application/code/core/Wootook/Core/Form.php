<?php

class Wootook_Core_Form
{
    protected $_fields = array();

    protected $_request = null;

    /**
     *
     * Field class loader
     * @var Wootook_Core_Form_FieldLoader
     */
    protected $_fieldLoader = null;

    /**
     *
     * Validator class loader
     * @var Wootook_Core_Form_ValidatorLoader
     */
    protected $_validatorLoader = null;

    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Model_Session
     */
    protected $_session = null;

    public function __construct(Wootook_Core_Model_Session $session, Array $fields = array())
    {
        $this->_session = $session;

        $this->_fieldLoader = new Wootook_Core_Form_FieldLoader($this, array(
            'Wootook_Core_Form_Field_' => 'Wootook/Core/Form/Field'
            ));

        $this->_validatorLoader = new Wootook_Core_Form_ValidatorLoader($this, array(
            'Wootook_Core_Form_Validator_' => 'Wootook/Core/Form/Validator'
            ));

        $this->addField('__formkey', 'text', array('form_key' => 'formKey'));

        foreach ($fields as $fieldName => $fieldConfig) {
            if (is_string($fieldConfig)) {
                $this->addField($fieldName, $fieldConfig);
            } else if ($fieldConfig instanceof Wootook_Core_Form_FieldAbstract) {
                $this->addField($fieldName, $fieldConfig);
            } else {
                if (isset($fieldConfig['validators'])) {
                    $this->addField($fieldName, $fieldConfig['type'], $fieldConfig['validators']);
                } else {
                    $this->addField($fieldName, $fieldConfig['type']);
                }
            }
        }
    }

    public function validate()
    {
        foreach ($this->_fields as $field) {
            if (!$field->validate()) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * Enter description here ...
     * @param string $name
     * @param string|Wootook_Core_Form_FieldAbstract $type
     * @return Wootook_Core_Form
     */
    public function addField($name, $type = 'text', Array $validators = array())
    {
        if ($type instanceof Wootook_Core_Form_FieldAbstract) {
            $this->_fields[$name] = $name;
            $this->_fields[$name]->setForm($this);
        } else {
            $field = $this->_fieldLoader->load($type);

            if ($field === null) {
                trigger_error(sprintf('Field %1$s (type: %2$s) could not be created.', $name, $type), E_USER_WARNING);
                return $this;
            }
            $this->_fields[$name] = $field;
        }
        $this->_fields[$name]->setName($name);

        foreach ($validators as $validatorName => $validatorType) {
            if ($validatorType instanceof Wootook_Core_Form_FieldAbstract) {
                $this->_fields[$name]->addValidator($validatorType, $validatorName);
            } else {
                $validator = $this->_validatorLoader->load($validatorType);

                if ($validator === null) {
                    trigger_error(sprintf('Validator %1$s (type: %2$s) could not be created.', $validatorName, $validatorType), E_USER_WARNING);
                    return $this;
                }
                $this->_fields[$name]->addValidator($validator, $validatorName);
            }
        }

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @param string $name
     * @return Wootook_Core_Form_FieldAbstract
     */
    public function getField($name)
    {
        if (!isset($this->_fields[$name])) {
            return null;
        }

        return $this->_fields[$name];
    }

    /**
     *
     * Enter description here ...
     * @param string $name
     * @return Wootook_Core_Model_Session
     */
    public function getSession()
    {
        return $this->_session;
    }

    public function setRequest(Wootook_Core_Controller_Request_Http $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     *
     * Enter description here ...
     * @return Wootook_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    public function getData()
    {
        $request = $this->getRequest();
        if ($request === null) {
            return array();
        }
        if ($request->isPost()) {
            return $request->getAllPostData();
        }
        return $request->getAllQueryData();
    }

    public function populate(Array $datas = array())
    {
        $request = $this->getRequest();

        foreach ($this->_fields as $fieldName => $field) {
            if (isset($datas[$fieldName])) {
                $field->populate($datas[$fieldName]);
            } else if ($request->isPost()) {
                $field->populate($request->getPost($fieldName));
            } else {
                $field->populate($request->getQuery($fieldName));
            }
        }
    }
}