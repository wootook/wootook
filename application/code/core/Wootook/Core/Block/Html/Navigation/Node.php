<?php

class Wootook_Core_Block_Html_Navigation_Node
    extends Wootook_Core_Block_Html_Navigation
{
    protected $_title = '';

    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getTemplate()
    {
        if ($this->_template !== null) {
            return $this->_template;
        }
        return 'page/html/navigation/node.phtml';
    }
}