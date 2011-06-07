<?php

class Legacies_Database
    extends PDO
{
    protected static $_singleton = null;

    protected static $_prefix = null;

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            $config = include ROOT_PATH . 'config.php';
            $hostname = $config['global']['database']['options']['hostname'];
            $username = $config['global']['database']['options']['username'];
            $password = $config['global']['database']['options']['password'];
            $database = $config['global']['database']['options']['database'];

            self::$_singleton = new self("mysql:dbname={$database};host={$hostname}", $username, $password);
        }
        return self::$_singleton;
    }

    public function getTable($name)
    {
        if (self::$_prefix === null) {
            $config = include ROOT_PATH . 'config.php';
            self::$_prefix = $config['global']['database']['table_prefix'];
        }
        return self::$_prefix . $name;
    }
}