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

/**
 * Page layout class utility.
 * Builds page structures from flexible layout declaration files, written in a
 * user-friendly XML format.
 *
 * @author      Greg <g.planchat@gmail.com>
 * @since       1.5.0
 * @package     Wootook
 * @subpackage  Wootook_Core
 * @category    layout
 */
class Wootook_Core_Model_Layout
    extends Wootook_Core_Mvc_Model_Model
{
    const DEFAULT_PACKAGE = 'base';
    const DEFAULT_THEME = 'default';

    const DOMAIN_FRONTEND = 'frontend';
    const DOMAIN_BACKEND = 'backend';

    /** @var array */
    protected $_blocks = array();

    /** @var Wootook_Core_Mvc_View_View */
    protected $_messageBlock = null;

    /** @var Wootook_Core_Mvc_View_View */
    protected $_rootView = null;

    /** @var string */
    protected $_eventPrefix = 'layout';

    /** @var string */
    protected $_eventObject = 'layout';

    /** @var string */
    protected $_domain = self::DOMAIN_FRONTEND;

    /** @var string */
    protected $_package = self::DEFAULT_PACKAGE;

    /** @var string */
    protected $_theme = self::DEFAULT_THEME;

    /** @var array */
    protected $_namespaces = array(
        'core' => array(
            'Wootook_Core_Block_' => 'Wootook/Core/Block'
            )
        );

    /**
     * Page layout class utility.
     *
     * @param string $domain
     * @param string $package
     * @param string $theme
     * @param array $data
     */
    public function __construct($domain = null, $package = null, $theme = null, Array $data = array())
    {
        if ($domain === null) {
            if (Wootook::getDefaultWebsite()->getId() == 0) {
                $this->_domain = self::DOMAIN_BACKEND;
            } else {
                $this->_domain = self::DOMAIN_FRONTEND;
            }
        } else {
            $this->_domain = $domain;
        }
        if ($package !== null) {
            $this->setPackage($package);
        }
        if ($theme !== null) {
            $this->setTheme($theme);
        }

        parent::__construct($data);
    }

    public function _init()
    {
        $modules = Wootook_Core_Helper_Config_Modules::getSingleton();

        foreach ($modules['block'] as $module => $moduleNamespaces) {
            foreach ($moduleNamespaces as $namespace => $path) {
                $this->registerBlockNamespace($module, $namespace, $path);
            }
        }

        $fileList = Wootook::getGameConfig('layout');
        if ($fileList instanceof Wootook_Core_Config_Node) {
            $fileList = $fileList->toArray();
        }
        if (!is_array($fileList) || empty($fileList)) {
            $fileList = array();
        }
        $this->_data = array();

        $this->setPackage(Wootook::getWebsiteConfig('package'));
        $this->setTheme(Wootook::getGameConfig('theme'));

        $parser = new Wootook_Core_Model_Layout_Parser();
        foreach ($fileList as $layoutFile) {
            try {
                if (preg_match('#\.php$#', $layoutFile)) {
                    $layoutData = include $this->_getLayoutPath($layoutFile);
                } else {
                    $layoutData = $parser->parse($this->_getLayoutPath($layoutFile), $this->_getLayoutCachePath($layoutFile), 3600);
                }
            } catch (Wootook_Core_Exception_LayoutException $e) {
                Wootook_Core_ErrorProfiler::getSingleton()->addException($e);
                continue;
            }

            if (!is_array($layoutData)) {
                continue;
            }

            foreach ($layoutData as $layoutId => $layoutConfig) {
                if ($this->hasData($layoutId)) {
                    $declared = $this->getData($layoutId);

                    if (isset($declared['block']) && !isset($layoutConfig['block'])) {
                        $layoutConfig['block'] = $declared['block'];
                    }

                    if (isset($declared['reference'])) {
                        if (isset($layoutConfig['reference'])) {
                            $layoutConfig['reference'] = array_merge($declared['reference'], $layoutConfig['reference']);
                        } else {
                            $layoutConfig['reference'] = $declared['reference'];
                        }
                    }
                }

                $this->setData($layoutId, $layoutConfig);
            }
        }

        $this->_messageBlock = $this->createBlock('core/messages', 'messages');

        return $this;
    }

    protected function _getAllParents(&$layoutConfigs, $currentHandle)
    {
        foreach ($currentHandle as $parent) {
            $currentLayout = $this->getData($parent);
            $layoutConfigs[] = $currentLayout;

            if (!isset($currentLayout['update'])) {
                continue;
            }
            $this->_getAllParents($layoutConfigs, $currentLayout['update']);
        }
    }

    protected function _load()
    {
        $layoutId = func_get_arg(0);

        if (!$this->hasData($layoutId)) {
            return $this;
        }

        $layoutConfigs = array($this->getData($layoutId));
        if (isset($layoutConfigs[0]['update'])) {
            $this->_getAllParents($layoutConfigs, $layoutConfigs[0]['update']);
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

            $this->_rootView = $this->_createBlock($type, $name, $layoutConfig);
        } else if (!isset($this->_rootView)) {
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
        throw new Wootook_Core_Exception_LayoutException('Save not permitted for the layout manager.');
    }

    protected function _delete()
    {
        throw new Wootook_Core_Exception_LayoutException('Delete not permitted for the layout manager.');
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

    /**
     * Register a new block namespage for use in the layout declaration files
     *
     * @since 1.5.0
     * @param string $module
     * @param string $namespace
     * @param string|null $path
     * @return Wootook_Core_Model_Layout
     */
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

    /**
     * Unregister a previously declared block namespace
     *
     * @since 1.5.0
     * @param string $module
     * @param string $namespace
     * @return Wootook_Core_Model_Layout
     */
    public function unregisterBlockNamespace($module, $namespace)
    {
        if (isset($this->_namespaces[$module]) && isset($this->_namespaces[$module][$namespace])) {
            unset($this->_namespaces[$module][$namespace]);
        }

        return $this;
    }

    /**
     * Create a new block instance
     *
     * @param string $type
     * @param string $name
     * @param array $config
     * @return Wootook_Core_Mvc_View_View
     */
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
                    $instance->$alias = new Wootook_Core_Mvc_View_View($config);
                } else {
                    $child = $this->_createBlock($config['type'], $name, $config);
                    if ($child !== null) {
                        $instance->$alias = $child;
                    }
                }
            }
        } catch (ReflectionException $e) {
            Wootook_Core_ErrorProfiler::getSingleton()
                ->addException($e);
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
                    $paramterName = strtolower(preg_replace('#([A-Z])#', '-\\1', $parameter->getName()));
                    $paramterPosition = $parameter->getPosition();

                    if (isset($params[$paramterName])) {
                        $callParamaters[$paramterPosition] = $params[$paramterName];
                    } else if ($parameter->isDefaultValueAvailable()) {
                        $callParamaters[$paramterPosition] = $parameter->getDefaultValue();
                    //} else if (!$parameter->isOptionnal()) {
                    } else if ($paramterPosition <= $requiredParameterCount) {
                        throw new Wootook_Core_Exception_LayoutException(Wootook::__('Method %s requires parameter %d ($%s) to be defined.',
                            $reflectionMethod->getName(), $paramterPosition + 1, $paramterName));
                    }
                }

                $reflectionMethod->invokeArgs($block, $callParamaters);
            } catch (ReflectionException $e) {
                Wootook_Core_ErrorProfiler::getSingleton()
                    ->addException($e);
                continue;
            } catch (RuntimeException $e) {
                Wootook_Core_ErrorProfiler::getSingleton()
                    ->addException($e);
                continue;
            }
        }
    }

    /**
     * Get the current Layout domain (frontend or backend)
     *
     * @since 1.5.0
     * @return string
     */
    public function getDomain()
    {
        return $this->_domain;
    }

    /**
     * Set the current Layout domain (frontend or backend)
     *
     * @since 1.5.0
     * @param string $domain
     * @return Wootook_Core_Model_Layout
     */
    public function setDomain($domain)
    {
        $this->_domain = $domain;
        $this->_init();

        return $this;
    }

    public function getPackage()
    {
        if ($this->_package === null) {
            $this->_package = self::DEFAULT_PACKAGE;
        }
        return $this->_package;
    }

    public function setPackage($package)
    {
        $this->_package = $package;

        return $this;
    }

    public function getTheme()
    {
        if ($this->_theme === null) {
            $this->_theme = self::DEFAULT_THEME;
        }
        return $this->_theme;
    }

    public function setTheme($theme)
    {
        $this->_theme = $theme;

        return $this;
    }

    public function getScriptPath()
    {
        return Wootook::getBasePath('design') . DIRECTORY_SEPARATOR . $this->getDomain();
    }

    public function getAllBlocks()
    {
        return $this->_blocks;
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
        if (!$this->_rootView instanceof Wootook_Core_Mvc_View_View) {
            throw new Wootook_Core_Exception_LayoutException(Wootook::__('No root view declared.'));
        }

        $scriptPath = $this->getScriptPath();
        foreach ($this->_blocks as $block) {
            if ($block instanceof Wootook_Core_Block_Template) {
                $block->setScriptPath($scriptPath);
            }
            $block->beforeToHtml();
        }
        unset($block);

        return $this->_rootView->render();
    }

    protected function _getLayoutCachePath($file)
    {
        $package = $this->getPackage();
        $theme = $this->getTheme();
        $pattern = APPLICATION_PATH . "cache/" . __CLASS__ . "--{$this->getDomain()}-%s-%s-{$file}.cache";

        if (empty($package)) {
            $package = self::DEFAULT_PACKAGE;
        }
        if (empty($theme)) {
            $theme = self::DEFAULT_THEME;
        }

        return sprintf($pattern, $package, $theme);
    }

    protected function _getLayoutPath($file)
    {
        $package = $this->getPackage();
        $theme = $this->getTheme();
        $pattern = "{$this->getScriptPath()}/%s/%s/layouts/{$file}";

        if ($package !== null && $theme !== null) {
            $path = sprintf($pattern, $package, $theme);
            if (Wootook::fileExists($path)) {
                return $path;
            }
        }

        if ($package !== null) {
            $path = sprintf($pattern, $package, self::DEFAULT_THEME);
            if (Wootook::fileExists($path)) {
                return $path;
            }
        }

        $path = sprintf($pattern, self::DEFAULT_PACKAGE, self::DEFAULT_THEME);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        throw new Wootook_Core_Exception_LayoutException(sprintf("Layout file '%s' does not exist.", $file));
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
