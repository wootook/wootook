<?php

include './bootstrap.php';

if (!extension_loaded('xdebug')) {
    header('Content-Type: text/plain');
}

$planet = new Legacies_Empire_Model_Planet(array(
    'last_update'       => 0,
    'planet_type'       => 1,

    'metal'             => 0,
    'metal_perhour'     => 20,
    'crystal'           => 0,
    'crystal_perhour'   => 10,
    'deuterium'         => 0,
    'deuterium_perhour' => 0,
    'energy_max'        => 0,
    'energy_used'       => 0,

    'metal_mine_porcent'           => 10,
    'crystal_mine_porcent'         => 10,
    'solar_plant_porcent'          => 10,
    'fusion_plant_porcent'         => 10,
    'solar_satelit_porcent'        => 10,
    'deuterium_sintetizer_porcent' => 10,

    'metal_mine'           => 10,
    'crystal_mine'         => 0,
    'deuterium_sintetizer' => 0,
    'solar_plant'          => 0,
    'fusion_plant'         => 0,
    'robot_factory'        => 0,
    'nano_factory'         => 0,
    'hangar'               => 0,
    'metal_store'          => 5,
    'crystal_store'        => 5,
    'deuterium_store'      => 5,

    'rpg_stockeur' => 0
    ));

var_dump($planet->getAllDatas());

$planet->updateStorages(3600);

var_dump($planet->getAllDatas());

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => 3600
    ));

var_dump($planet->getAllDatas());