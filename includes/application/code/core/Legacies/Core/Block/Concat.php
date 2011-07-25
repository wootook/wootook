<?php

class Legacies_Core_Block_Concat
    extends Legacies_Core_View
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