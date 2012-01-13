<?php return array(
    'empire' => array(
        'update' => '1column',
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
                                    'path'  => 'planet',
                                    'title' => 'Planet'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'planet/overview',
                                    'label' => 'Overview',
                                    'title' => 'Overview',
                                    'uri'   => 'overview.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'planet/buildings',
                                    'label' => 'Buildings',
                                    'title' => 'Buildings',
                                    'uri'   => 'buildings.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'planet/research-lab',
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
                                    'name'  => 'planet/shipyard',
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
                                    'name'  => 'planet/defenses',
                                    'label' => 'Defenses',
                                    'title' => 'Defenses',
                                    'uri'   => 'buildings.php',
                                    'params' => array(
                                        'mode' => 'defense'
                                        )
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'universe',
                                    'title' => 'Universe'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/galaxy',
                                    'label' => 'Check out the Galaxy',
                                    'title' => 'Check out the Galaxy',
                                    'uri'   => 'galaxy.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/fleet',
                                    'label' => 'Send a Fleet',
                                    'title' => 'Send a Fleet',
                                    'uri'   => 'fleet.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/retailer',
                                    'label' => 'Retailer',
                                    'title' => 'Retailer',
                                    'uri'   => 'marchand.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/records',
                                    'label' => 'All Records',
                                    'title' => 'All Records',
                                    'uri'   => 'records.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/statistics',
                                    'label' => 'My Stats',
                                    'title' => 'My Stats',
                                    'uri'   => 'stat.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'universe/search-player',
                                    'label' => 'Search a Player',
                                    'title' => 'Search a Player',
                                    'uri'   => 'search.php'
                                    )
                                ),
                            array(
                                'method' => 'setNodeTitle',
                                'params' => array(
                                    'path'  => 'account',
                                    'title' => 'My Account'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/empire',
                                    'label' => 'Empire',
                                    'title' => 'Empire',
                                    'uri'   => 'imperium.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/officers',
                                    'label' => 'Officers',
                                    'title' => 'Officers',
                                    'uri'   => 'officier.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/alliance',
                                    'label' => 'My Alliance',
                                    'title' => 'My Alliance',
                                    'uri'   => 'alliance.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/messages',
                                    'label' => 'My Messages',
                                    'title' => 'My Messages',
                                    'uri'   => 'messages.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/resources',
                                    'label' => 'My Resources Production',
                                    'title' => 'My Resources Production',
                                    'uri'   => 'resources.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'account/tech-tree',
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
                                    'name'  => 'community/chat',
                                    'label' => 'Chat',
                                    'title' => 'Chat',
                                    'uri'   => 'chat.php'
                                    )
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/announcement',
                                    'label' => 'Announcements',
                                    'title' => 'Announcements',
                                    'uri'   => 'annonce.php'
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
                                ),
                            array(
                                'method' => 'addLink',
                                'params' => array(
                                    'name'  => 'community/banned',
                                    'label' => 'Banned Players',
                                    'title' => 'Banned Players',
                                    'uri'   => 'banned.php'
                                    )
                                )
                            )
                        ),
                    'topnav' => array(
                        'type'     => 'empire/topnav',
                        'template' => 'empire/topnav.phtml',
                        ),
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
