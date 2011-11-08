<?php return array(
    'install' => array(
        'update' => '2columns-left',
        'reference' => array(
            'messages' => array(
                'actions' => array(
                    array(
                        'method' => 'prepareMessages',
                        'params' => array(
                            'namespace' => 'install'
                            )
                        )
                    )
                ),
            'content' => array(
                'children' => array(
                    'navigation' => array(
                        'type' => 'core/html.navigation',
                        'template' => 'page/html/navigation.phtml',
                        'actions' => array()
                        )
                    )
                ),
            'left' => array(
                'children' => array(
                    'status' => array(
                        'type' => 'core/template',
                        'template' => 'status.phtml'
                        )
                    )
                )
            )
        ),

    'install.intro' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'intro.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 'intro'
                            )
                        )
                    )
                )
            )
        ),

    'install.step.system' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'step/system.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 'system'
                            )
                        )
                    )
                )
            )
        )
    );
