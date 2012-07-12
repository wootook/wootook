<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://wootook.org>
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

define('DEBUG', true);
define('DEPRECATION', true);

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.',PHP_VERSION);
    define('PHP_VERSION_ID', (((int)$version[0]) * 10000 + ((int)$version[1]) * 100 + ((int)$version[2])));
    unset($version);
}

if (!defined('DEBUG') && ($env = getenv('DEBUG')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('DEBUG', true);
} else if (!defined('DEBUG') && isset($_SERVER['DEBUG']) && in_array(strtolower($_SERVER['DEBUG']), array('1', 'on', 'true'))) {
    define('DEBUG', true);
}

if (!defined('DEPRECATION') && ($env = getenv('DEPRECATION')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('DEPRECATION', true);
} else if (!defined('DEPRECATION') && isset($_SERVER['DEPRECATION']) && in_array(strtolower($_SERVER['DEPRECATION']), array('1', 'on', 'true'))) {
    define('DEPRECATION', true);
}

if (!defined('BCNUMBERS') && ($env = getenv('BCNUMBERS')) !== false && in_array(strtolower($env), array('1', 'on', 'true'))) {
    define('BCNUMBERS', true);
} else if (!defined('BCNUMBERS') && isset($_SERVER['BCNUMBERS']) && in_array(strtolower($_SERVER['BCNUMBERS']), array('1', 'on', 'true'))) {
    define('BCNUMBERS', true);
}

if (!defined('DEBUG')) {
    @ini_set('display_errors', false);
} else {
    @ini_set('display_errors', true);
    @error_reporting(E_ALL | E_STRICT);
}

defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

defined('PHPEXT') || define('PHPEXT', 'php');

defined('VERSION') || define('VERSION', '1.5.0-beta2');

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules',
    get_include_path()
    )));

include APPLICATION_PATH . 'code' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Wootook.php';
include 'WootookCore' . DIRECTORY_SEPARATOR . 'autoload_register.php';

Wootook\Core\Profiler\ErrorProfiler::register();
Wootook\Core\Helper\Config\Events::registerEvents();
