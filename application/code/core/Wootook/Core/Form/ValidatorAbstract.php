<?php

abstract class Wootook_Core_Form_ValidatorAbstract
{
    abstract public function validate(Wootook_Core_Form_FieldAbstract $field, $data);

    protected function _getSession(Wootook_Core_Form_FieldAbstract $field)
    {
        $form = $field->getForm();
        if (!$form instanceof Wootook_Core_Form) {
            return null;
        }
        return $form->getSession();
    }
}