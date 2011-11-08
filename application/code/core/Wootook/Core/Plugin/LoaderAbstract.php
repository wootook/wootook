<?php

abstract class Wootook_Core_Plugin_LoaderAbstract
{
    protected $_pluginInstances = array();
    protected $_namespaces = array();

    public function load($pluginName, $useSingleton = false)
    {
        if (isset($this->_pluginInstances[$pluginName])) {
            return $this->_pluginInstances[$pluginName];
        }

        foreach ($this->_namespaces as $namespace => $path) {
            $className = $namespace . ucfirst($pluginName);
            $fileName = $path . DIRECTORY_SEPARATOR . ucfirst($pluginName) . '.php';

            if (!Wootook::fileExists($fileName)) {
                continue;
            }

            if (!class_exists($className, true)) {
                continue;
            }

            return $this->_load($className, $useSingleton);
        }

        return null;
    }

    public function registerNamespace($namespace, $path = null)
    {
        if ($path === null) {
            $path = str_replace('_', DIRECTORY_SEPARATOR, $namespace);
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

    abstract protected function _load($className, $useSingleton);

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