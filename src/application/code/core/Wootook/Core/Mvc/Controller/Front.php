<?php

class Wootook_Core_Mvc_Controller_Front
{
    const ROUTE_DEFAULT = 'default';
    const ROUTE_ERROR = 'error';

    // FIXME: Create a router class to manage all this mess
    protected $_routes = array(
        self::ROUTE_ERROR => array(
            'modules' => array(
                'core' => array(
                    'class' => 'Wootook_Core_Controller_',
                    'path'  => 'Wootook/Core/Controller'
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
        $string = strtolower($string);
        $string = str_replace(' ', '_', ucwords(str_replace('.', ' ', $string)));
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function _camelizePath($string)
    {
        $string = strtolower($string);
        $string = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('.', ' ', $string)));
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
        $modulePrefix = $this->_routes[$route]['modules'][$module]['class'];
        return $modulePrefix . $this->_camelizeClass($controllerKey) . 'Controller';
    }

    protected function _getControllerFilename($route, $module, $controllerKey)
    {
        if (!isset($this->_routes[$route])) {
            return null;
        }
        if (!isset($this->_routes[$route]['modules'][$module])) {
            return null;
        }
        $modulePath = $this->_routes[$route]['modules'][$module]['path'];

        return $modulePath . DIRECTORY_SEPARATOR . $this->_camelizePath($controllerKey) . 'Controller.php';
    }

    protected function _getActionMethod($actionKey)
    {
        return $this->_camelizeMethod($actionKey) . 'Action';
    }

    protected function _forward($action, $controller = null, $module = null)
    {
        $this->_request->setActionName($action);
        if ($controller !== null) {
            $this->_request->setControllerName($controller);
            if ($module != null) {
                $this->_request->setModuleName($module);
            }
        }
        $this->_response->setIsDispatched(false);
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

            $controllerKey = $this->_request->getControllerName();
            if (empty($controllerKey)) {
                $controllerKey = $this->_routes[$route]['defaults']['controller'];
            }

            $controllerClass = $this->_getControllerClass($route, $moduleKey, $controllerKey);
            $controllerPath = $this->_getControllerFilename($route, $moduleKey, $controllerKey);

            if (!class_exists($controllerClass, false) && Wootook::fileExists($controllerPath)) {
                include_once $controllerPath;
            }

            if (!class_exists($controllerClass, false)) {
                $this->_forward(
                    $this->_routes[self::ROUTE_ERROR]['defaults']['action'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['controller'],
                    $this->_routes[self::ROUTE_ERROR]['defaults']['module']
                );
                $route = self::ROUTE_ERROR;
                continue;
            }

            $actionKey = $this->_request->getActionName();
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

            $controller = new $controllerClass($this->_request, $this->_response);
            $controller->init();

            $this->_response->setIsDispatched();

            $this->preDispatch();
            $controller->preDispatch();
            if (!$this->_response->isDispatched()) {
                continue;
            }
            if ($this->_response->isRedirect()) {
                break;
            }

            $controller->$actionMethod();

            if (!$this->_response->isDispatched()) {
                continue;
            }

            $controller->postDispatch();
            $this->postDispatch();

            if ($this->_response->isDispatched() || $this->_response->isRedirect()) {
                break;
            }
        }

        return $this;
    }

    public function send()
    {
        $this->_response->render();
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
        Wootook::dispatchEvent('core.mvc.controller.front.pre-dispatch', array(
            'request'  => $this->_request,
            'response' => $this->_response
            ));

        return $this;
    }

    public function postDispatch()
    {
        Wootook::dispatchEvent('core.mvc.controller.front.post-dispatch', array(
            'request'  => $this->_request,
            'response' => $this->_response
        ));

        return $this;
    }
}
