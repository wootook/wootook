<?php

include './bootstrap.php';

if (!extension_loaded('xdebug')) {
    header('Content-Type: text/plain');
}

$user = new Legacies_Empire_Model_User(array(
    'rpg_stockeur' => 0,

    'b_laboratory' => 0,
    'b_laboratory_id' => serialize(array()),

    "spy_tech"              => 12,
    "computer_tech"         => 8,
    "military_tech"         => 10,
    "defence_tech"          => 10,
    "shield_tech"           => 10,
    "energy_tech"           => 8,
    "hyperspace_tech"       => 8,
    "combustion_tech"       => 6,
    "impulse_motor_tech"    => 6,
    "hyperspace_motor_tech" => 6,
    "laser_tech"            => 6,
    "ionic_tech"            => 6,
    "buster_tech"           => 6,
    "intergalactic_tech"    => 0,
    "expedition_tech"       => 0,
    "graviton_tech"         => 0,
    ));

$data = array(
    'last_update'       => 0,
    'planet_type'       => 1,

    'b_building_id'     => '',
    'b_hangar_id'       => serialize(array(/*array(
            'ship_id'    => Legacies_Empire::ID_SHIP_LIGHT_TRANSPORT,
            'qty'        => 10,
            'created_at' => 0,
            'updated_at' => 0
        ), array(
            'ship_id'    => Legacies_Empire::ID_SHIP_LARGE_TRANSPORT,
            'qty'        => 20,
            'created_at' => 600,
            'updated_at' => 600
        ), array(
            'ship_id'    => Legacies_Empire::ID_SHIP_LARGE_TRANSPORT,
            'qty'        => 30,
            'created_at' => 650,
            'updated_at' => 650
        ), array(
            'ship_id'    => Legacies_Empire::ID_SHIP_LARGE_TRANSPORT,
            'qty'        => 40,
            'created_at' => 700,
            'updated_at' => 700
        )*/)),

    'metal'             => 500000,
    'metal_perhour'     => 20,
    'cristal'           => 500000,
    'cristal_perhour'   => 10,
    'deuterium'         => 300000,
    'deuterium_perhour' => 0,
    'energy_max'        => 0,
    'energy_used'       => 0,

    'metal_mine_porcent'           => 10,
    'cristal_mine_porcent'         => 10,
    'solar_plant_porcent'          => 10,
    'fusion_plant_porcent'         => 10,
    'solar_satelit_porcent'        => 10,
    'deuterium_sintetizer_porcent' => 10,

    'metal_mine'           => 20,
    'cristal_mine'         => 19,
    'deuterium_sintetizer' => 0,
    'solar_plant'          => 20,
    'fusion_plant'         => 0,
    'robot_factory'        => 0,
    'nano_factory'         => 0,
    'hangar'               => 12,
    'metal_store'          => 10,
    'cristal_store'        => 10,
    'deuterium_store'      => 10,

    'rpg_stockeur' => 0
    );
$planet = new Legacies_Empire_Model_Planet($data);

$planet->setUser($user);
//var_dump(array('$planet->setUser($user)', array_diff($planet->getAllDatas(), $data)));
$data = $planet->getAllDatas();

$planet->updateStorages(0);
//var_dump(array('$planet->updateStorages(0)', array_diff($planet->getAllDatas(), $data)));
$data = $planet->getAllDatas();

$planet->updateResourceProduction(0);
//var_dump(array('$planet->updateResourceProduction(0)', array_diff($planet->getAllDatas(), $data)));
$data = $planet->getAllDatas();

$planet->getShipyard()->appendQueue(Legacies_Empire::ID_SHIP_LIGHT_FIGHTER, 10, 0);
//var_dump(array('$planet->getShipyard()->appendQueue(Legacies_Empire::ID_SHIP_LIGHT_FIGHTER, 10, 0)', array_diff($planet->getAllDatas(), $data)));
$data = $planet->getAllDatas();

$planet->appendBuildingQueue(Legacies_Empire::ID_BUILDING_CRISTAL_MINE, false, 0);
//var_dump(array('$planet->appendBuildingQueue(Legacies_Empire::ID_BUILDING_CRISTAL_MINE, false, 0)', array_diff($planet->getAllDatas(), $data)));
$data = $planet->getAllDatas();

$userData = $user->getAllDatas();
$planet->getResearchLab()->appendQueue(Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY, false, 0);
var_dump(array('$planet->getResearchLab()->appendQueue(Legacies_Empire::ID_RESEARCH_ENERGY_TECHNOLOGY, false, 0)', array_diff($planet->getAllDatas(), $data), array_diff($user->getAllDatas(), $userData)));
$data = $planet->getAllDatas();

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => 5400
    ));

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => (3600 * 20)
    ));

$planet->appendBuildingQueue(Legacies_Empire::ID_BUILDING_CRISTAL_MINE, false, 3600);

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => (3600 * 30)
    ));

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => (3600 * 5000)
    ));

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();

$planet->appendBuildingQueue(Legacies_Empire::ID_BUILDING_CRISTAL_MINE, false, 0);
$planet->appendBuildingQueue(Legacies_Empire::ID_BUILDING_SOLAR_PLANT, false, 0);

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet,
    'time'   => (3600 * 10000)
    ));

var_dump(array_diff($planet->getAllDatas(), $data));
$data = $planet->getAllDatas();