<?php

abstract class Wootook_Admin_Controller_Front_Action
    extends Wootook_Core_Controller_Front_Action
{
    protected function _initLayout()
    {
        $layout = new Wootook_Core_Layout(Wootook_Core_Layout::DOMAIN_BACKEND);
        $layout->registerBlockNamespace('admin', 'Wootook_Admin_Block_', 'Wootook/Admin/Block');

        return $layout;
    }
}