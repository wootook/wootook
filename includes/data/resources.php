<?php return array(
    Legacies_Empire::RESOURCE_METAL     => array(
        'template' => 'empire/resources/default.phtml',
        'renderer' => 'Legacies_Empire_Block_Resource_Default',

        'field'            => Legacies_Empire::RESOURCE_METAL,
        'production_field' => 'metal_perhour',
        'ratio_field'      => 'metal_porcent',
        'storage_field'    => 'metal_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_METAL_MINE => 'metal_mine_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_METAL_STORAGE
        ),
    Legacies_Empire::RESOURCE_CRISTAL   => array(
        'template' => 'empire/resources/default.phtml',
        'renderer' => 'Legacies_Empire_Block_Resource_Default',

        'field'            => Legacies_Empire::RESOURCE_CRISTAL,
        'production_field' => 'crystal_perhour',
        'ratio_field'      => 'crystal_porcent',
        'storage_field'    => 'crystal_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_CRISTAL_MINE => 'crystal_mine_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_CRISTAL_STORAGE
        ),
    Legacies_Empire::RESOURCE_DEUTERIUM => array(
        'template' => 'empire/resources/default.phtml',
        'renderer' => 'Legacies_Empire_Block_Resource_Default',

        'field'            => Legacies_Empire::RESOURCE_DEUTERIUM,
        'production_field' => 'deuterium_perhour',
        'ratio_field'      => 'deuterium_porcent',
        'storage_field'    => 'deuterium_max',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => 'deuterium_sintetizer_porcent'
            ),
        'storage'          => Legacies_Empire::ID_BUILDING_DEUTERIUM_TANK
        ),
    Legacies_Empire::RESOURCE_ENERGY => array(
        'template' => 'empire/resources/energy.phtml',
        'renderer' => 'Legacies_Empire_Block_Resource_Default',

        'field'            => 'energy_used',
        'production_field' => 'energy_max',
        'storage_field'    => 'energy_used',
        'production'       => array(
            Legacies_Empire::ID_BUILDING_SOLAR_PLANT    => 'solar_plant_porcent',
            Legacies_Empire::ID_BUILDING_FUSION_REACTOR => 'fusion_plant_porcent',
            Legacies_Empire::ID_SHIP_SOLAR_SATELLITE    => 'solar_satelit_porcent'
            ),
        'storage'          => null
        )
    );
