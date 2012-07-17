<?php

namespace Wootook\Core\DependencyInjection\Config;

use Wootook\Core\Config\Node,
    Wootook\Core\DependencyInjection;

class Config
{
    public function __invoke(DependencyInjection\Factory $factory, Node $config)
    {
        foreach ($config as $className => $classConfig) {
            $classDefinition = $factory->initClassDefinition($className);
            $factory->registerClassDefinition($className, $classDefinition);

            if ($classConfig->methods instanceof Node) {
                foreach ($classConfig->methods as $methodName => $methodConfig) {
                    //$classDefinition->
                }
            }
        }
    }
}
