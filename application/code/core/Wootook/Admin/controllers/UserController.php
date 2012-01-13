<?php

class Wootook_Admin_UserController
    extends Wootook_Admin_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('grid');
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
    }

    public function editAction()
    {
    }

    public function saveAction()
    {
    }

    public function saveMassAction()
    {
    }

    public function deleteAction()
    {
    }

    public function deleteMassAction()
    {
    }
}