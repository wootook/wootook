<?php

class Wootook_Empire_Model_Planet_PluginLoader
    extends Wootook_Core_PluginLoader_PluginLoader
{
    protected $_currentPlayer = null;
    protected $_currentPlanet = null;

    public function __construct($currentPlanet, $currentPlayer)
    {
        $this->_currentPlayer = $currentPlayer;
        $this->_currentPlanet = $currentPlanet;

        $this->registerNamespace('Legacies_Empire_Model_Planet_Building_');
    }

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new ReflectionClass($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook_Core_Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            $method->invoke();
        }

        try {
            return $reflection->newInstance($this->_currentPlanet, $this->_currentPlayer);
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
