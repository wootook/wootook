<?php

if (!defined('DEBUG') && in_array(strtolower(getenv('DEBUG')), array('1', 'on', 'true'))) {
    define('DEBUG', true);
}

if (!defined('DEBUG')) {
    @ini_set('display_errors', false);
} else {
    @ini_set('display_errors', true);
    @error_reporting(E_ALL | E_STRICT);
}

defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', ROOT_PATH . 'includes' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);

defined('PHPEXT') || define('PHPEXT', require ROOT_PATH . 'extension.inc');

defined('VERSION') || define('VERSION', '2009.5');

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'core',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'community',
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR . 'local',
    get_include_path()
    )));

function __autoload($class) {
    include_once str_replace('_', '/', $class) . '.php';
}

if (0 === filesize(ROOT_PATH . 'config.php')) {
    header('Location: install/');
    die();
}

foreach (include ROOT_PATH . 'includes/data/events.php' as $event => $listenerList) {
    foreach ($listenerList as $listener) {
        Legacies::registerListener($event, $listener);
    }
}

include ROOT_PATH . 'includes/constants.php';