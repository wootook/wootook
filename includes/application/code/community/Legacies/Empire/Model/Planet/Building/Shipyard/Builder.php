<?php

class Legacies_Empire_Model_Planet_Building_Shipyard_Builder
    extends Legacies_Empire_Model_BuilderAbstract
{
    public function __construct($currentPlanet, $currentUser)
    {
        parent::__construct($currentPlanet, $currentUser);

        $this->_unserializeQueue($currentPlanet->getData('b_hangar_id'));
    }

    public function _initItem($shipId, $qty)
    {
        return new Legacies_Empire_Model_Planet_Building_Shipyard_Item(array(
            'ship_id'    => $shipId,
            'qty'        => $qty,
            'created_at' => time(),
            'updated_at' => time()
            ));
    }
}