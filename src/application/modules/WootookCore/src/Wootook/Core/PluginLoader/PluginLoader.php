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

namespace Wootook\Core\PluginLoader;

abstract class PluginLoader
{
    protected $_pluginInstances = array();
    protected $_namespaces = array();

    public function load($pluginName, $useSingleton = false, Array $constructorParams = array())
    {
        if (isset($this->_pluginInstances[$pluginName])) {
            return $this->_pluginInstances[$pluginName];
        }

        $pluginName = str_replace(' ', '\\', ucwords(str_replace('.', ' ', $pluginName)));
        $pluginName = str_replace(' ', '', ucwords(str_replace('-', ' ', $pluginName)));

        foreach ($this->_namespaces as $namespace => $path) {
            $className = $namespace . '\\' . $pluginName;
            $fileName = $path . DIRECTORY_SEPARATOR .
                str_replace('\\', DIRECTORY_SEPARATOR, $pluginName) . '.php';

            if (!class_exists($className, true)) {
                continue;
            }

            return $this->_load($className, $useSingleton, $constructorParams);
        }

        return null;
    }

    public function registerNamespace($namespace, $path = null)
    {
        if ($path === null) {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        }
        $this->_namespaces[$namespace] = $path;

        return $this;
    }

    public function unregisterNamespace($namespace)
    {
        if (isset($this->_namespaces[$namespace])) {
            unset($this->_namespaces[$namespace]);
        }

        return $this;
    }

    abstract protected function _load($className, $useSingleton, Array $constructorParams = array());

    public function getPlugin($pluginName)
    {
        return $this->load($pluginName);
    }

    public function setPlugin($pluginName, $pluginInstance)
    {
        $this->_pluginInstances[$pluginName] = $pluginInstance;

        return $this;
    }

    public function hasPlugin($pluginName)
    {
        return isset($this->_pluginInstances[$pluginName]);
    }

    public function unsetPlugin($pluginName)
    {
        unset($this->_pluginInstances[$pluginName]);

        return $this;
    }
}
