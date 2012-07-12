<?php return array(
    'global' => array(
        'modules' => array(
            'WootookCore' => array(
                'active' => true,
                'code-pool' => 'core'
                ),
            'WootookPlayer' => array(
                'active' => false,
                'code-pool' => 'core',
                'dependencies' => array(
                    'WootookCore' => '1.5.0'
                    )
                )
            )
        )
    );
