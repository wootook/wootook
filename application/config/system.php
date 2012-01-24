<?php return array(
    'global' => array(
        'resource' => array(
            'database' => array(
                'default' => array(
                    'engine' => 'mysql',
                    'options' => array(
                        ),
                    'params' => array(
                         'hostname' => 'localhost',
                         'username' => 'root',
                         'password' => '',
                         'database' => 'db_wootook'
                         ),
                    'table_prefix' => 'wtk_',
                     ),
                'core_setup' => array(
                    'use' => 'default'
                    ),
                'core_read' => array(
                    'use' => 'default'
                    ),
                'core_write' => array(
                    'use' => 'default'
                    ),
                )
            )
        ),
    'default' => array(
        'engine' => array(
            'core' => array(
                'use_large_numbers' => true,
                ),
            'universe' => array(
                'galaxies' => 3,
                'systems' => 100,
                'positions' => 15,
                ),
            'combat' => array(
                'allow_spy_drone_attacks' => true,
                )
            ),
        'system' => array(
            'date' => array(
                'timezone' => 'Europe/Paris'
                ),
            ),
        ),
    'frontend' => array(
        'layout' => array(
            'page'   => 'page.xml',
            'user'   => 'user.xml',
            'empire' => 'empire.php'
            ),
        ),
    'backend' => array(
        'layout' => array(
            'page'  => 'page.xml',
            'admin' => 'admin.php'
            ),
        )
    );