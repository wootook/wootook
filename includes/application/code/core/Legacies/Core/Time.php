<?php


class Legacies_Core_Time
{
    public static function init()
    {
        $config = include ROOT_PATH . 'config.php';
        $timezone = $config['global']['date']['timezone'];

        date_default_timezone_set($timezone);
    }
}