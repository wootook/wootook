<?php return array(
    'install' => array(
        'update' => '2columns-left',
        'reference' => array(
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
        ),

    'install.step.database' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'step/database.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 'database'
                            )
                        )
                    )
                )
            )
        ),

    'install.step.universe' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'step/universe.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 'universe'
                            )
                        )
                    )
                )
            )
        ),

    'install.step.profile' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'step/profile.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 'profile'
                            )
                        )
                    )
                )
            )
        ),

    'install.summary' => array(
        'update' => 'install',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'summary.phtml'
                        ),
                    )
                )
            )
        ),
    );
