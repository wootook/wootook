<?php

class Wootook_Core_Layout
    extends Wootook_Core_Model
{
    const DEFAULT_PACKAGE = 'base';
    const DEFAULT_THEME = 'default';

    protected $_blocks = array();

    protected $_messageBlock = null;

    protected $_view = null;
    protected $_eventPrefix = 'layout';
    protected $_eventObject = 'layout';

    protected $_package = self::DEFAULT_PACKAGE;
    protected $_theme = self::DEFAULT_THEME;

    protected $_namespaces = array(
        'core' => array(
            'Wootook_Core_Block_' => 'Wootook/core/Block'
            )
        );

    public function _init()
    {
        $modules = Wootook_Core_Model_Config_Modules::getSingleton();

        foreach ($modules['block'] as $module => $moduleNamespaces) {
            foreach ($moduleNamespaces as $namespace => $path) {
                $this->registerBlockNamespace($module, $namespace, $path);
            }
        }

        $fileList = Wootook::getConfig('global/layout');
        if (!is_array($fileList) || empty($fileList)) {
            $fileList = $this->getAllDatas();
        } else {
            $fileList = array_merge($fileList, $this->getAllDatas());
        }
        $this->_data = array();

        $this->setPackage(Wootook::getConfig('global/package'));
        $this->setTheme(Wootook::getConfig('global/theme'));

        foreach ($fileList as $layoutFile) {
            foreach (include $this->_getLayoutPath($layoutFile) as $layoutId => $layoutConfig) {
                if ($this->hasData($layoutId)) {
                    $declared = $this->getData($layoutId);

                    $layoutConfig = array_merge($declared, $layoutConfig);
                }

                $this->setData($layoutId, $layoutConfig);
            }
        }

        $this->_messageBlock = $this->createBlock('core/messages', 'messages');

        return $this;
    }

    protected function _load()
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
            if (!is_array($config)) {
                continue;
            }
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

        if (isset($layoutConfig['type'])) {
            $type = $layoutConfig['type'];
            $name = isset($layoutConfig['name']) ? $layoutConfig['name'] : 'root';

            $this->_view = $this->_createBlock($type, $name, $layoutConfig);
        } else if (!isset($this->_view)) {
            return $this;
        }

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

        Wootook::dispatchEvent($this->_eventPrefix . '.prepare.before', array(
            $this->_eventObject => $this
            ));

        foreach ($this->_blocks as $block) {
            $block->prepareLayout();
        }

        Wootook::dispatchEvent($this->_eventPrefix . '.prepare.after', array(
            $this->_eventObject => $this
            ));
    }

    protected function _save()
    {
    }

    protected function _delete()
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

        /*
        $module = str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
        $module = str_replace(' ', '_', ucwords(str_replace('.', ' ', $module)));
        */
        $block = str_replace(' ', '', ucwords(str_replace('-', ' ', $block)));
        $block = str_replace(' ', '_', ucwords(str_replace('.', ' ', $block)));

        if (isset($this->_namespaces[$module])) {
            foreach ($this->_namespaces[$module] as $namespace => $path) {
                $className = $namespace . $block;
                $fileName = $path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $block) . '.php';

                Wootook_Core_ErrorProfiler::sleep();
                if (!($fp = @fopen($fileName, 'r', true))) {
                    Wootook_Core_ErrorProfiler::wakeup();
                    continue;
                }
                Wootook_Core_ErrorProfiler::wakeup();
                fclose($fp);

                if (!class_exists($className, true)) {
                    continue;
                }

                return $className;
            }
        }

        trigger_error(Wootook::__('Class type "%s" could not be resolved.', $type), E_USER_NOTICE);
        return null;
    }

    public function registerBlockNamespace($module, $namespace, $path = null)
    {
        if ($path === null) {
            $path = str_replace('_', DIRECTORY_SEPARATOR, $namespace);
        }
        if (!isset($this->_namespaces[$module])) {
            $this->_namespaces[$module] = array();
        }
        $this->_namespaces[$module][$namespace] = $path;

        return $this;
    }

    public function unregisterBlockNamespace($module, $namespace)
    {
        if (isset($this->_namespaces[$module]) && isset($this->_namespaces[$module][$namespace])) {
            unset($this->_namespaces[$module][$namespace]);
        }

        return $this;
    }

    public function createBlock($type, $name, $config = array())
    {
        $instance = $this->_createBlock($type, $name, $config);

        if ($instance === null) {
            return null;
        }
        $instance->prepareLayout();

        return $instance;
    }

    protected function _createBlock($type, $name, $config = array())
    {
        $className = $this->_resolveBlockClassType($type);

        if ($className === null) {
            return null;
        }

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
                    $instance->$alias = new Wootook_Core_View($config);
                } else {
                    $child = $this->_createBlock($config['type'], $name, $config);
                    if ($child !== null) {
                        $instance->$alias = $child;
                    }
                }
            }
        } catch (ReflectionException $e) {
            trigger_error($e->getMessage(), E_USER_NOTICE);
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
                        throw new Wootook_Core_Exception_RuntimeException(Wootook::__('Method %s requires parameter %d ($%s) to be defined.',
                            $reflectionMethod->getName(), $paramterPosition + 1, $paramterName));
                    }
                }

                $reflectionMethod->invokeArgs($block, $callParamaters);
            } catch (ReflectionException $e) {
                trigger_error($e->getMessage(), E_USER_NOTICE);
                continue;
            } catch (RuntimeException $e) {
                trigger_error($e->getMessage(), E_USER_WARNING);
                continue;
            }
        }
    }

    public function getPackage()
    {
        return $this->_package;
    }

    public function setPackage($package)
    {
        $this->_package = $package;

        return $this;
    }

    public function getTheme()
    {
        return $this->_theme;
    }

    public function setTheme($theme)
    {
        $this->_theme = $theme;

        return $this;
    }

    public function getScriptPath()
    {
        return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'design';
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
        if (!$this->_view instanceof Wootook_Core_View) {
            throw new Wootook_Core_Exception_RuntimeException(Wootook::__('No root view declared.'));
        }

        $scriptPath = $this->getScriptPath();
        foreach ($this->_blocks as $block) {
            if ($block instanceof Wootook_Core_Block_Template) {
                $block->setScriptPath($scriptPath);
            }
            $block->beforeToHtml();
        }
        unset($block);

        return $this->_view->render();
    }

    protected function _getLayoutPath($file)
    {
        $package = $this->getPackage();
        $theme = $this->getTheme();
        $pattern = "{$this->getScriptPath()}/%s/%s/layouts/{$file}";

        $path = sprintf($pattern, $package, $theme);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        $path = sprintf($pattern, $package, self::DEFAULT_THEME);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        $path = sprintf($pattern, self::DEFAULT_PACKAGE, self::DEFAULT_THEME);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        trigger_error(Wootook::__("Layout file '%s' does not exist.", $file), E_USER_NOTICE);

        return null;
    }

    /**
     *
     * @return Wootook_Core_Block_Messages
     */
    public function getMessagesBlock()
    {
        return $this->_messageBlock;
    }
}