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


namespace Wootook\Core\App;

use Wootook\Core,
    Wootook\Core\Config\Adapter as ConfigAdapter,
    Wootook\Core\Database,
    Wootook\Core\Exception as CoreException,
    Wootook\Core\Mvc\Controller as MvcController,
    Wootook\Core\Profiler;
/**
 *
 */
class App
{
    const ENV_PRODUCTION  = 'prod';
    const ENV_INTEGRATION = 'inte';
    const ENV_TESTING     = 'test';
    const ENV_DEVELOPMENT = 'devel';

    const DOMAIN_INSTALL   = 'install';
    const DOMAIN_BACKEND   = 'backend';
    const DOMAIN_FRONTEND  = 'frontend';
    const DOMAIN_WEBSOCKET = 'websocket';

    const SCOPE_GLOBAL  = 'global';
    const SCOPE_WEBSITE = 'website';
    const SCOPE_GAME    = 'game';

    /**
     *
     * Enter description here ...
     * @var ConfigAdapter\Adapter
     */
    private $_rootConfig = null;
    private $_globalConfig = null;

    protected $_websiteList = array();
    protected $_gameList = array();

    protected $_defaultWebsiteId = 1;
    protected $_defaultGameId = 1;

    protected $_blockSingletons = array();
    protected $_modelSingletons = array();
    protected $_resourceSingletons = array();
    protected $_helperSingletons = array();

    protected $_frontController = null;

    protected $_connectionManager = null;

    public function __construct($configPath, $domain = self::DOMAIN_FRONTEND, $environment = self::ENV_PRODUCTION)
    {
        try {
            $this->_rootConfig = new ConfigAdapter\PhpArray();
            $this->_rootConfig->load($configPath . DIRECTORY_SEPARATOR . 'system.php');

            $localConfig = new ConfigAdapter\PhpArray();
            $localConfig->load($configPath . DIRECTORY_SEPARATOR . 'local.php');

            $this->_rootConfig->merge($localConfig);

            $directoryIterator = new \FilesystemIterator($configPath . DIRECTORY_SEPARATOR . 'modules');
            foreach ($directoryIterator as $file) {
                $modulesConfig = new ConfigAdapter\PhpArray();
                $modulesConfig->load($file->getPathname());

                $this->_rootConfig->merge($modulesConfig);
            }

            foreach ($this->_rootConfig->getConfig('global/modules') as $moduleName => $moduleConfig) {
                if (!$moduleConfig->getConfig('active')) {
                    continue;
                }

                $path = $moduleName . '/config/module.php';

                try {
                    $additionalModuleConfig = new ConfigAdapter\PhpArray();
                    $additionalModuleConfig->load($path);

                    $this->_rootConfig->merge($additionalModuleConfig);
                } catch (CoreException\DataAccessException $e) {
                    Profiler\ErrorProfiler::getSingleton()->addException($e);
                    continue;
                }
            }

            if (empty($domain) || !$this->_rootConfig[$domain]) {
                $domain = self::DOMAIN_FRONTEND;
            }

            if (empty($environment) || !$this->_rootConfig[$environment]) {
                $environment = self::ENV_PRODUCTION;
            }

            $this->_globalConfig = clone $this->_rootConfig['default'];

            $this->appendEnvironmentConfig($this->_globalConfig, 'global')
                ->appendEnvironmentConfig($this->_globalConfig, $environment)
                ->appendEnvironmentConfig($this->_globalConfig, $domain);
        } catch (CoreException\DataAccessException $e) {
            Profiler\ErrorProfiler::getSingleton()->addException($e);
        }
    }

    /**
     * @return MvcController\Front
     */
    public function getFrontController()
    {
        if ($this->_frontController === null) {
            $this->_frontController = new MvcController\Front($this);
        }
        return $this->_frontController;
    }

    /**
     * @return MvcController\Front
     */
    public function getConnectionManager()
    {
        if ($this->_connectionManager === null) {
            $this->_connectionManager = new Database\ConnectionManager($this);
        }
        return $this->_connectionManager;
    }

    /**
     * @param MvcController\Front $frontController
     * @return Core\App
     */
    public function setFrontController(MvcController\Front $frontController)
    {
        $this->_frontController = $frontController;

        return $this;
    }

