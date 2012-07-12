<?php return array(
    'global' => array(
        'modules' => array(
            'WootookCore' => array(
                'version' => '1.5.0'
            )
        ),
        'models' => array(
            'core' => array(
                'namespace' => 'Wootook\\Core\\Model',
                'connection' => array(
                    'read'  => 'core_read',
                    'write' => 'core_write',
                    'setup' => 'core_setup',
                    )
                )
            ),
        'blocks' => array(
            'core' => array(
                'namespace' => 'Wootook\\Core\\Block'
                )
            )
        ),
    'frontend' => array(
        'routes' => array(
            'core' => array(
                'controllers' => array(
                    'core' => 'Wootook\\Core\\Controller'
                    )
                )
            )
        )
    );
