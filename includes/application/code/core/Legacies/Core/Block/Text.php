<?php

class Legacies_Core_Block_Text
    extends Legacies_Core_View
{
    protected $_content = null;

    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function render()
    {
        $content = $this->getContent();
        if (empty($content)) {
            return null;
        }
        return $content;
    }
}