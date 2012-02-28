<?php

class Wootook_Core_Block_Deprecated
    extends Wootook_Core_Block_Template
{
    public function getScriptPath()
    {
        return $this->getLayout()->getScriptPath();
    }
}