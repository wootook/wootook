<?php return array(
    'install' => array(
        'update' => array('2columns-left'),
        'reference' => array(
            'content' => array(
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
        'update' => array('install'),
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
                            'value' => 0
                            )
                        )
                    )
                )
            )
        ),

    'install.step.system' => array(
        'update' => array('install'),
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
                            'value' => 1
                            )
                        )
                    )
                )
            )
        ),

    'install.step.database' => array(
        'update' => array('install'),
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
                            'value' => 2
                            )
                        )
                    )
                )
            )
        ),

    'install.step.universe' => array(
        'update' => array('install'),
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
                            'value' => 3
                            )
                        )
                    )
                )
            )
        ),

    'install.step.profile' => array(
        'update' => array('install'),
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
                            'value' => 4
                            )
                        )
                    )
                )
            )
        ),

    'install.summary' => array(
        'update' => array('install'),
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview' => array(
                        'type' => 'core/template',
                        'template' => 'summary.phtml'
                        ),
                    )
                ),
            'status' => array(
                'actions' => array(
                    array(
                        'method' => 'setData',
                        'params' => array(
                            'key'   => 'step',
                            'value' => 5
                            )
                        )
                    )
                )
            )
        ),
    );