    public function appendEnvironmentConfig(Core\Config\Node $config, $environment)
    {
        if (isset($this->_rootConfig[$environment])) {
            $config->merge($this->_rootConfig[$environment]);
        }

        return $this;
    }

    public function appendDatabaseConfig(Core\Config\Node $config, $scope = self::SCOPE_GLOBAL, $scopeId = 0)
    {
        if (empty($scope)) {
            $scope = self::SCOPE_GLOBAL;
        }

        if ($scope !== self::SCOPE_GLOBAL && $scopeId <= 0) {
            $scope = self::SCOPE_GLOBAL;
            Profiler\ErrorProfiler::getSingleton()
                ->addException(new CoreException\RuntimeException('Please specify a valid scope ID.'));
        }

        if ($scope === self::SCOPE_GLOBAL && $scopeId != 0) {
            $scopeId = 0;
        }

        $readAdapter = $this->getConnectionManager()
            ->getConnection('core_read');

        $select = $readAdapter->select()
            ->column(array('path' => 'config_path', 'value' => 'config_value'))
            ->from(array('config' => $readAdapter->getTable('core_config')))
        ;
        switch ($scope) {
        case self::SCOPE_GLOBAL:
            $select->where('game_id', 0)->where('website_id', 0);
            break;
        case self::SCOPE_WEBSITE:
            $select->where('website_id', $scopeId);
            break;
        case self::SCOPE_GAME:
            $select->where('game_id', $scopeId);
            break;
        }
        /** @var Core\Database\Statement\Statement $statement */
        $statement = $select->prepare();

        $statement->execute();
        foreach ($statement as $row) {
            $config->setConfig($row['path'], $row['value']);
        }

        return $this;
    }

    public function setDefaultWebsiteId($websiteIdentifier)
    {
        $this->_defaultWebsiteId = $websiteIdentifier;

        return $this;
    }

    public function getDefaultWebsiteId()
    {
        return $this->_defaultWebsiteId;
    }

    /**
     * @return \Wootook\Core\Model\Website
     */
    public function getDefaultWebsite()
    {
        return $this->getWebsite($this->_defaultWebsiteId);
    }

    public function setDefaultGameId($gameIdentifier)
    {
        $this->_defaultGameId = $gameIdentifier;

        return $this;
    }

    public function getDefaultGameId()
    {
        return $this->_defaultGameId;
    }

    public function getDefaultGame()
    {
        return $this->getGame($this->_defaultGameId);
    }

    /**
     * @param string|int $websiteIdentifier
     * @return \Wootook\Core\Model\Website
     */
    public function getWebsite($websiteIdentifier)
    {
        if (!isset($this->_websiteList[$websiteIdentifier])) {
            /** @var Model\Website $website */
            //$website = $this->getModel('core', 'website', array($this));
            $website = new Model\Website($this);

            if (is_numeric($websiteIdentifier)) {
                $website->load($websiteIdentifier);
            } else if ($websiteIdentifier !== null) {
                $website->load($websiteIdentifier, 'code');
            } else {
                throw new CoreException\RuntimeException('Website identifier should be specified.');
            }

            $this->_websiteList[$website->getId()] = $website;
            $this->_websiteList[$website->getCode()] = $website;
        }

        return $this->_websiteList[$websiteIdentifier];
    }

    /**
     * @param string|int $gameIdentifier
     * @return \Wootook\Core\Model\Game
     */
    public function getGame($gameIdentifier)
    {
        if (!isset($this->_gameList[$gameIdentifier])) {
            /** @var Model\Game $game */
            //$game = $this->getModel('core', 'game', array($this));
            $game = new Model\Game($this);

            if (is_numeric($gameIdentifier)) {
                $game->load($gameIdentifier);
            } else if ($gameIdentifier !== null) {
                $game->load($gameIdentifier, 'code');
            } else {
                throw new CoreException\RuntimeException('Game identifier should be specified.');
            }

            $this->_gameList[$game->getId()] = $game;
            $this->_gameList[$game->getCode()] = $game;
        }

        return $this->_gameList[$gameIdentifier];
    }

    public function getGlobalConfig($path = null)
    {
        if ($this->_globalConfig === null) {
            return null;
        }
        if ($path !== null) {
            return $this->_globalConfig->getConfig($path);
        }
        return $this->_globalConfig;
    }

