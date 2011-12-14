<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

define('INSIDE' , true);
define('INSTALL', false);
define('IN_INSTALL', true);

define('STEP_SYSTEM',   1);
define('STEP_DATABASE', 2);
define('STEP_UNIVERSE', 3);
define('STEP_PROFILE',  4);
define('STEP_CONFIG',   5);

require_once dirname(dirname(__FILE__)) . '/application/bootstrap.php';

$mode     = isset($_GET['mode']) ? strval($_GET['mode']) : 'intro';
$step     = isset($_GET['step']) ? intval($_GET['step']) : 1;
$prevStep = $step - 1;
$nextStep = $step + 1;

$modules = array(
    'Wootook_Core',
    'Wootook_Empire',
    'Legacies_Empire',
    'Legacies_Officers'
    );

includeLang('install/install');

$baseUrl = (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on" ? 'https://' : 'http://')
    . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != "80" ? ":{$_SERVER["SERVER_PORT"]}" : '');
if (isset($_SERVER['REQUEST_URI'])) {
    if (strrpos($_SERVER['REQUEST_URI'], '/') == (strlen($_SERVER['REQUEST_URI']) - 1)) {
        $baseUrl .= dirname($_SERVER['REQUEST_URI']) . '/';
    } else {
        $baseUrl .= dirname(dirname($_SERVER['REQUEST_URI'])) . '/';
    }
}

Wootook::setConfig('global/web/base_url', $baseUrl);

Wootook::setConfig('global/package', 'install');
Wootook::setConfig('global/theme', 'default');
Wootook::setconfig('global/layout', array(
    'page' => 'page.php',
    'install' => 'install.php'
    ));

$session = Wootook::getSession('install');
$layout = new Wootook_Core_Layout();
$request = new Wootook_Core_Controller_Request_Http();
$response = new Wootook_Core_Controller_Response_Http();

