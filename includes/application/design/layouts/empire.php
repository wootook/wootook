<?php return array(
    'overview' => array(
        'update' => '1column',
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
        )
    );
