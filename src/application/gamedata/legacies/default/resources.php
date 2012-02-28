<?php return array(
    Legacies_Empire::RESOURCE_METAL     => array(
//        'template' => 'empire/resources/default.phtml',
//        'renderer' => 'legacies_empire/resource_default',

        'field'            => 'metal',
        'production_field' => 'metal_perhour',
        'storage_field'    => 'metal_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_METAL_MINE => 'metal_mine_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_METAL_STORAGE
        ),
    Legacies_Empire::RESOURCE_CRISTAL   => array(
//        'template' => 'empire/resources/default.phtml',
//        'renderer' => 'legacies_empire/resource_default',

        'field'            => 'cristal',
        'production_field' => 'cristal_perhour',
        'storage_field'    => 'cristal_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_CRISTAL_MINE => 'cristal_mine_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE
        ),
    Legacies_Empire::RESOURCE_DEUTERIUM => array(
//        'template' => 'empire/resources/default.phtml',
//        'renderer' => 'legacies_empire/resource_default',

        'field'            => 'deuterium',
        'production_field' => 'deuterium_perhour',
        'storage_field'    => 'deuterium_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => 'deuterium_sintetizer_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK
        ),
    Legacies_Empire::RESOURCE_ENERGY => array(
//        'template' => 'empire/resources/energy.phtml',
//        'renderer' => 'legacies_empire/resource_energy',

        'field'            => 'energy_used',
        'production_field' => 'energy_max',
        'storage_field'    => null,
        'production'       => array(
            Legacies_Empire::ID_BUILDING_SOLAR_PLANT    => 'solar_plant_porcent',
            Legacies_Empire::ID_BUILDING_FUSION_REACTOR => 'fusion_plant_porcent',
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE    => 'solar_satelit_porcent'
            ),
        'storage'          => null
        )
    );
