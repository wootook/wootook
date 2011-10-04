<?php

class Wootook_Core_Block_Html_Form
    extends Wootook_Core_Block_Template
{
    public function getFormKey()
    {
        $session = Wootook::getSession('security');
        return $session->getFormKey(true);
    }
}