<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 25/03/12
 * Time: 11:05
 * To change this template use File | Settings | File Templates.
 */
class Legacies_Empire_Model_Player_Technology_IntergalacticResearchNetwork
    implements Wootook_Empire_Model_Player_TechnologyInterface
{
    public static function researchTechnologyEnhancementListener(Wootook_Core_Event $event)
    {
        /** @var float $enhancement */
        $enhancement = $event->getData('enhancement');

        /** @var Wootook_Empire_Model_Planet $planet */
        $planet = $event->getData('planet');

        /** @var Wootook_Player_Model_Entity $player */
        $player = $event->getData('player');


        $virtualLevel = 1 + $planet->getElement(Legacies_Empire::ID_BUILDING_RESEARCH_LAB);

        if (($network = $player->getElement(Legacies_Empire::ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK)) > 0) {
            $planetCollection = $player->getPlanetCollection(Wootook_Empire_Model_Planet::TYPE_PLANET);

            $fields = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();
            $planetCollection->addFieldToFilter('id', array(array('nin' => $planet->getId())))
                ->addOrderBy($fields[Legacies_Empire::ID_BUILDING_RESEARCH_LAB], 'DESC')
                ->setPageSize($network)
            ;

            $select = $planetCollection->getSelect();
            $select->reset(Wootook_Core_Database_Sql_Select::COLUMNS);
            $select->column(array('count' => new Wootook_Core_Database_Sql_Placeholder_Expression(
                "COUNT({$select->quote($fields[Legacies_Empire::ID_BUILDING_RESEARCH_LAB])})")));

            $statement = $select->prepare();
            $virtualLevel += $statement->fetchColumn();
        }

        $event->setData('enhancement', $enhancement * $virtualLevel);
    }
}
