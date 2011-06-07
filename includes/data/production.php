<?php return array(
//
// Buildings production
// {{{
    Legacies_Empire::ID_BUILDING_METAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 40,
        Legacies_Empire::RESOURCE_CRISTAL    => 10,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return (30 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_CRISTAL_MINE => array(
        Legacies_Empire::RESOURCE_METAL      => 30,
        Legacies_Empire::RESOURCE_CRISTAL    => 15,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.6,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return (20 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_DEUTERIUM_SYNTHETISER => array(
        Legacies_Empire::RESOURCE_METAL      => 150,
        Legacies_Empire::RESOURCE_CRISTAL    => 50,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return ((10 * $BuildLevel * pow((1.1), $BuildLevel)) * (-0.002 * $BuildTemp + 1.28)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(30 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_SOLAR_PLANT => array(
        Legacies_Empire::RESOURCE_METAL      => 50,
        Legacies_Empire::RESOURCE_CRISTAL    => 20,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 0,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (20 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),

    Legacies_Empire::ID_BUILDING_FUSION_REACTOR => array(
        Legacies_Empire::RESOURCE_METAL      => 500,
        Legacies_Empire::RESOURCE_CRISTAL    => 200,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 100,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 1.8,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return -(10 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (50 * $BuildLevel * pow((1.1), $BuildLevel)) * (0.1 * $BuildLevelFactor);'
            )
        ),
// }}}

//
// Ships production
// {{{
    Legacies_Empire::ID_SHIP_SOLAR_SATELLITE => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 500,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 0.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return (($BuildTemp / 4) + 20) * $BuildLevel * (0.1 * $BuildLevelFactor);'
            )
        ),
    Legacies_Empire::ID_SHIP_SUPERNOVA => array(
        Legacies_Empire::RESOURCE_METAL      => 0,
        Legacies_Empire::RESOURCE_CRISTAL    => 2000,
        Legacies_Empire::RESOURCE_DEUTERIUM  => 500,
        Legacies_Empire::RESOURCE_ENERGY     => 0,
        Legacies_Empire::RESOURCE_MULTIPLIER => 0.5,
        Legacies_Empire::RESOURCE_FORMULA    => array(
            Legacies_Empire::RESOURCE_METAL     => 'return "0";',
            Legacies_Empire::RESOURCE_CRISTAL   => 'return "0";',
            Legacies_Empire::RESOURCE_DEUTERIUM => 'return "0";',
            Legacies_Empire::RESOURCE_ENERGY    => 'return -(12500 * ($BuildTemp / 4) + 20) * $BuildLevel * (0.1 * $BuildLevelFactor);'
            )
        )
// }}}
    );
