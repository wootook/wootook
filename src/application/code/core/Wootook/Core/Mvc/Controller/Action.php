<?php

abstract class Wootook_Core_Mvc_Controller_Action
{
    private $_request = null;
    private $_response = null;

    private $_layout = null;

    public function __construct(Wootook_Core_Mvc_Controller_Request_Http $request, Wootook_Core_Mvc_Controller_Response_Http $response)
    {
        $this->setRequest($request);
        $this->setResponse($response);
    }

    public function init()
    {
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
    }

    public function setRequest(Wootook_Core_Mvc_Controller_Request_Http $request)
    {
        $this->_request = $request;

        return $this;
    }

    public function setResponse(Wootook_Core_Mvc_Controller_Response_Http $response)
    {
        $this->_response = $response;

        return $this;
    }

    /**
     *
     * @return Wootook_Core_Mvc_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     *
     * @return Wootook_Core_Mvc_Controller_Response_Http
     */
    public function getResponse()
    {
        return $this->_response;
    }

    protected function _forward($action, $controller = null, $module = null)
    {
        $request = $this->getRequest();

        $request->setActionName($action);
        if ($controller !== null) {
            $request->setControllerName($controller);
            if ($module != null) {
                $request->setModuleName($module);
            }
        }
        $this->getResponse()->setIsDispatched(false);
    }

    protected function _redirect($uri, Array $params = array(), $code = Wootook_Core_Mvc_Controller_Response_Http::REDIRECT_FOUND)
    {
        $parts = explode('/', $uri);
        $partsCount = count($parts);
        if ($partsCount > 0 && $parts[0] == '*') {
            $parts[0] = $this->getRequest()->getModuleName();
            if ($partsCount > 1 && $parts[1] == '*') {
                $parts[1] = $this->getRequest()->getControllerName();
                if ($partsCount > 2 && $parts[2] == '*') {
                    $parts[2] = $this->getRequest()->getActionName();
                }
            }
        }
        $uri = implode('/', array_slice($parts, 0, 3));

        $this->getResponse()
            ->setRedirect(Wootook::getUrl($uri, $params), $code);

        Wootook_Core_ErrorProfiler::unregister(true);

        return $this;
    }

    protected function _redirectUrl($url, $code = Wootook_Core_Mvc_Controller_Response_Http::REDIRECT_FOUND)
    {
        $this->getResponse()->setRedirect($url, $code);

        return $this;
    }

    protected function _buildLayoutHandle()
    {
        $request = $this->getRequest();

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        return "{$module}.{$controller}.{$action}";
    }

    public function loadLayout($handle = null)
    {
        if ($this->_layout === null) {
            $this->_layout = $this->_initLayout();
        }

        if ($handle === null) {
            $handle = $this->_buildLayoutHandle();
        }
        $this->_layout->load($handle);

        return $this;
    }

    protected function _initLayout()
    {
        return new Wootook_Core_Model_Layout(Wootook_Core_Model_Layout::DOMAIN_FRONTEND);
    }

    /**
     *
     * @return Wootook_Core_Model_Layout
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    public function renderLayout()
    {
        try {
            $content = $this->getLayout()->render();
        } catch (Wootook_Core_Exception_LayoutException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
            return $this;
        }
        $this->getResponse()->setBody($content);

        return $this;
    }

    protected function _prepareLayoutMessages($namespace)
    {
        $this->getLayout()
            ->getMessagesBlock()
            ->prepareMessages($namespace);

        return $this;
    }
}
