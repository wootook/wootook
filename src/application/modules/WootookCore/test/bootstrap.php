<?php
spl_autoload_register(function ($class) {
    static $map;
    if (!$map) {
        $map = include dirname(dirname(dirname(__DIR__))) . '/autoload_classmap.php';
    }

    if (!isset($map[$class])) {
        return false;
    }
    return require $map[$class];
});

require dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/code/core/Wootook.php';
