<?php

class Wootook_Core_Form_Validator_Email
    extends Wootook_Core_Form_Validator_Regex
{
    public function __construct()
    {
        parent::__construct('#^[[:alnum:]\._\-]\.[a-z]{2,}$#');
    }

    public function validate(Wootook_Core_Form_FieldAbstract $field, $data)
    {
        if (!$this->_validate($field, $data)) {
            $this->_getSession($field)
                ->addError('Field "%s" should contain a host name.', $field->getName());

            return false;
        }
        return true;
    }
}