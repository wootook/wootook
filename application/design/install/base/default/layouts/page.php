<?php return array(
    '1column' => array(
        'update' => 'default'
        ),

    '2columns-left' => array(
        'update' => 'default',
        'template' => 'page/2columns-left.phtml',
        'reference' => array(
            'root' => array(
                'children' => array(
                    'left' => array(
                        'type' => 'core/concat'
                        )
                    )
                )
            )
        ),

    '2columns-right' => array(
        'update' => 'default',
        'template' => 'page/2columns-right.phtml',
        'reference' => array(
            'root' => array(
                'children' => array(
                    'right' => array(
                        'type' => 'core/concat'
                        ),
                    )
                )
            )
        ),

    '3columns' => array(
        'update' => 'default',
        'template' => 'page/3columns.phtml',
        'reference' => array(
            'root' => array(
                'children' => array(
                    'left' => array(
                        'type' => 'core/concat'
                        ),
                    'right' => array(
                        'type' => 'core/concat'
                        ),
                    )
                )
            )
        ),

    'default' => array(
        'type' => 'core/html.page',
        'template' => 'page/1column.phtml',
        'children' => array(
            'head' => array(
                'type' => 'core/html.head',
                'template' => 'page/html/head.phtml',
                'actions' => array(
                    array(
                        'method' => 'addCss',
                        'params' => array(
                            'stylesheet' => 'css/base.css'
                            )
                        ),
                    array(
                        'method' => 'addJs',
                        'params' => array(
                            'script' => 'scripts/jquery/jquery-1.6.4.js'
                            )
                        )
                    )
                ),
            'header' => array(
                'type' => 'core/template',
                'template' => 'page/html/header.phtml'
                ),
            'footer' => array(
                'type' => 'core/template',
                'template' => 'page/html/footer.phtml'
                ),
            'content' => array(
                'type' => 'core/concat'
                )
            )
        ),

    'ajax' => array(
        'type' => 'core/html.page',
        'template' => 'page/empty.phtml',
        'children' => array(
            'content' => array(
                'type' => 'core/concat'
                )
            )
        ),

    'empty' => array(
        'type' => 'core/html.page',
        'template' => 'page/empty.phtml',
        'children' => array(
            'head' => array(
                'type' => 'core/html.head',
                'template' => 'page/html/head.phtml'
                ),
            'content' => array(
                'type' => 'core/concat'
                )
            )
        ),

    'message' => array(
        'update' => 'default',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'message' => array(
                        'type'     => 'core/template',
                        'template' => 'page/html/message.phtml'
                        ),
                    )
                )
            )
        ),

    'login' => array(
        'update' => '1column',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'login' => array(
                        'type'     => 'core/template',
                        'template' => 'user/login.phtml'
                        ),
                    )
                ),
            'head' => array(
                'actions' => array(
                    array(
                        'method' => 'addCss',
                        'params' => array(
                            'stylesheet' => 'css/deprecated/styles.css'
                            )
                        ),
                    array(
                        'method' => 'addCss',
                        'params' => array(
                            'stylesheet' => 'css/deprecated/about.css'
                            )
                        )
                    )
                )
            )
        ),

    'registration' => array(
        'update' => '1column',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'registration' => array(
                        'type'     => 'core/template',
                        'template' => 'user/registration.phtml'
                        ),
                    )
                ),
            'head' => array(
                'actions' => array(
                    array(
                        'method' => 'addCss',
                        'params' => array(
                            'stylesheet' => 'css/deprecated/styles.css'
                            )
                        ),
                    array(
                        'method' => 'addCss',
                        'params' => array(
                            'stylesheet' => 'css/deprecated/about.css'
                            )
                        )
                    )
                )
            )
        ),

    'install' => array(
        'update' => '2columns-left',
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
                            'value' => 0
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
                            'value' => 1
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
                            'value' => 2
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
                            'value' => 3
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
                            'value' => 4
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
