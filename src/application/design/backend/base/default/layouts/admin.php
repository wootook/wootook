<?php return array(
    'admin' => array(
        'update' => array('1column'),
        'reference' => array(
            'header' => array(
                'children' => array(
                    'navigation' => array(
                        'type' => 'core/html.navigation',
                        'template' => 'page/html/navigation.phtml',
                        'actions' => array(
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'system',
                                    'title' => 'System'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'system/overview',
                                    'label' => 'Overview',
                                    'title' => 'Overview',
                                    'uri'   => 'admin/overview.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'system/settings',
                                    'label' => 'Settings',
                                    'title' => 'Settings',
                                    'uri'   => 'admin/settings.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'system/reset-universe',
                                    'label' => 'Reset Universe',
                                    'title' => 'Reset Universe',
                                    'uri'   => 'admin/XNovaResetUnivers.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'players',
                                    'title' => 'Player Accounts'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/list',
                                    'label' => 'List',
                                    'title' => 'List',
                                    'uri'   => 'admin/userlist.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/password-recovery',
                                    'label' => 'Change a password',
                                    'title' => 'Change a password',
                                    'uri'   => 'admin/md5changepass.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/search',
                                    'label' => 'Search',
                                    'title' => 'Search',
                                    'uri'   => 'admin/paneladmina.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/multi-account-alerts',
                                    'label' => 'Multi account alerts',
                                    'title' => 'Multi account alerts',
                                    'uri'   => 'admin/multi.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/declared-multi-account',
                                    'label' => 'Declared multi accounts',
                                    'title' => 'Declared multi accounts',
                                    'uri'   => 'admin/declare_list.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/add-resources',
                                    'label' => 'Add Resources',
                                    'title' => 'Add Resources',
                                    'uri'   => 'admin/add_money.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/messages',
                                    'label' => 'Private messages management',
                                    'title' => 'Private messages management',
                                    'uri'   => 'admin/messagelist.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/chat',
                                    'label' => 'Manage Chat',
                                    'title' => 'Manage Chat',
                                    'uri'   => 'chat.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/ban',
                                    'label' => 'Ban Player',
                                    'title' => 'Ban Player',
                                    'uri'   => 'admin/banned.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'players/unban',
                                    'label' => 'Unban Player',
                                    'title' => 'Unban Player',
                                    'uri'   => 'admin/unbanned.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'empire',
                                    'title' => 'Empires'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'empire/planet-list',
                                    'label' => 'Planet List',
                                    'title' => 'Planet List',
                                    'uri'   => 'admin/planetlist.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'empire/moon-list',
                                    'label' => 'Moon List',
                                    'title' => 'Moon List',
                                    'uri'   => 'admin/moonlist.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'empire/add-moon',
                                    'label' => 'Add a moon',
                                    'title' => 'Add a moon',
                                    'uri'   => 'admin/add_moon.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'empire/planet-activity',
                                    'label' => 'Planet Activity',
                                    'title' => 'Planet Activity',
                                    'uri'   => 'admin/activeplanet.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'empire/fleet-list',
                                    'label' => 'Fleet list',
                                    'title' => 'Fleet list',
                                    'uri'   => 'admin/ShowFlyingFleets.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'tools',
                                    'title' => 'Tools'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/phpinfo',
                                    'label' => 'PHP Info',
                                    'title' => 'PHP Info',
                                    'uri'   => 'variables.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/stats',
                                    'label' => 'Stats updater',
                                    'title' => 'Stats updater',
                                    'uri'   => 'statbuilder.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/errors',
                                    'label' => 'Error reporting',
                                    'title' => 'Error reporting',
                                    'uri'   => 'errors.php'
                                    )
                                ),
                            array(
                                'method' => 'addExternalLink',
                                'params' => array(
                                    'name'  => 'tools/help',
                                    'label' => 'Need help?',
                                    'title' => 'Need help?',
                                    'url'   => 'http://wootook.org/board/'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),
    'admin.grid' => array(
        'update' => array('admin'),
        'reference' => array(
            'content' => array(
                'children' => array(
                    'grid.container' => array(
                        'type'     => 'admin/grid.container',
                        'template' => 'grid/container.phtml',
                        'children' => array(
                            'mass-actions' => array(
                                'type'     => 'admin/grid.mass-actions',
                                'template' => 'grid/view.phtml',
                                ),
                            'grid' => array(
                                'type'     => 'admin/grid.view',
                                'template' => 'grid/view.phtml',
                                )
                            )
                        )
                    )
                )
            )
        ),
    'admin.user.grid' => array(
        'update' => array('admin.grid'),
        'reference' => array(
            'grid' => array(
                'actions' => array(
                    array(
                        'method' => 'addColumn',
                        'params' => array(
                            'name' => 'index',
                            'type' => 'indexer',
                            'config' => array()
                            )
                        )
                    )
                )
            )
        )
    );
