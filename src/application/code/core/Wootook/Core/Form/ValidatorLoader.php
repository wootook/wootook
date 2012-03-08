<?php

class Wootook_Core_Form_ValidatorLoader
    extends Wootook_Core_PluginLoader_PluginLoader
{
    protected $_form = null;

    public function __construct(Wootook_Core_Form $form, Array $namespaces = array())
    {
        $this->_form = $form;

        foreach ($namespaces as $namespace => $path) {
            if (is_int($namespace)) {
                $this->registerNamespace($path);
            } else {
                $this->registerNamespace($namespace, $path);
            }
        }
    }

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new ReflectionClass($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook_Core_Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            return $method->invoke(null);
        }

        try {
            return $reflection->newInstance();
        } catch (ReflectionException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()
                ->addException($e);
            return null;
        }
    }
}