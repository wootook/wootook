<?php

class Wootook_Core_App
{
    /**
     *
     * Enter description here ...
     * @var Wootook_Core_Config_Adapter_Adapter
     */
    private $_globalConfig = null;

    public function __construct($websiteCode, $gameCode)
    {
        $this->_globalConfig = Wootook::getConfig();
    }

    protected function _resolveClassType(&$namespaces, $module, $class)
    {
        $class = str_replace(' ', '', ucwords(str_replace('-', ' ', $class)));
        $class = str_replace(' ', '_', ucwords(str_replace('.', ' ', $class)));

        if (isset($namespaces[$module])) {
            foreach ($namespaces[$module] as $namespace => $path) {
                $className = $namespace . $class;
                $fileName = $path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

                Wootook_Core_ErrorProfiler::getSingleton()->sleep();
                if (!($fp = @fopen($fileName, 'r', true))) {
                    Wootook_Core_ErrorProfiler::getSingleton()->wakeup();
                    continue;
                }
                Wootook_Core_ErrorProfiler::getSingleton()->wakeup();
                fclose($fp);

                if (!class_exists($className, true)) {
                    continue;
                }

                return $className;
            }
        }

        Wootook_Core_ErrorProfiler::getSingleton()
            ->addException(new Wootook_Core_Exception_LayoutException(sprintf('Class type "%s" could not be resolved.', $type)));
        return null;
    }

    protected function _newInstance($className, Array $constructorParams)
    {
        if ($className === null) {
            return null;
        }

        if (empty($constructorParams)) {
            return new $className;
        } else {
            $reflection = new ReflectionClass($className);
            return $reflection->newInstanceArgs($constructorParams);
        }
    }

    protected function _parseIdentifier($identifier, &$module, &$class)
    {
        $offset = strpos($identifier, '/');
        if ($offset === false) {
            $module = $identifier;
            $class = null;

            return;
        }

        $module = substr($type, 0, $offset);
        $class  = substr($type, $offset + 1);
    }

    public function block($identifier)
    {
        $this->_parseIdentifier($identifier, $module, $class);

        return $this->getBlock($module, $class);
    }

    public function model($identifier)
    {
        $this->_parseIdentifier($identifier, $module, $class);

        return $this->getModel($module, $class);
    }

    public function resource($identifier)
    {
        $this->_parseIdentifier($identifier, $module, $class);

        return $this->getResource($module, $class);
    }

    public function helper($identifier)
    {
        $this->_parseIdentifier($identifier, $module, $class);

        if ($class === null) {
            $class = 'data';
        }

        return $this->getResource($module, $class);
    }

    public function getBlock($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->blocks, $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getModel($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->models, $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getResource($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->resources, $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getHelper($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->helpers, $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getBlockSingleton($module, $class, Array $constructorParams = array())
    {

    }

    public function getModelSingleton($module, $class, Array $constructorParams = array())
    {

    }

    public function getResourceSingleton($module, $class, Array $constructorParams = array())
    {

    }

    public function getHelperSingleton($module, $class, Array $constructorParams = array())
    {

    }
}
