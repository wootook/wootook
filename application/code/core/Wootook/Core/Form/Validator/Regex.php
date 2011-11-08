<?php

class Wootook_Core_Form_Validator_Regex
    extends Wootook_Core_Form_ValidatorAbstract
{
    protected $_expression = null;

    public function __construct($expression)
    {
        $this->_expression = $expression;
    }

    public function validate(Wootook_Core_Form_FieldAbstract $field, $data)
    {
        if (!$this->_validate($field, $data)) {
            $this->_getSession($field)
                ->addError('Field "%s" does not match the validation pattern.', $field->getName());

            return false;
        }
        return true;
    }

    protected function _validate(Wootook_Core_Form_FieldAbstract $field, $data)
    {
        return preg_match($this->_expression, $data);
    }
}