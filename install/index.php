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

require_once dirname(dirname(__FILE__)) .'/application/bootstrap.php';

//include(ROOT_PATH . 'includes/databaseinfos.php');
//include(ROOT_PATH . 'includes/migrateinfo.php');

$mode     = isset($_GET['mode']) ? strval($_GET['mode']) : 'intro';
$step     = isset($_GET['step']) ? intval($_GET['step']) : 1;
$prevStep = $step - 1;
$nextStep = $step + 1;

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

            $layout->getMessagesblock()->prepareMessages('install');

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

            Wootook::writeConfig($config);

            $session->setData('step', STEP_DATABASE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_DATABASE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.system');
        break;

    case STEP_DATABASE:
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

            $layout->getMessagesblock()->prepareMessages('install');

            $config = Wootook::getConfig();
            $config['database'] = array(
                'core_setup' => array(
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
                'core_read' => array(
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
                'core_write' => array(
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
                );

            Wootook::writeConfig($config);

            $session->setData('step', STEP_UNIVERSE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.database');
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

            $config = Wootook::getConfig();
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

            $scriptPath = APPLICATION_PATH . 'code' . DIRECTORY_SEPARATOR . 'core'
                . 'Wootook' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR
                . 'install' . DIRECTORY_SEPARATOR . 'mysql5';
            $queue = new Wootook_Core_Setup_Model_Updater_ScriptQueue($scriptPath);

            try {
                $updater = new Wootook_Core_Setup_Model_Updater();
                foreach ($queue as $installScript) {
                    $updater->run($installScript);
                }
            } catch (Exception $e) {
                $session->addSuccess(Wootook::__('An error occured durong game data initialization.'));
                $session->setData('step', STEP_SYSTEM);
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_SYSTEM)));
                $response->sendHeaders();
                exit(0);
            }

            $session->addSuccess(Wootook::__('Game data successfully initialized.'));
        }

        $layout->load('install.step.universe');
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
                $session->addError(Wootook::__('e-mails does not match.'));
                $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_PROFILE)));
                $response->sendHeaders();
                exit(0);
            }

            Wootook_Empire_Model_User::register($request->getPost('username'), $request->getPost('email'), $request->getPost('password'));

            $session->setData('step', STEP_UNIVERSE);
            $response->setRedirect(Wootook::getUrl('install/index.php', array('mode' => 'install', 'step' => STEP_UNIVERSE)));
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.step.profile');
        break;

    case STEP_CONFIG:
        if (empty($_POST)) {
            $session->setData('step', $step);
            $response->setRedirect("?mode=install&step={$nextStep}");
            $response->sendHeaders();
            exit(0);
        }

        $layout->load('install.config');
        break;
    }
    break;

    case 'ins':
        if ($page == 1) {
            if (isset($_GET['error']) && intval($_GET['error']) == 1) {
	            adminMessage ($lang['ins_error1'], $lang['ins_error']);
            }
            elseif (isset($_GET['error']) && intval($_GET['error']) == 2) {
	            adminMessage ($lang['ins_error2'], $lang['ins_error']);
            }

            $subTpl = gettemplate('install/ins_form');
            $bloc   = $lang;
            $bloc['dpath'] = $dpath;
            $frame  = parsetemplate($subTpl, $bloc);
        } else if ($page == 2) {
            $host   = $_POST['host'];
            $user   = $_POST['user'];
            $pass   = $_POST['passwort'];
            $prefix = $_POST['prefix'];
            $db     = $_POST['db'];

            $connection = @mysql_connect($host, $user, $pass);
            if (!$connection) {
                header("Location: ?mode=ins&page=1&error=1");
                exit();
            }

            $dbselect = @mysql_select_db($db);
            if (!$dbselect) {
                header("Location: ?mode=ins&page=1&error=1");
                exit();
            }

            $dz = fopen("../config.php", "w");
            if (!$dz) {
	            header("Location: ?mode=ins&page=1&error=2");
	            exit();
            }
            $fileData =<<<EOF
<?php return array(
    'global' => array(
        'database' => array(
            'engine' => 'mysql',
            'options' => array(
                'hostname' => '{$host}',
                'username' => '{$user}',
                'password' => '{$pass}',
                'database' => '{$db}'
                ),
            'table_prefix' => '{$prefix}',
            )
        )
    );
EOF;
            fwrite($dz, $fileData);
            fclose($dz);

            doquery ( $QryTableAks        , 'aks'        );
            doquery ( $QryTableAnnonce    , 'annonce'    );
            doquery ( $QryTableAlliance   , 'alliance'   );
            doquery ( $QryTableBanned     , 'banned'     );
            doquery ( $QryTableBuddy      , 'buddy'      );
            doquery ( $QryTableChat       , 'chat'       );
            doquery ( $QryTableConfig     , 'config'     );
            doquery ( $QryInsertConfig    , 'config'     );
            doquery ( $QryTabledeclared        , 'declared'        );
            doquery ( $QryTableErrors     , 'errors'     );
            doquery ( $QryTableFleets     , 'fleets'     );
            doquery ( $QryTableGalaxy     , 'galaxy'     );
            doquery ( $QryTableIraks      , 'iraks'      );
            doquery ( $QryTableLunas      , 'lunas'      );
            doquery ( $QryTableMessages   , 'messages'   );
            doquery ( $QryTableNotes      , 'notes'      );
            doquery ( $QryTablePlanets    , 'planets'    );
            doquery ( $QryTableRw         , 'rw'         );
            doquery ( $QryTableStatPoints , 'statpoints' );
            doquery ( $QryTableUsers      , 'users'      );
            doquery ( $QryTableMulti      , 'multi'      );

            $subTpl = gettemplate ('install/ins_form_done');
            $bloc   = $lang;
            $bloc['dpath']        = $dpath;
            $frame  = parsetemplate ( $subTpl, $bloc );
        } elseif ($page == 3) {
            if (isset($_GET['error']) && intval($_GET['error']) == 3) {
            adminMessage($lang['ins_error3'], $lang['ins_error']);
            }

            $subTpl = gettemplate ('install/ins_acc');
            $bloc   = $lang;
            $bloc['dpath']        = $dpath;
            $frame  = parsetemplate ( $subTpl, $bloc );
        } elseif ($page == 4) {
            $adm_user   = $_POST['adm_user'];
            $adm_pass   = $_POST['adm_pass'];
            $adm_email  = $_POST['adm_email'];
            $adm_planet = $_POST['adm_planet'];
            $adm_sex    = $_POST['adm_sex'];
            $md5pass    = md5($adm_pass);

            if (!isset($_POST['adm_user'])) {
                header("Location: ?mode=ins&page=3&error=3");
                exit();
            }
            if (!isset($_POST['adm_pass'])) {
                header("Location: ?mode=ins&page=3&error=3");
                exit();
            }
            if (!isset($_POST['adm_email'])) {
                header("Location: ?mode=ins&page=3&error=3");
                exit();
            }
            if (!isset($_POST['adm_planet'])) {
                header("Location: ?mode=ins&page=3&error=3");
                exit();
            }

            $config = include(ROOT_PATH . 'config.php');
            $db_host   = $config['global']['database']['options']['hostname'];
            $db_user   = $config['global']['database']['options']['username'];
            $db_pass   = $config['global']['database']['options']['password'];
            $db_db     = $config['global']['database']['options']['database'];
            $db_prefix = $config['global']['database']['table_prefix'];

            $connection = @mysql_connect($db_host, $db_user, $db_pass);
                if (!$connection) {
                header("Location: ?mode=ins&page=1&error=1");
                exit();
                }

            $dbselect = @mysql_select_db($db_db);
                if (!$dbselect) {
                header("Location: ?mode=ins&page=1&error=1");
                exit();
                }

            $QryInsertAdm  = "INSERT INTO {{table}} SET ";
            $QryInsertAdm .= "`id`                = '1', ";
            $QryInsertAdm .= "`username`          = '". $adm_user ."', ";
            $QryInsertAdm .= "`email`             = '". $adm_email ."', ";
            $QryInsertAdm .= "`email_2`           = '". $adm_email ."', ";
            $QryInsertAdm .= "`authlevel`         = '3', ";
            $QryInsertAdm .= "`sex`               = '". $adm_sex ."', ";
            $QryInsertAdm .= "`id_planet`         = '1', ";
            $QryInsertAdm .= "`galaxy`            = '1', ";
            $QryInsertAdm .= "`system`            = '1', ";
            $QryInsertAdm .= "`planet`            = '1', ";
            $QryInsertAdm .= "`current_planet`    = '1', ";
            $QryInsertAdm .= "`register_time`     = '". time() ."', ";
            $QryInsertAdm .= "`password`          = '". $md5pass ."';";
            doquery($QryInsertAdm, 'users');

            $QryAddAdmPlt  = "INSERT INTO {{table}} SET ";
            $QryAddAdmPlt .= "`name`              = '". $adm_planet ."', ";
            $QryAddAdmPlt .= "`id_owner`          = '1', ";
            $QryAddAdmPlt .= "`galaxy`            = '1', ";
            $QryAddAdmPlt .= "`system`            = '1', ";
            $QryAddAdmPlt .= "`planet`            = '1', ";
            $QryAddAdmPlt .= "`last_update`       = '". time() ."', ";
            $QryAddAdmPlt .= "`planet_type`       = '1', ";
            $QryAddAdmPlt .= "`image`             = 'normaltempplanet02', ";
            $QryAddAdmPlt .= "`diameter`          = '12750', ";
            $QryAddAdmPlt .= "`field_max`         = '163', ";
            $QryAddAdmPlt .= "`temp_min`          = '47', ";
            $QryAddAdmPlt .= "`temp_max`          = '87', ";
            $QryAddAdmPlt .= "`metal`             = '500', ";
            $QryAddAdmPlt .= "`metal_perhour`     = '0', ";
            $QryAddAdmPlt .= "`metal_max`         = '1000000', ";
            $QryAddAdmPlt .= "`crystal`           = '500', ";
            $QryAddAdmPlt .= "`crystal_perhour`   = '0', ";
            $QryAddAdmPlt .= "`crystal_max`       = '1000000', ";
            $QryAddAdmPlt .= "`deuterium`         = '500', ";
            $QryAddAdmPlt .= "`deuterium_perhour` = '0', ";
            $QryAddAdmPlt .= "`deuterium_max`     = '1000000';";
            doquery($QryAddAdmPlt, 'planets');

            $QryAddAdmGlx  = "INSERT INTO {{table}} SET ";
            $QryAddAdmGlx .= "`galaxy`            = '1', ";
            $QryAddAdmGlx .= "`system`            = '1', ";
            $QryAddAdmGlx .= "`planet`            = '1', ";
            $QryAddAdmGlx .= "`id_planet`         = '1'; ";
            doquery($QryAddAdmGlx, 'galaxy');

            $subTpl = gettemplate ('install/ins_acc_done');
            $frame  = parsetemplate ( $subTpl, $lang );
        }
        break;
}

echo $layout->render();
