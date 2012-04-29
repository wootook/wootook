<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 20/03/12
 * Time: 21:48
 * To change this template use File | Settings | File Templates.
 */
interface Legacies_Core_Unit
{
    const TYPE_BUILDING        = 'building';
    const TYPE_BUILDING_MOON   = 'building_moon';
    const TYPE_BUILDING_PLANET = 'building_planet';
    const TYPE_RESEARCH        = 'technology';
    const TYPE_SHIP            = 'ship';
    const TYPE_DEFENSE         = 'defense';
    const TYPE_SPECIAL         = 'special';
    const TYPE_PRODUCTION      = 'production';
    const TYPE_FLEET_MISSION   = 'mission';

    const RESOURCE_TITANIUM   = 'titanium';
    const RESOURCE_SILICIUM   = 'silicium';
    const RESOURCE_HYDROGEN   = 'hydrogen';
    const RESOURCE_ANTIMATTER = 'antimatter';
    const RESOURCE_ENERGY     = 'energy';

    const ID_BUILDING_TITANIUM_MINE            = 'titanium-mine';
    const ID_BUILDING_SILICIUM_MINE            = 'silicium-mine';
    const ID_BUILDING_HYDROGEN_PUMP            = 'hydrogen-pump';
    const ID_BUILDING_SOLAR_PLANT              = 'solar-plant';
    const ID_BUILDING_ATOMIC_FUSION_PLANT      = 'atomic-fusion-plant';
    const ID_BUILDING_SHIPYARD                 = 'shipyard';
    const ID_BUILDING_TITANIUM_STORAGE         = 'titanium-storage';
    const ID_BUILDING_SILICIUM_STORAGE         = 'silicium-storage';
    const ID_BUILDING_HYDROGEN_TANK            = 'hydrogen-tank';
}
