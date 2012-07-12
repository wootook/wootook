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

use Wootook\Core\Base\Service;

class Front
{
    use Service\App;

    const ROUTE_DEFAULT = 'default';
    const ROUTE_ERROR = 'error';

    // FIXME: Create a router class to manage all this mess
    protected $_routes = array(
        self::ROUTE_ERROR => array(
            'modules' => array(
                'core' => array(
                    100 => 'Wootook\\Core\\Controller'
                    )
                ),
            'defaults' => array(
                'module'     => 'core',
                'controller' => 'error',
                'action'     => 'no-route'
                )
            ),
        self::ROUTE_DEFAULT => array(
            'modules' => array(
                ),
            'defaults' => array(
                'module'     => 'core',
                'controller' => 'index',
                'action'     => 'index'
                )
            )
        );

    protected $_request = null;
    protected $_response = null;

    protected function _construct(Request\Request $request = null, Response\Response $response = null)
    {
        if ($request !== null) {
            $this->setRequest($request);
        }
        if ($response !== null) {
            $this->setResponse($response);
        }
    }

    /**
     * @return Request\Request
     */
    public function getRequest()
    {
        if ($this->_request === null) {
            $this->_request = new Request\Http($this->app());
        }
        return $this->_request;
    }

    public function setRequest(Request\Request $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * @return Response\Response
     */
    public function getResponse()
    {
        if ($this->_response === null) {
            $this->_response = new Response\Http($this->app());
        }
        return $this->_response;
    }

    public function setResponse(Response\Response $response)
    {
        $this->_response = $response;

        return $this;
    }

    protected function _setNoRouteAction(&$route, &$moduleKey, &$controllerKey, &$actionKey)
    {
        $route         = self::ROUTE_ERROR;
        $moduleKey     = $this->_routes[self::ROUTE_ERROR]['defaults']['module'];
        $controllerKey = $this->_routes[self::ROUTE_ERROR]['defaults']['controller'];
        $actionKey     = $this->_routes[self::ROUTE_ERROR]['defaults']['action'];

        return $this;
    }

    protected function _setDefaultRouteAction(&$route, &$moduleKey, &$controllerKey, &$actionKey)
    {
        $route         = self::ROUTE_DEFAULT;
        $moduleKey     = $this->_routes[self::ROUTE_DEFAULT]['defaults']['module'];
        $controllerKey = $this->_routes[self::ROUTE_DEFAULT]['defaults']['controller'];
        $actionKey     = $this->_routes[self::ROUTE_DEFAULT]['defaults']['action'];

        return $this;
    }

    protected function _camelizeClass($string)
    {
        $string = strtolower($string);
        $string = str_replace(' ', '\\', ucwords(str_replace('.', ' ', $string)));
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function _camelizeMethod($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }

    protected function _getControllerClass($route, $module, $controllerKey)
    {
        if (!isset($this->_routes[$route])) {
            return null;
        }
        if (!isset($this->_routes[$route]['modules'][$module])) {
            return null;
        }
        $modulePrefix = current($this->_routes[$route]['modules'][$module]);
        return $modulePrefix . '\\' . $this->_camelizeClass($controllerKey) . 'Controller';
    }

    protected function _getActionMethod($actionKey)
    {
        return $this->_camelizeMethod($actionKey) . 'Action';
    }

    protected function _forward($action, $controller = null, $module = null)
    {
        $this->getRequest()->setActionName($action);
        if ($controller !== null) {
            $this->getRequest()->setControllerName($controller);
            if ($module != null) {
                $this->getRequest()->setModuleName($module);
            }
        }
        $this->getResponse()->setIsDispatched(false);
    }

    public function dispatch($route = null)
    {
        if ($route == null) {
            $route = self::ROUTE_DEFAULT;
        }

        if (!isset($this->_routes[$route])) {
            $route = self::ROUTE_DEFAULT;
        }

        $loop = 0;
        while ($loop++ < 100) {
            $moduleKey = $this->getRequest()->getModuleName();
            if (empty($moduleKey)) {
                $this->_forward(
                    $this->_routes[$route]['defaults']['action'],
                    $this->_routes[$route]['defaults']['controller'],
                    $this->_routes[$route]['defaults']['module']
                );
                continue;
            }

            if (!isset($this->_routes[$route]['modules'][$moduleKey])) {
                $this->_forward(
                    $this->_routes[self::ROUTE_ERROR]['defaults']['action'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['controller'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['module']
                );
                $route = self::ROUTE_ERROR;
                continue;
            }

            $controllerKey = $this->getRequest()->getControllerName();
            if (empty($controllerKey)) {
                $controllerKey = $this->_routes[$route]['defaults']['controller'];
            }

            $controllerClass = $this->_getControllerClass($route, $moduleKey, $controllerKey);

            if (!class_exists($controllerClass, true)) {
                $this->_forward(
                    $this->_routes[self::ROUTE_ERROR]['defaults']['action'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['controller'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['module']
                );
                $route = self::ROUTE_ERROR;
                continue;
            }

            $actionKey = $this->getRequest()->getActionName();
            if (empty($actionKey)) {
                $actionKey = $this->_routes[$route]['defaults']['action'];
            }

            $actionMethod = $this->_getActionMethod($actionKey);
            if (!method_exists($controllerClass, $actionMethod)) {
                $this->_forward(
                    $this->_routes[self::ROUTE_ERROR]['defaults']['action'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['controller'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['module']
                );
                $route = self::ROUTE_ERROR;
                continue;
            }

            $controller = new $controllerClass($this->app(), $this->getRequest(), $this->getResponse());
            $controller->init();

            $this->getResponse()->setIsDispatched();

            $this->preDispatch();
            $controller->preDispatch();
            if (!$this->getResponse()->isDispatched()) {
                continue;
            }
            if ($this->getResponse()->isRedirect()) {
                break;
            }

            $controller->$actionMethod();

            if (!$this->getResponse()->isDispatched()) {
                continue;
            }

            $controller->postDispatch();
            $this->postDispatch();

            if ($this->getResponse()->isDispatched() || $this->getResponse()->isRedirect()) {
                break;
            }
        }

        return $this;
    }

    public function send()
    {
        $this->getResponse()->render();
    }

    public function addModule($frontName, $namespace, $path)
    {
        $this->_routes[self::ROUTE_DEFAULT]['modules'][$frontName] = array(
            'class' => $namespace,
            'path'  => $path
            );

        return $this;
    }

    public function setDefaults($module, $controller, $action)
    {
        $this->_routes[self::ROUTE_DEFAULT]['defaults'] = array(
            'module'     => $module,
            'controller' => $controller,
            'action'     => $action
            );

        return $this;
    }

    public function preDispatch()
    {
        \Wootook::dispatchEvent('core.mvc.controller.front.pre-dispatch', array(
            'request'  => $this->getRequest(),
            'response' => $this->getResponse()
            ));

        return $this;
    }

    public function postDispatch()
    {
        \Wootook::dispatchEvent('core.mvc.controller.front.post-dispatch', array(
            'request'  => $this->getRequest(),
            'response' => $this->getResponse()
        ));

        return $this;
    }
}
