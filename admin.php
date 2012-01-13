<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */
define('DISABLE_IDENTITY_CHECK', true);
define('IN_ADMIN', true);
require_once dirname(__FILE__) .'/application/bootstrap.php';

$moduleList = array(
    'admin' => array(
        'Wootook_Admin_' => 'Wootook/Admin'
        ),
    'core' => array(
        'Wootook_Core_' => 'Wootook/Core'
        )
    );

$request = Wootook::getRequest();
$response = Wootook::getResponse();

$request->setModuleName('admin');

$dispatchCount = 0;
$classReflector = array();
$instances = array();
while (true) {
    $module = $request->getModuleName();
    $controller = $request->getControllerName();
    $action = $request->getActionName();

    if ($dispatchCount >= 100) {
        throw new Wootook_Core_Exception_RuntimeException(sprintf('Maximum of %d dispatched actions reached.', $dispatchCount));
    }

    $controllerClassSuffix = str_replace(' ', '', ucwords(str_replace('-', ' ', $controller))) . 'Controller';

    $moduleConfig = $moduleList[$module];
    $controllerFile = current($moduleConfig) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerClassSuffix . '.php';
    $controllerClass = key($moduleConfig) . $controllerClassSuffix;
    $actionMethod = str_replace(' ', '', ucwords(str_replace('-', ' ', $action))) . 'Action';

    if (!class_exists($controllerClass, false)) {
        if (!Wootook::fileExists($controllerFile)) {
            $request->setModuleName('core')
                ->setControllerName('error')
                ->setActionName('error');
            continue;
        }
        include $controllerFile;
    }

    if (!isset($classReflector[$controllerClass])) {
        $classReflector[$controllerClass] = new ReflectionClass($controllerClass);
    }

    $instance = $classReflector[$controllerClass]->newInstance($request, $response);
    $instance->init();

    $request->setIsDispatched(true);
    $instance->preDispatch();
    $reflector = $instance->$actionMethod();
    $instance->postDispatch();

    if ($request->isDispatched()) {
        echo $response->sendHeaders()->sendBody();
        break;
    }
}
