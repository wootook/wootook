<?php

class Wootook_Core_Database_Orm_DataMapper
    extends Wootook_Core_PluginLoader_PluginLoader
{
    protected $_rules = array();

    protected $_alias = array();

    public function __construct($config = array())
    {
        $this->registerNamespace('Wootook_Core_Database_Orm_DataMapper_');

        if (!is_array($config) || !$config instanceof Wootook_Core_Config_Node) {
            return;
        }

        foreach ($config as $field => $typeConfig) {
            if (is_string($typeConfig)) {
                $this->addRule($field, $typeConfig);
            } else if (is_array($typeConfig) && isset($typeConfig['type'])) {
                if (isset($typeConfig['alias'])) {
                    $this->addRule($field, $typeConfig['type'], $typeConfig['alias']);
                } else {
                    $this->addRule($field, $typeConfig['type']);
                }
            }
        }
    }

    public function addRule($field, $type, $alias = null)
    {
        if (($instance = $this->load($type)) !== null) {
            $this->_rules[$field] = $instance;
        }

        if ($alias === null) {
            $alias = $field;
        }

        $this->_alias[$field] = $alias;

        return $this;
    }

    public function removeRule($field)
    {
        unset($this->_rules[$field]);
        unset($this->_alias[$field]);

        return $this;
    }

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new ReflectionClass($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook_Core_Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            return $method->invoke(null);
        }

        try {
            return $reflection->newInstance($this);
        } catch (ReflectionException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()
                ->addException($e);
            return null;
        }
    }

    /**
     *
     * @param Wootook_Core_Database_Resource $entity
     * @param Array $datas
     * @return Array
     */
    public function encode(Wootook_Core_Database_Resource $entity, Array $datas = array())
    {
        $aliasTable = array_flip($this->_alias);
        foreach ($entity->getAllDatas() as $aliasedField => $decodedValue) {
            if (!isset($aliasTable[$aliasedField])) {
                $field = $aliasedField;
            } else {
                $field = $aliasTable[$aliasedField];
            }

            if (isset($this->_rules[$field])) {
                $datas[$field] = $this->_rules[$field]->encode($decodedValue);
            } else {
                $datas[$field] = $decodedValue;
            }
        }

        return $datas;
    }

    /**
     *
     * @param Wootook_Core_Database_Resource $entity
     * @param Array $datas
     * @return Wootook_Core_Database_Resource
     */
    public function decode(Wootook_Core_Database_Resource $entity, Array $datas = array())
    {
        foreach ($datas as $field => $encodedValue) {
            if (isset($this->_rules[$field])) {
                $entity->setData($this->_alias[$field], $this->_rules[$field]->decode($encodedValue));
            } else {
                $entity->setData($field, $encodedValue);
            }
        }

        return $entity;
    }
}