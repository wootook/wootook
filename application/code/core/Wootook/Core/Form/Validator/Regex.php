<?php

class Wootook_Core_Form_Validator_Regex
    extends Wootook_Core_Form_ValidatorAbstract
{
    protected $_expression = null;

    public function __construct($expression)
    {
        $this->_expression = $expression;
    }

    public function validate(Wootook_Core_Form_ElementAbstract $element, $data)
    {
        if (!$this->_validate($element, $data)) {
            $this->_getSession($element)
                ->addError('Field "%s" does not match the validation pattern.', $element->getName());

            return false;
        }
        return true;
    }

    protected function _validate(Wootook_Core_Form_ElementAbstract $element, $data)
    {
        return preg_match($this->_expression, $data);
    }
}