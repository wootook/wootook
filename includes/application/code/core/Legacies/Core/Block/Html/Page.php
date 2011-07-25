<?php

class Legacies_Core_Block_Html_Page
    extends Legacies_Core_Block_Template
{
    protected $_bodyClasses = array();

    public function getBodyClasses()
    {
        return implode(' ', $this->_bodyClasses);
    }

    public function addBodyClass($class)
    {
        $this->_bodyClasses[] = $class;

        return $this;
    }
}