<?php

class Wootook_Core_Email_Part_Part
{
    protected $_content = null;

    public function __construct($content, $mime = 'text/plain')
    {
        $this->_content = $content;
    }

    public function render()
    {
        return $this->_content;
    }
}