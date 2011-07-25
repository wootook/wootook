<?php

class Legacies_Core_Controller_Action
{
    private $_request = null;
    private $_response = null;

    public function init()
    {
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
    }

    public function getRequest()
    {
        return $this->_request;
    }

    public function getResponse()
    {
        return $this->_response;
    }

    protected function _forward($action, $controller = null, $module = null)
    {
        $request = $this->getRequest();

        $request->setAction($action);
        if ($controller !== null) {
            $request->setController($controller);
            if ($module != null) {
                $request->setModule($module);
            }
        }
        $request->setIsDispatched(false);
    }

    protected function _redirect($url)
    {
        $this->getResponse()
            ->setRawHeader('HTTP/1.1 302 Found')
            ->setHeader('Location', $url)
            ->setIsRedirect(true)
        ;

        return $this;
    }
}