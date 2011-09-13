<?php

class Legacies_Core_Layout
    extends Legacies_Core_Model
{
    protected $_blocks = array();

    protected $_view = null;
    protected $_eventPrefix = 'layout';
    protected $_eventObject = 'layout';

    public function _init()
    {
        $path = APPLICATION_PATH . 'design/layouts/';
        $config = include ROOT_PATH . 'config.php';
        $fileList = $config['global']['layout'];

        foreach ($fileList as $layoutFile) {
            foreach (include $path . $layoutFile as $layoutId => $layoutConfig) {
                if ($this->hasData($layoutId)) {
                    $declared = $this->getData($layoutId);

                    $layoutConfig = array_merge($declared, $layoutConfig);
                }

                $this->setData($layoutId, $layoutConfig);
            }
        }

        return $this;
    }

    public function _load()
    {
        $layoutId = func_get_arg(0);

        if (!$this->hasData($layoutId)) {
            return $this;
        }

        $layoutConfigs = array($this->getData($layoutId));
        if (isset($layoutConfigs[0]['update'])) {
            $parent = $layoutConfigs[0]['update'];

            $i = 0;
            while (true) {
                $layoutConfigs[++$i] = $this->getData($parent);

                if (!isset($layoutConfigs[$i]['update'])) {
                    break;
                }
                $parent = $layoutConfigs[$i]['update'];
            }
        }

        $layoutUpdates = array();
        $layoutConfig = array();
        foreach (array_reverse($layoutConfigs) as $config) {
            $layoutConfig = array_merge($layoutConfig, $config);

            if (isset($layoutConfig['reference'])) {
                foreach ($layoutConfig['reference'] as $name => $update) {
                    if (!isset($layoutUpdates[$name])) {
                        $layoutUpdates[$name] = array();
                    }
                    $layoutUpdates[$name][] = $update;
                }
                unset($layoutConfig['reference']);
            }
        }
        unset($layoutConfigs, $layoutConfig['update']);

        $type = $layoutConfig['type'];
        $name = isset($layoutConfig['name']) ? $layoutConfig['name'] : 'root';

        $this->_view = $this->_createBlock($type, $name, $layoutConfig);

        foreach ($layoutUpdates as $block => $updates) {
            if (!isset($this->_blocks[$block])) {
                continue;
            }

            $parent = $this->_blocks[$block];
            foreach ($updates as $update) {
                if (isset($update['children'])) {
                    foreach ($update['children'] as $childName => $childConfig) {
                        $type = $childConfig['type'];
                        $alias = isset($childConfig['alias']) ? $childConfig['alias'] : $childName;

                        $parent->$alias = $this->_createBlock($type, $childName, $childConfig);
                    }
                }
            }
        }

        foreach ($layoutUpdates as $block => $updates) {
            if (!isset($this->_blocks[$block])) {
                continue;
            }

            foreach ($updates as $update) {
                if (isset($update['actions'])) {
                    $this->_callActions($this->_blocks[$block], $update['actions']);
                }
            }
        }

        foreach ($this->_blocks as $block) {
            $block->prepareLayout();
        }
    }

    public function _save()
    {
    }

    public function _delete()
    {
    }

    protected function _resolveBlockClassType($type)
    {
        $offset = strpos($type, '/');
        if ($offset === false) {
            return null;
        }

        $module = substr($type, 0, $offset);
        $block  = substr($type, $offset + 1);

        $module = str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
        $module = str_replace(' ', '_', ucwords(str_replace('.', ' ', $module)));
        $block = str_replace(' ', '', ucwords(str_replace('-', ' ', $block)));
        $block = str_replace(' ', '_', ucwords(str_replace('.', ' ', $block)));

        return 'Legacies_' . $module . '_Block_' . $block;
    }

    public function createBlock($type, $name, $config = array())
    {
        $instance = $this->_createBlock($type, $name, $config);

        $instance->prepareLayout();

        return $instance;
    }

    protected function _createBlock($type, $name, $config = array())
    {
        $className = $this->_resolveBlockClassType($type);

        $children = array();
        if (isset($config['children'])) {
            $children = $config['children'];
            unset($config['children']);
        }
        $actions = array();
        if (isset($config['actions'])) {
            $actions = $config['actions'];
            unset($config['actions']);
        }

        try {
            $reflectionClass = new ReflectionClass($className);

            $instance = $reflectionClass->newInstance($config);
            $this->_blocks[$name] = $instance;
            $instance->setNameInLayout($name);

            $instance->setLayout($this);

            foreach ($children as $name => $config) {
                if (isset($config['alias'])) {
                    $alias = $config['alias'];
                } else {
                    $alias = $name;
                }

                if (!isset($config['type'])) {
                    $instance->$alias = new Legacies_Core_View($config);
                } else {
                    $instance->$alias = $this->_createBlock($config['type'], $name, $config);
                }
            }
        } catch (ReflectionException $e) {
            var_dump($e);
            return null;
        }

        $this->_callActions($instance, $actions);

        return $instance;
    }

    protected function _callActions($block, $actions)
    {
        $reflectionClass = new ReflectionClass($block);

        foreach ($actions as $action) {
            if (!isset($action['method'])) {
                continue;
            }
            $method = $action['method'];

            $params = array();
            if (isset($action['params'])) {
                $params = $action['params'];
            }

            try {
                $reflectionMethod = $reflectionClass->getMethod($method);
                $requiredParameterCount = $reflectionMethod->getNumberOfRequiredParameters();
                $callParamaters = array();
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    $paramterName = $parameter->getName();
                    $paramterPosition = $parameter->getPosition();

                    if (isset($params[$paramterName])) {
                        $callParamaters[$paramterPosition] = $params[$paramterName];
                    } else if ($parameter->isDefaultValueAvailable()) {
                        $callParamaters[$paramterPosition] = $parameter->getDefaultValue();
                    //} else if (!$parameter->isOptionnal()) {
                    } else if ($paramterPosition <= $requiredParameterCount) {
                        throw new RuntimeException();
                    }
                }

                $reflectionMethod->invokeArgs($block, $callParamaters);
            } catch (ReflectionException $e) {
                continue;
            } catch (RuntimeException $e) {
                continue;
            }
        }
    }

    public function getBlock($code)
    {
        if (isset($this->_blocks[$code])) {
            return $this->_blocks[$code];
        }
        return null;
    }

    public function render()
    {
        foreach ($this->_blocks as $block) {
            $block->beforeToHtml();
        }
        unset($block);

        return $this->_view->render();
    }
}