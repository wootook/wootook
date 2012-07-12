<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

namespace Wootook\Core\Mvc\Controller;

use Wootook\Core,
    Wootook\Core\Exception as CoreException,
    Wootook\Core\Layout,
    Wootook\Core\Model,
    Wootook\Core\Profiler;

abstract class Action
{
    protected $_app = null;

    private $_request = null;
    private $_response = null;

    private $_layout = null;

    public function __construct(Core\App\App $app, Request\Request $request, Response\Response $response)
    {
        $this->_app = $app;

        $this->setRequest($request);
        $this->setResponse($response);
    }

    public function init()
    {
    }

    public function app()
    {
        return $this->_app;
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
    }

    public function setRequest(Request\Request $request)
    {
        $this->_request = $request;

        return $this;
    }

    public function setResponse(Response\Response $response)
    {
        $this->_response = $response;

        return $this;
    }

    /**
     *
     * @return Request\Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     *
     * @return Response\Response
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

    protected function _redirect($uri, Array $params = array(), $code = Response\Response::REDIRECT_FOUND)
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
            ->setRedirect(\Wootook::getUrl($uri, $params), $code);

        Profiler\ErrorProfiler::unregister(true);

        return $this;
    }

    protected function _redirectUrl($url, $code = Response\Response::REDIRECT_FOUND)
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
        return new Layout\Manager($this->app(), Layout\Manager::DOMAIN_FRONTEND);
    }

    /**
     *
     * @return Layout\Manager
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    public function renderLayout()
    {
        try {
            $content = $this->getLayout()->render();
        } catch (CoreException\LayoutException $e) {
            Profiler\ErrorProfiler::getSingleton()->addException($e);
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