    protected function _resolveClassType(&$namespaces, $module, $class)
    {
        $classSuffix = str_replace(' ', '', ucwords(str_replace('-', ' ', $class)));
        $classSuffix = str_replace(' ', '_', ucwords(str_replace('.', ' ', $classSuffix)));

        if (isset($namespaces[$module])) {
            foreach ($namespaces[$module] as $namespace => $path) {
                $className = $namespace . $classSuffix;
                $fileName = $path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $classSuffix) . '.php';

                Profiler\ErrorProfiler::getSingleton()->sleep();
                if (!($fp = @fopen($fileName, 'r', true))) {
                    Profiler\ErrorProfiler::getSingleton()->wakeup();
                    continue;
                }
                Profiler\ErrorProfiler::getSingleton()->wakeup();
                fclose($fp);

                if (!class_exists($className, true)) {
                    continue;
                }

                return $className;
            }
        }

        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException(sprintf('Class type "%1$s/%2$s" could not be resolved.', $module, $class)));
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

        $module = substr($identifier, 0, $offset);
        $class  = substr($identifier, $offset + 1);
    }

    public function block($identifier, Array $constructorParams = array())
    {
        $this->_parseIdentifier($identifier, $module, $class);

        if ($class === null) {
            $class = 'view';
        }

        return $this->getBlock($module, $class, $constructorParams);
    }

    public function model($identifier, Array $constructorParams = array())
    {
        $this->_parseIdentifier($identifier, $module, $class);

        if ($class === null) {
            $class = 'entity';
        }

        return $this->getModel($module, $class, $constructorParams);
    }

    public function resource($identifier, Array $constructorParams = array())
    {
        $this->_parseIdentifier($identifier, $module, $class);

        if ($class === null) {
            $class = 'entity';
        }

        return $this->getResource($module, $class, $constructorParams);
    }

    public function helper($identifier, Array $constructorParams = array())
    {
        $this->_parseIdentifier($identifier, $module, $class);

        if ($class === null) {
            $class = 'data';
        }

        return $this->getResource($module, $class, $constructorParams);
    }

    public function getBlock($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->getConfig('blocks'), $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getModel($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->getConfig('models'), $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getResource($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->getConfig('resources'), $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getHelper($module, $class, Array $constructorParams = array())
    {
        $className = $this->_resolveClassType($this->_globalConfig->getConfig('helpers'), $module, $class);

        return $this->_newInstance($className, $constructorParams);
    }

    public function getBlockSingleton($module, $class, Array $constructorParams = array())
    {
        if (!isset($this->_blockSingletons[$module])) {
            $this->_blockSingletons[$module] = array();
        }
        if (!isset($this->_blockSingletons[$module][$class])) {
            $this->_blockSingletons[$module][$class] = $this->getBlock($module, $class, $constructorParams);
        }
        return $this->_blockSingletons[$module][$class];
    }

    public function getModelSingleton($module, $class, Array $constructorParams = array())
    {
        if (!isset($this->_modelSingletons[$module])) {
            $this->_modelSingletons[$module] = array();
        }
        if (!isset($this->_modelSingletons[$module][$class])) {
            $this->_modelSingletons[$module][$class] = $this->getModel($module, $class, $constructorParams);
        }
        return $this->_modelSingletons[$module][$class];
    }

    public function getResourceSingleton($module, $class, Array $constructorParams = array())
    {
        if (!isset($this->_resourceSingletons[$module])) {
            $this->_resourceSingletons[$module] = array();
        }
        if (!isset($this->_resourceSingletons[$module][$class])) {
            $this->_resourceSingletons[$module][$class] = $this->getResource($module, $class, $constructorParams);
        }
        return $this->_resourceSingletons[$module][$class];
    }

    public function getHelperSingleton($module, $class, Array $constructorParams = array())
    {
        if (!isset($this->_helperSingletons[$module])) {
            $this->_helperSingletons[$module] = array();
        }
        if (!isset($this->_helperSingletons[$module][$class])) {
            $this->_helperSingletons[$module][$class] = $this->getHelper($module, $class, $constructorParams);
        }
        return $this->_helperSingletons[$module][$class];
    }
}
