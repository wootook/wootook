<?php

class Wootook_Core_Block_Text
    extends Wootook_Core_Mvc_View_View
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
