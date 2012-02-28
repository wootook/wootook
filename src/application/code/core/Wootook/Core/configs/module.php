<?php return array(
    'modules' => array(
        'Wootook_Core' => array(
            'version' => '1.5.0'
            )
        ),
    'global' => array(
        'models' => array(
            'core' => array(
                'namespace' => 'Warner_Core_Model_',
                'connection' => array(
                    'read'  => 'core_read',
                    'write' => 'core_write',
                    'setup' => 'core_setup',
                    )
                )
            ),
        'blocks' => array(
            'core' => array(
                'namespace' => 'Warner_Core_Block_'
                )
            )
        ),
    'frontend' => array(
        'routes' => array(
            'core' => array(
                'modules' => array(
                    'core' => 'Wootook_Core'
                    )
                )
            )
        )
    );