<?php

set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__DIR__),
    get_include_path()
    )));

function frameworkAutoload($class) {
    include_once str_replace('_', '/', $class) . '.php';
}

spl_autoload_register('frameworkAutoload');

include 'vfsStream/vfsStream.php';

vfsStream::create(array(
    'includes' => array(
        'application' => array(
            'code' => array(
                'community' => array(),
                'core' => array(),
                'libraries' => array(),
                'local' => array(),
                ),
            'design' => array(
                'layouts' => array(),
                'scripts' => array()
                )
            ),
        'data' => array(
            'combat.php' => '<?php echo ' . var_export(array(), true),
            'events.php' => '<?php echo ' . var_export(array(), true),
            'fields-alias.php' => '<?php echo ' . var_export(array(), true),
            'prices.php' => '<?php echo ' . var_export(array(), true),
            'production.php' => '<?php echo ' . var_export(array(), true),
            'requirements.php' => '<?php echo ' . var_export(array(), true),
            'resources.php' => '<?php echo ' . var_export(array(), true),
            'types.php' => '<?php echo ' . var_export(array(), true),
            )
        ),
    'config.php' => '<?php echo ' . var_export(array(
        'global' => array(
            'date' => array(
                'timezone' => 'Europe/Paris'
                ),
            'database' => array(
                'engine' => 'mysql',
                'options' => array(
                    'hostname' => 'localhost',
                    'username' => 'root',
                    'password' => '',
                    'database' => 'db_xnova'
                    ),
                'table_prefix' => 'game_',
                ),
            'layout' => array(
                'page'   => 'page.php',
                'empire' => 'empire.php'
                )
            )
        ), true) . ';'
    ), 'root', 644);

define('ROOT_PATH', vfsStream::url(''));
define('APPLICATION_PATH', vfsStream::url('includes/application'));

