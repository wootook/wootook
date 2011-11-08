<?php return array(
    'empire' => array(
        'update' => '1column',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'navigation' => array(
                        'type' => 'core/html.navigation',
                        'template' => 'page/html/navigation.phtml',
                        'actions' => array(
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'development',
                                    'title' => 'Development'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/overview',
                                    'label' => 'Overview',
                                    'title' => 'Overview',
                                    'uri'   => 'overview.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/buildings',
                                    'label' => 'Buildings',
                                    'title' => 'Buildings',
                                    'uri'   => 'buildings.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/research-lab',
                                    'label' => 'Research Lab',
                                    'title' => 'Research Lab',
                                    'uri'   => 'buildings.php',
                                    'params' => array(
                                        'mode' => 'research'
                                        )
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/shipyard',
                                    'label' => 'Shipyard',
                                    'title' => 'Shipyard',
                                    'uri'   => 'buildings.php',
                                    'params' => array(
                                        'mode' => 'fleet'
                                        )
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/defenses',
                                    'label' => 'Defenses',
                                    'title' => 'Defenses',
                                    'uri'   => 'buildings.php',
                                    'params' => array(
                                        'mode' => 'defense'
                                        )
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/officers',
                                    'label' => 'Officers',
                                    'title' => 'Officers',
                                    'uri'   => 'officier.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'navigation',
                                    'title' => 'Navigation'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/alliance',
                                    'label' => 'Alliance',
                                    'title' => 'Alliance',
                                    'uri'   => 'alliance.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/fleet',
                                    'label' => 'Fleet',
                                    'title' => 'Fleet',
                                    'uri'   => 'fleet.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/galaxy',
                                    'label' => 'Galaxy',
                                    'title' => 'Galaxy',
                                    'uri'   => 'galaxy.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/empire',
                                    'label' => 'Empire',
                                    'title' => 'Empire',
                                    'uri'   => 'imperium.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/resources',
                                    'label' => 'Resources Production',
                                    'title' => 'Resources Production',
                                    'uri'   => 'resources.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'development/retailer',
                                    'label' => 'Retailer',
                                    'title' => 'Retailer',
                                    'uri'   => 'marchand.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'navigation/tech-tree',
                                    'label' => 'Technology Tree',
                                    'title' => 'Technology Tree',
                                    'uri'   => 'techtree.php'
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
                                    'name'  => 'tools/messages',
                                    'label' => 'Messages',
                                    'title' => 'Messages',
                                    'uri'   => 'messages.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/records',
                                    'label' => 'Records',
                                    'title' => 'Records',
                                    'uri'   => 'records.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/statistics',
                                    'label' => 'Stats',
                                    'title' => 'Stats',
                                    'uri'   => 'stat.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/banned',
                                    'label' => 'Banned Players',
                                    'title' => 'Banned Players',
                                    'uri'   => 'banned.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/announcement',
                                    'label' => 'Announcements',
                                    'title' => 'Announcements',
                                    'uri'   => 'annonce.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/notes',
                                    'label' => 'Note Pad',
                                    'title' => 'Note Pad',
                                    'uri'   => 'notes.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/options',
                                    'label' => 'Account Options',
                                    'title' => 'Account Options',
                                    'uri'   => 'options.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'tools/logout',
                                    'label' => 'Log Out',
                                    'title' => 'Log Out',
                                    'uri'   => 'logout.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'community',
                                    'title' => 'Community'
                                    )
                                ),
                            array(
                                'method' => 'addExternalLink',
                                'params' => array(
                                    'name'  => 'community/board',
                                    'label' => 'Forum board',
                                    'title' => 'Forum board',
                                    'url'   => 'http://www.wootook.org/'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/search-player',
                                    'label' => 'Search Player',
                                    'title' => 'Search Player',
                                    'uri'   => 'search.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/chat',
                                    'label' => 'Chat',
                                    'title' => 'Chat',
                                    'uri'   => 'chat.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/multi',
                                    'label' => 'Declare Multi-account',
                                    'title' => 'Declare Multi-account',
                                    'uri'   => 'delclare_multi.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/rules',
                                    'label' => 'Rules',
                                    'title' => 'Rules',
                                    'uri'   => 'rules.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/contact',
                                    'label' => 'Contact Admin',
                                    'title' => 'Contact Admin',
                                    'uri'   => 'contact.php'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ),

    'overview' => array(
        'update' => 'empire',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'overview.forms' => array(
                        'type' => 'core/concat'
                        ),
                    'overview' => array(
                        'type' => 'empire/overview',
                        'template' => 'empire/overview.phtml'
                        ),
                    )
                ),
            'left' => array(
                )
            )
        ),

    'overview.rename-planet' => array(
        'update' => 'overview',
        'reference' => array(
            'overview.forms' => array(
                'children' => array(
                    'rename-planet' => array(
                        'type' => 'empire/overview.rename-planet',
                        'template' => 'empire/overview/form/rename-planet.phtml'
                        ),
                    )
                )
            )
        ),

    'overview.destroy-planet' => array(
        'update' => 'overview',
        'reference' => array(
            'overview.forms' => array(
                'children' => array(
                    'destroy-planet' => array(
                        'type' => 'empire/overview.destroy-planet',
                        'template' => 'empire/overview/form/destroy-planet.phtml'
                        ),
                    )
                )
            )
        ),

    'planet.buildings' => array(
        'update' => 'empire',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'topnav' => array(
                        'type'     => 'empire/topnav',
                        'template' => 'empire/topnav.phtml',
                        ),
                    'queue' => array(
                        'type' => 'empire/planet.buildings.queue',
                        'template' => 'empire/planet/buildings/queue.phtml',
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/buildings/queue/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'empire/planet.buildings.queue.item'
                                    )
                                )
                            )
                        ),
                    'item-list' => array(
                        'type'     => 'empire/planet.buildings',
                        'template' => 'empire/planet/buildings.phtml',
                        'children' => array(
                            'item-list.items' => array(
                                'type' => 'core/concat'
                                ),
                            ),
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/buildings/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'empire/planet.buildings.item'
                                    )
                                )
                            )
                        ),
                    )
                )
            )
        ),

    'planet.shipyard' => array(
        'update' => 'empire',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'topnav' => array(
                        'type'     => 'empire/topnav',
                        'template' => 'empire/topnav.phtml',
                        ),
                    'item-list' => array(
                        'type'     => 'legacies_empire/planet.shipyard',
                        'template' => 'empire/planet/shipyard.phtml',
                        'children' => array(
                            'item-list.items' => array(
                                'type' => 'core/concat'
                                ),
                            ),
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/shipyard/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'legacies_empire/planet.shipyard.item'
                                    )
                                ),
                            )
                        ),
                    'queue' => array(
                        'type' => 'legacies_empire/planet.shipyard.queue',
                        'template' => 'empire/planet/shipyard/queue.phtml',
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/shipyard/queue/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'legacies_empire/planet.shipyard.queue.item'
                                    )
                                )
                            )
                        ),
                    )
                )
            )
        ),

    'planet.defense' => array(
        'update' => 'planet.shipyard',
        'reference' => array(
            'item-list' => array(
                'actions' => array(
                    array(
                        'method' => 'setType',
                        'params' => array(
                            'type' => Legacies_Empire::TYPE_DEFENSE
                            )
                        )
                    )
                )
            )
        ),

    'planet.research-lab' => array(
        'update' => 'empire',
        'reference' => array(
            'content' => array(
                'children' => array(
                    'topnav' => array(
                        'type'     => 'empire/topnav',
                        'template' => 'empire/topnav.phtml',
                        ),
                    'item-list' => array(
                        'type'     => 'legacies_empire/planet.research-lab',
                        'template' => 'empire/planet/research-lab.phtml',
                        'children' => array(
                            'item-list.items' => array(
                                'type' => 'core/concat'
                                ),
                            ),
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/research-lab/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'legacies_empire/planet.research-lab.item'
                                    )
                                ),
                            )
                        ),
                    'queue' => array(
                        'type' => 'legacies_empire/planet.research-lab.queue',
                        'template' => 'empire/planet/research-lab/queue.phtml',
                        'actions' => array(
                            array(
                                'method' => 'setItemTemplate',
                                'params' => array(
                                    'template' => 'empire/planet/research-lab/queue/item.phtml'
                                    )
                                ),
                            array(
                                'method' => 'setItemBlockType',
                                'params' => array(
                                    'blockType' => 'legacies_empire/planet.research-lab.queue.item'
                                    )
                                )
                            )
                        )
                    )
                )
            )
        )
    );
