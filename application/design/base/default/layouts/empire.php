<?php return array(
    'empire' => array(
        'update' => '2columns-left',
        'reference' => array(
            'navigation' => array(
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
                            'uri'   => 'overview.php'
                            )
                        ),
                    array(
                        'method' => 'addLink',
                        'params' => array(
                            'name'  => 'development/research-lab',
                            'label' => 'Research Lab',
                            'title' => 'Research Lab',
                            'uri'   => 'overview.php',
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
                            'uri'   => 'overview.php',
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
                            'uri'   => 'overview.php',
                            'params' => array(
                                'mode' => 'defense'
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
