<?php return array(
    'global' => array(
        'resource' => array(
            'database' => array(
                'default' => array(
                    'engine' => 'pdo_mysql',
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
        'game' => array(
            'speed' => array(
                'general' => 1000,
                'ships' => 1000
                ),
            'home' => array(
                'title' => 'Welcome on Wootook!',
                'formated-welcome-text' => '<p><strong>Wootook</strong> make you an emperor.</p><p>Conquer outer space and master other players on <strong>Wootook</strong>.</p>'
                )
            )
        ),
    'frontend' => array(
        'layout' => array(
            'page'   => 'page.xml',
            'player' => 'player.xml',
            'empire' => 'empire.xml'
            ),
        ),
    'backend' => array(
        'layout' => array(
            'page'  => 'page.xml',
            'admin' => 'admin.php'
            ),
        )
    );