switch ($mode) {
case 'intro':
    $layout->load('install.intro');
    $layout->getMessagesBlock()->prepareMessages('install');
    $session->setData('step', 1);
    break;

case 'install':
    if ($step > $session->getData('step')) {
        $session->setData('step', 0);
        $response->setRedirect("?mode=intro", Wootook_Core_Controller_Response_Http::REDIRECT_TEMPORARY);
        $response->sendHeaders();
        exit(0);
    }

    switch ($step) {
    case STEP_SYSTEM:
        if ($request->isPost()) {
            $form = new Wootook_Core_Form($session, array(
                'url_path'      => 'text',
                'timezone'      => 'text'
                ));
            $form->setRequest($request);
            $form->populate();

            if (!$form->validate()) {
                $session->setData('step', STEP_SYSTEM);
                $session->setFormData($form->getData());
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_SYSTEM)));
                $response->sendHeaders();
                exit(0);
            }

            $config = array(
                'global' => array(
                    'storyline' => array(
                        'universe' => 'legacies',
                        'episode'  => 'default',
                        ),
                    'web' => array(
                        'base_url' => $request->getPost('url_path')
                        ),
                    'date' => array(
                        'timezone' => $request->getPost('timezone')
                        ),
                    'layout' => array(
                        'page'   => 'page.php',
                        'admin'  => 'admin.php',
                        'empire' => 'empire.php'
                        ),
                    'locales' => array(
                        'fr'    => 'fr_FR',
                        'fr_FR' => 'fr_FR',
                        'en'    => 'en_US',
                        'en_US' => 'en_US'
                        )
                    ),
                );
            $session->setData('config', serialize($config));

            $session->setData('step', STEP_DATABASE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_DATABASE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.system');
        $layout->getMessagesBlock()->prepareMessages('install');
        break;

    case STEP_DATABASE:
        $config = unserialize($session->getData('config'));
        if (isset($config['global']['database'])) {
            $session->setFormData(array(
                'host'     => $config['global']['database']['default']['params']['hostname'],
                'port'     => $config['global']['database']['default']['params']['port'],
                'user'     => $config['global']['database']['default']['params']['username'],
                'password' => $config['global']['database']['default']['params']['password'],
                'dbname'   => $config['global']['database']['default']['params']['database'],
                'prefix'   => $config['global']['database']['default']['table_prefix'],
                ));
        }

        if ($request->isPost()) {
            $form = new Wootook_Core_Form($session, array(
                'host'     => 'text',
                'port'     => 'text',
                'user'     => 'text',
                'password' => 'text',
                'dbname'   => 'text',
                'prefix'   => 'text',
                ));
            $form->setRequest($request);
            $form->populate();

            if (!$form->validate()) {
                $session->setData('step', STEP_DATABASE);
                $session->setFormData($request->getData());
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_DATABASE)));
                $response->sendHeaders();
                exit(0);
            }

            if ($request->getPost('test')) {
                $data = array(
                    'status'  => 'pending',
                    'message' => ''
                    );

                try {
                    $hostname = $request->getPost('host');
                    $username = $request->getPost('user');
                    $password = $request->getPost('password');
                    $database = $request->getPost('dbname');
                    $port = $request->getPost('port');

                    $connection = new Wootook_Database("mysql:dbname={$database};host={$hostname};port={$port}", $username, $password);
                    $data['status'] = 'success';
                } catch (PDOException $e) {
                    $data['status'] = 'error';
                    $data['message'] = $e->getMessage();
                }

                Wootook_Core_ErrorProfiler::unregister(true);
                $response->setHeader('Content-Type', 'application/json');
                $response->setBody(json_encode($data));

                $response->sendHeaders();
                echo $response->sendBody();
                exit(0);
            }

            $config = unserialize($session->getData('config'));
            $config['global']['database'] = array(
                'default' => array(
                    'engine' => 'mysql',
                    'options' => array(
                        ),
                    'params' => array(
                        'hostname' => $request->getPost('host'),
                        'username' => $request->getPost('user'),
                        'password' => $request->getPost('password'),
                        'database' => $request->getPost('dbname'),
                        'port'     => $request->getPost('port')
                        ),
                    'table_prefix' => $request->getPost('prefix'),
                    ),
                'core_setup' => array(
                    'use' => 'default'
                    ),
                'core_read' => array(
                    'use' => 'default'
                    ),
                'core_write' => array(
                    'use' => 'default'
                    ),
                );
            $session->setData('config', serialize($config));

            $session->setData('step', STEP_UNIVERSE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.database');
        $layout->getMessagesBlock()->prepareMessages('install');
        break;

    case STEP_UNIVERSE:
        if ($request->isPost()) {
            $form = new Wootook_Core_Form($session, array(
                'galaxies'  => 'text',
                'systems'   => 'text',
                'positions' => 'text',

                'allow_spy_drone_attacks' => 'text',
                'use_large_numbers'       => 'text'
                ));
            $form->setRequest($request);
            $form->populate();

            if (!$form->validate()) {
                $session->setData('step', STEP_UNIVERSE);
                $session->setFormData($request->getData());
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            }

            $config = unserialize($session->getData('config'));
            $config['default'] = array(
                'engine' => array(
                    'core' => array(
                        'use_large_numbers' => (bool) $request->getPost('use_large_numbers')
                        ),
                    'universe' => array(
                        'galaxies'  => $request->getPost('galaxies'),
                        'systems'   => $request->getPost('systems'),
                        'positions' => $request->getPost('positions')
                        ),
                    'combat' => array(
                        'allow_spy_drone_attacks' => (bool) $request->getPost('allow_spy_drone_attacks')
                        )
                    ),
                );
            Wootook::writeConfig($config);

            $gameplays = include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'gameplays.php';
            $gameplayKey = $config['global']['storyline']['universe'];
            if (!isset($gameplays[$gameplayKey])) {
                $gameplayKey = key($gameplays);
            }
            $moduleList = $gameplays[$gameplayKey]['modules'];

            $installedModules = array();
            $updater = new Wootook_Core_Setup_Updater();
            foreach ($moduleList as $module => $moduleData) {
                $version = $moduleData['version'];
                $codePool = $moduleData['code_pool'];
                $modulePath = str_replace('_', DIRECTORY_SEPARATOR, $module);

                $scriptPath = APPLICATION_PATH . 'code' . DIRECTORY_SEPARATOR . $codePool
                    . DIRECTORY_SEPARATOR . $modulePath . DIRECTORY_SEPARATOR . 'install'
                    . DIRECTORY_SEPARATOR . 'mysql5';

                try {
                    $queue = new Wootook_Core_Setup_Updater_ScriptQueue($scriptPath, null, $version);

                    foreach ($queue as $installScript) {
                        $updater->run($installScript['script']);
                        $installedModules[$module] = $installScript['version']['version'];
                    }
                } catch (Wootook_Core_Setup_Exception_VersionStageError $e) {
                    Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);

                    $session->addError($e->getMessage());
                    $session->setData('step', STEP_UNIVERSE);
                    $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
                    $response->sendHeaders();
                    continue;
                } catch (Wootook_Core_Setup_Exception_VersionValueError $e) {
                    Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);

                    $session->addError($e->getMessage());
                    $session->setData('step', STEP_UNIVERSE);
                    $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
                    $response->sendHeaders();
                    continue;
                } catch (Wootook_Core_Setup_Exception_RuntimeException $e) {
                    Wootook_Core_ErrorProfiler::getSingleton()->exceptionManager($e);

                    $session->addError($e->getMessage());
                    $session->setData('step', STEP_UNIVERSE);
                    $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
                    $response->sendHeaders();
                    continue;
                }
            }
            $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'gamedata'  . DIRECTORY_SEPARATOR
                . $gameplayKey . DIRECTORY_SEPARATOR . 'version.php';
            file_put_contents($path, '<' . '?p' . 'hp ' . var_export($installedModules, true) . ';');

            $session->addSuccess(Wootook::__('Game data successfully initialized.'));
            $session->setData('step', STEP_PROFILE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.universe');
        $layout->getMessagesBlock()->prepareMessages('install');
        break;

    case STEP_PROFILE:
        if ($request->isPost()) {
            $form = new Wootook_Core_Form($session, array(
                'username'         => 'text',
                'email'            => 'text',
                'email_confirm'    => 'text',
                'password'         => 'text',
                'password_confirm' => 'text',
                ));
            $form->setRequest($request);
            $form->populate();

            if (!$form->validate()) {
                $session->setData('step', STEP_PROFILE);
                $session->setFormData($request->getData());
                $session->addError(Wootook::__('Form data error.'));
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            }

            if ($request->getPost('password') != $request->getPost('password_confirm')) {
                $session->setData('step', STEP_PROFILE);
                $session->setFormData($request->getData());
                $session->addError(Wootook::__('Passwords does not match.'));
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            }

            if ($request->getPost('email') != $request->getPost('email_confirm')) {
                $session->setData('step', STEP_PROFILE);
                $session->setFormData($request->getData());
                $session->addError(Wootook::__('Both e-mails does not match.'));
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            }

            try {
                $user = Wootook_Empire_Model_User::register($request->getPost('username'), $request->getPost('email'), $request->getPost('password'));
            } catch (Wootook_Empire_Exception_RuntimeException $e) {
            }
            $layout->getMessagesBlock()->prepareMessages('user');
            if (!$user->getId()) {
                $session->setData('step', STEP_PROFILE);
                $session->setFormData($request->getData());
                $session->addError(Wootook::__('Could not create user.'));
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            } else {
                Wootook_Empire_Model_User::setLoggedIn($user);

                $user->setData('authlevel', LEVEL_ADMIN)->save();
            }

            $session->setData('step', STEP_CONFIG);
            //$response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_CONFIG)));
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'summary')));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.profile');
        $layout->getMessagesBlock()->prepareMessages('install');
        break;

    case STEP_CONFIG:
        //*
        $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'summary')));
        $response->sendHeaders();
        exit(0);
        /*/
        if ($request->isPost()) {
            $session->setData('step', $step);
            $response->setRedirect("?mode=install&step={$nextStep}");
            $response->sendHeaders();
            exit(0);
        }

        $layout->getMessagesBlock()->prepareMessages('install');
        $layout->load('install.step.config');
        //*/
        break;
    }
    break;
case 'summary':
    $layout->load('install.summary');
    $layout->getMessagesBlock()->prepareMessages('install');
    break;
}

echo $layout->render();
