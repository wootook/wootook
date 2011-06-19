<?php

include './bootstrap.php';

$planet = Legacies_Empire_Model_Planet::factory(1);

var_dump($resource->getAllDatas(), $production->getAllDatas(), array(
    'last_update' => $planet->getData('last_update'),
    'metal' => $planet->getData('metal'),
    'cristal' => $planet->getData('crystal'),
    'deuterium' => $planet->getData('deuterium'),
    'energy_max' => $planet->getData('energy_max'),
    'energy_used' => $planet->getData('energy_used')
    ));

Legacies::dispatchEvent('planet.update', array(
    'planet' => $planet
    ));

header('Content-Type: text/plain');
var_dump(array(
    'last_update' => $planet->getData('last_update'),
    'metal' => $planet->getData('metal'),
    'cristal' => $planet->getData('crystal'),
    'deuterium' => $planet->getData('deuterium'),
    'energy_max' => $planet->getData('energy_max'),
    'energy_used' => $planet->getData('energy_used')
    ));