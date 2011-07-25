<?php

class Legacies_Core_Block_Html_Form
    extends Legacies_Core_Block_Template
{
    public function getFormKey()
    {
        $session = Legacies::getSession('security');
        return $session->getFormKey(true);
    }
}