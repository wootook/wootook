<?php return array(
    'layout.prepare.before' => array(),
    'layout.prepare.after' => array(
        array('Wootook_Player_Model_Entity', 'layoutPrepareAfterListener')
        ),
    'login.failure' => array(),
    'login.success' => array(),
    'logout.success' => array(),
    'register.before' => array(),
    'register.failure' => array(),
    'register.success' => array(),
    'planet.update' => array(
        array('Wootook_Empire_Model_Planet', 'planetUpdateListener'),
        array('Legacies_Empire_Model_Planet_Building_Shipyard', 'planetUpdateListener'),
        array('Legacies_Officers_Model_Observer', 'planetUpdateListener')
        ),
    'user.init' => array(),
    'planet.init' => array(
        array('Wootook_Empire_Model_Galaxy_Position', 'initPlanetListerner')
        ),
    'planet.shipyard.check-availability' => array(),
    'planet.shipyard.update-queue.before' => array(),
    'planet.shipyard.update-queue.after' => array(),
    'planet.shipyard.append-queue.before' => array(),
    'planet.shipyard.append-queue.after' => array(),
    'planet.building.building-time' => array(),
    'planet.shipyard.building-time' => array(),
    'planet.building.speed-enhancement' => array(
        array('Legacies_Empire_Model_Planet_Building_NaniteFactory', 'buildingEnhancementListener'),
        array('Legacies_Empire_Model_Planet_Building_RoboticFactory', 'buildingEnhancementListener')
        ),
    'planet.shipyard.speed-enhancement' => array(
        array('Legacies_Empire_Model_Planet_Building_NaniteFactory', 'buildingEnhancementListener'),
        array('Legacies_Empire_Model_Planet_Building_RoboticFactory', 'buildingEnhancementListener')
        ),
    'planet.research-lab.technology.speed-enhancement' => array(
        array('Legacies_Empire_Model_Player_Technology_IntergalacticResearchNetwork', 'researchTechnologyEnhancementListener')
        )
    );
