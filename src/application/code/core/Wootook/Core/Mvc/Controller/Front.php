<?php

class Wootook_Core_Mvc_Controller_Front
{
    const ROUTE_DEFAULT = 'default';
    const ROUTE_ERROR = 'error';

    protected $_routes = array(
        self::ROUTE_ERROR => array(
            'modules' => array(
                'core' => array(
                    'class' => 'Wootook_Core_',
                    'path'  => 'Wootook/Core'
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
                'core' => array(
                    'class' => 'Wootook_Core_',
                    'path'  => 'Wootook/Core'
                    ),
                'admin' => array(
                    'class' => 'Wootook_Admin_',
                    'path'  => 'Wootook/Admin'
                    ),
                'player' => array(
                    'class' => 'Wootook_Player_',
                    'path'  => 'Wootook/Player'
                    ),
                'empire' => array(
                    'class' => 'Wootook_Empire_',
                    'path'  => 'Wootook/Empire'
                    )
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

    public function __construct(Wootook_Core_Mvc_Controller_Request_Request $request,
        Wootook_Core_Mvc_Controller_Response_Response $response)
    {
        $this->_request = $request;
        $this->_response = $response;
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
        return str_replace(' ', '_', ucwords(str_replace('-', ' ', $string)));
    }

    protected function _camelizeMethod($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }

    protected function _getControllerClass($modulePrefix, $controllerKey)
    {
        return $modulePrefix . $this->_camelizeClass($controllerKey) . 'Controller';
    }

    protected function _getControllerPath($modulePath, $controllerKey)
    {
        return $modulePath . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR
            . $this->_camelizeClass($controllerKey) . 'Controller.php';
    }

    protected function _getActionMethod($actionKey)
    {
        return $this->_camelizeMethod($actionKey) . 'Action';
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
            $moduleKey = $this->_request->getModuleName();
            $controllerKey = $this->_request->getControllerName();
            $actionKey = $this->_request->getActionName();

            if (empty($moduleKey)) {
                $this->_setDefaultRouteAction($route, $moduleKey, $controllerKey, $actionKey);
            }

            if (!isset($this->_routes[$route]['modules'][$moduleKey])) {
                $this->_setNoRouteAction($route, $moduleKey, $controllerKey, $actionKey);
            }

            if (empty($controllerKey)) {
                $controllerKey = $this->_routes[$route]['defaults']['controller'];
            }

            $controllerClass = $this->_getControllerClass($this->_routes[$route]['modules'][$moduleKey]['class'], $controllerKey);
            $controllerPath = $this->_getControllerPath($this->_routes[$route]['modules'][$moduleKey]['path'], $controllerKey);

            if (!class_exists($controllerClass, false) && Wootook::fileExists($controllerPath)) {
                include_once $controllerPath;
            }

            if (!class_exists($controllerClass, false)) {
                $this->_setNoRouteAction($route, $moduleKey, $controllerKey, $actionKey);

                $controllerClass = $this->_getControllerClass($this->_routes[$route]['modules'][$moduleKey]['class'], $controllerKey);
                $controllerPath = $this->_getControllerPath($this->_routes[$route]['modules'][$moduleKey]['path'], $controllerKey);

                include_once $controllerPath;
            }

            if (empty($actionKey)) {
                $actionKey = $this->_routes[$route]['defaults']['action'];
            }

            $actionMethod = $this->_getActionMethod($actionKey);
            if (!method_exists($controllerClass, $actionMethod)) {
                $this->_setNoRouteAction($route, $moduleKey, $controllerKey, $actionKey);

                $controllerClass = $this->_getControllerClass($this->_routes[$route]['modules'][$moduleKey]['class'], $controllerKey);
                $controllerPath = $this->_getControllerPath($this->_routes[$route]['modules'][$moduleKey]['path'], $controllerKey);

                include_once $controllerPath;

                $actionMethod = $this->_getActionMethod($actionKey);
            }

            $controller = new $controllerClass($this->_request, $this->_response);
            $controller->init();

            $this->_response->setIsDispatched();

            $controller->preDispatch();
            if (!$this->_response->isDispatched()) {
                continue;
            }

            $controller->$actionMethod();
            $controller->postDispatch();

            if ($this->_response->isDispatched()) {
                break;
            }
        }

        return $this;
    }

    public function send()
    {
        $this->_response->render();
    }
}