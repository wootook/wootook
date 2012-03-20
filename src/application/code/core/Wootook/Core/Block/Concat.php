<?php

class Wootook_Core_Block_Concat
    extends Wootook_Core_Mvc_View_View
{
    public function render()
    {
        $content = '';
        foreach ($this->_partials as $partial) {
            $content .= $partial->render();
        }
        return $content;
    }
}
