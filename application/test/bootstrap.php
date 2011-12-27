<?php

//define('BCNUMBERS', true);

if (!defined('DEBUG') && in_array(strtolower(getenv('DEBUG')), array('1', 'on', 'true'))) {
    define('DEBUG', true);
}

if (!defined('DEBUG')) {
    @ini_set('display_errors', false);
} else {
    @ini_set('display_errors', true);
    @error_reporting(E_ALL | E_STRICT);
}

defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', ROOT_PATH . 'application' . DIRECTORY_SEPARATOR);

defined('PHPEXT') || define('PHPEXT', 'php');

defined('VERSION') || define('VERSION', '1.5');

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'core',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'community',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'local',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'libraries',
    get_include_path()
    )));

function __autoload($class) {
    include_once str_replace('_', '/', $class) . '.php';
}

if (0 === filesize(ROOT_PATH . 'config.php')) {
    header('Location: install/');
    die();
}

Wootook_Core_Model_Config_Events::registerEvents();

include ROOT_PATH . 'includes/constants.php';