<?php
spl_autoload_register(function ($class) {
    static $map;
    if (!$map) {
        $map = include dirname(__DIR__) . '/autoload_classmap.php';
    }

    if (!isset($map[$class])) {
        return false;
    }
    return require $map[$class];
});

