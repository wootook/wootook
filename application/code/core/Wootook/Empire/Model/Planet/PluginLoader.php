<?php

class Wootook_Empire_Model_Planet_PluginLoader
    extends Wootook_Core_Plugin_LoaderAbstract
{
    protected $_currentUser = null;
    protected $_currentPlanet = null;

    public function __construct($currentPlanet, $currentUser)
    {
        $this->_currentUser = $currentUser;
        $this->_currentPlanet = $currentPlanet;
    }

    protected function _load($className, $useSingleton)
    {
        $reflection = new ClassReflection($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook_Core_Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            $method->invoke();
        }

        try {
            return $reflection->invoke($this->_currentPlanet, $this->_currentUser);
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
