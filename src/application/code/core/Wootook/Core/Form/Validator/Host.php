<?php

class Wootook_Core_Form_Validator_Email
    extends Wootook_Core_Form_Validator_Regex
{
    public function __construct()
    {
        parent::__construct('#^[[:alnum:]\._\-]\.[a-z]{2,}$#');
    }

    public function validate(Wootook_Core_Form_ElementAbstract $element, $data)
    {
        if (!$this->_validate($element, $data)) {
            $this->_getSession($element)
                ->addError('Field "%s" should contain a host name.', $element->getName());

            return false;
        }
        return true;
    }
}