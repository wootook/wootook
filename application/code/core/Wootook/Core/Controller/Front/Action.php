<?php

abstract class Wootook_Core_Controller_Front_Action
{
    private $_request = null;
    private $_response = null;

    private $_layout = null;

    public function __construct(Wootook_Core_Controller_Request_Http $request, Wootook_Core_Controller_Response_Http $response)
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

    public function setRequest(Wootook_Core_Controller_Request_Http $request)
    {
        $this->_request = $request;

        return $this;
    }

    public function setResponse(Wootook_Core_Controller_Response_Http $response)
    {
        $this->_response = $response;

        return $this;
    }

    /**
     *
     * @return Wootook_Core_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     *
     * @return Wootook_Core_Controller_Response_Http
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
        $request->setIsDispatched(false);
    }

    protected function _redirect($uri, Array $params = array(), $code = Wootook_Core_Controller_Response_Http::REDIRECT_FOUND)
    {
        $this->getResponse()
            ->setRedirect(Wootook::getUrl($uri, $params), $code);

        return $this;
    }

    protected function _redirectUrl($url, $code = Wootook_Core_Controller_Response_Http::REDIRECT_FOUND)
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
        return new Wootook_Core_Layout(Wootook_Core_Layout::DOMAIN_FRONTEND);
    }

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
}