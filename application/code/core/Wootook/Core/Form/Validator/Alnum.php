<?php

class Wootook_Core_Form_Validator_Alnum
    extends Wootook_Core_Form_Validator_Regex
{

    public function __construct()
    {
        parent::__construct('#[^[:alnum:]]#');
    }

    public function validate(Wootook_Core_Form_FieldAbstract $field, $data)
    {
        if ($this->_validate($field, $data)) {
            $this->_getSession($field)
                ->addError('Field "%s" should only contain alphanumeric characters.', $field->getName());

            return false;
        }
        return true;
    }
}