<?php

abstract class Wootook_Core_Form_ValidatorAbstract
{
    abstract public function validate(Wootook_Core_Form_ElementAbstract $element, $data);

    protected function _getSession(Wootook_Core_Form_ElementAbstract $element)
    {
        $form = $element->getForm();
        if (!$form instanceof Wootook_Core_Form) {
            return null;
        }
        return $element->getSession();
    }
}