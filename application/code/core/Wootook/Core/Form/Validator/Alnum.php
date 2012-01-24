<?php

class Wootook_Core_Form_Validator_Alnum
    extends Wootook_Core_Form_Validator_Regex
{
    public function __construct()
    {
        parent::__construct('#[^[:alnum:]]#');
    }

    public function validate(Wootook_Core_Form_ElementAbstract $element, $data)
    {
        if ($this->_validate($element, $data)) {
            $this->_getSession($element)
                ->addError('Field "%s" should only contain alphanumeric characters.', $element->getName());

            return false;
        }
        return true;
    }
}