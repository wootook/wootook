<?php return array(
    'login.failure' => array(),
    'login.success' => array(),
    'logout.success' => array(),
    'register.before' => array(),
    'register.failure' => array(),
    'register.success' => array(
        array('Legacies_Empire_Model_Planet', 'registrationListener')
        ),
    'planet.update' => array(
        array('Legacies_Empire_Model_Planet', 'planetUpdateListener'),
        array('Legacies_Empire_Model_Planet_Building_Shipyard', 'planetUpdateListener')
        ),
    'user.init' => array(
        array('Legacies_Empire_Model_Planet', 'registrationListener')
        ),
    'planet.init' => array(
        array('Legacies_Empire_Model_Galaxy_Position', 'initPlanetListerner')
        ),
    'planet.shipyard.check-availability' => array(),
    'planet.shipyard.update-queue.before' => array(),
    'planet.shipyard.update-queue.after' => array(),
    'planet.shipyard.append-queue.before' => array(),
    'planet.shipyard.append-queue.after' => array(),
    'planet.building.building-time' => array(
        array('Legacies_Empire_Model_Planet_Building_NaniteFactory', 'buildingTimeListener'),
        array('Legacies_Empire_Model_Planet_Building_RoboticFactory', 'buildingTimeListener')
        ),
    'planet.shipyard.building-time' => array(
        array('Legacies_Empire_Model_Planet_Building_NaniteFactory', 'buildingTimeListener'),
        array('Legacies_Empire_Model_Planet_Building_RoboticFactory', 'buildingTimeListener')
        )
    );
