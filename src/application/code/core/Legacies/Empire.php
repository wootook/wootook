<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */

class Legacies_Empire
{
    const TYPE_BUILDING        = 'build';
    const TYPE_BUILDING_MOON   = 'build_moon';
    const TYPE_BUILDING_PLANET = 'build_planet';
    const TYPE_RESEARCH        = 'tech';
    const TYPE_SHIP            = 'fleet';
    const TYPE_DEFENSE         = 'defense';
    const TYPE_SPECIAL         = 'special';
    const TYPE_OFFICER         = 'officier';
    const TYPE_PRODUCTION      = 'prod';
    const TYPE_FLEET_MISSION   = 'mission';

    const RESOURCE_METAL      = 'metal';
    const RESOURCE_CRISTAL    = 'cristal';
    const RESOURCE_DEUTERIUM  = 'deuterium';
    const RESOURCE_ENERGY     = 'energy';

    const RESOURCE_MULTIPLIER = 'factor';
    const RESOURCE_FORMULA    = 'formule';
    const RESOURCE_CLASS      = 'class';
    const BASE_BUILDING_TIME  = 'base_time';

    const SHIPS_CONSUMPTION_PRIMARY   = 'consumption';
    const SHIPS_CELERITY_PRIMARY      = 'speed';
    const SHIPS_CONSUMPTION_SECONDARY = 'consumption2';
    const SHIPS_CELERITY_SECONDARY    = 'speed2';
    const SHIPS_CAPACITY              = 'capacity';

    const ID_BUILDING_METAL_MINE            = 1;
    const ID_BUILDING_CRISTAL_MINE          = 2;
    const ID_BUILDING_DEUTERIUM_SYNTHETISER = 3;
    const ID_BUILDING_SOLAR_PLANT           = 4;
    const ID_BUILDING_FUSION_REACTOR        = 12;
    const ID_BUILDING_ROBOTIC_FACTORY       = 14;
    const ID_BUILDING_NANITE_FACTORY        = 15;
    const ID_BUILDING_SHIPYARD              = 21;
    const ID_BUILDING_METAL_STORAGE         = 22;
    const ID_BUILDING_CRISTAL_STORAGE       = 23;
    const ID_BUILDING_DEUTERIUM_TANK        = 24;
    const ID_BUILDING_RESEARCH_LAB          = 31;
    const ID_BUILDING_TERRAFORMER           = 33;
    const ID_BUILDING_ALLIANCE_DEPOT        = 34;
    const ID_BUILDING_LUNAR_BASE            = 41;
    const ID_BUILDING_SENSOR_PHALANX        = 42;
    const ID_BUILDING_JUMP_GATE             = 43;
    const ID_BUILDING_MISSILE_SILO          = 44;

    const ID_RESEARCH_ESPIONAGE_TECHNOLOGY           = 106;
    const ID_RESEARCH_COMPUTER_TECHNOLOGY            = 108;
    const ID_RESEARCH_WEAPON_TECHNOLOGY              = 109;
    const ID_RESEARCH_SHIELDING_TECHNOLOGY           = 110;
    const ID_RESEARCH_ARMOUR_TECHNOLOGY              = 111;
    const ID_RESEARCH_ENERGY_TECHNOLOGY              = 113;
    const ID_RESEARCH_HYPERSPACE_TECHNOLOGY          = 114;
    const ID_RESEARCH_COMBUSTION_DRIVE               = 115;
    const ID_RESEARCH_IMPULSE_DRIVE                  = 117;
    const ID_RESEARCH_HYPERSPACE_DRIVE               = 118;
    const ID_RESEARCH_LASER_TECHNOLOGY               = 120;
    const ID_RESEARCH_ION_TECHNOLOGY                 = 121;
    const ID_RESEARCH_PLASMA_TECHNOLOGY              = 122;
    const ID_RESEARCH_INTERGALACTIC_RESEARCH_NETWORK = 123;
    const ID_RESEARCH_EXPEDITION_TECHNOLOGY          = 124;
    const ID_RESEARCH_ASTROPHYSICS                   = 124;
    const ID_RESEARCH_ORE_MINING                     = 125;
    const ID_RESEARCH_GRAVITON_TECHNOLOGY            = 199;

    const ID_SHIP_LIGHT_TRANSPORT = 202;
    const ID_SHIP_LARGE_TRANSPORT = 203;
    const ID_SHIP_LIGHT_FIGHTER   = 204;
    const ID_SHIP_HEAVY_FIGHTER   = 205;
    const ID_SHIP_CRUISER         = 206;
    const ID_SHIP_BATTLESHIP      = 207;
    const ID_SHIP_COLONY_SHIP     = 208;
    const ID_SHIP_RECYCLER        = 209;
    const ID_SHIP_SPY_DRONE       = 210;
    const ID_SHIP_BOMBER          = 211;
    const ID_SHIP_SOLAR_SATELLITE = 212;
    const ID_SHIP_DESTRUCTOR      = 213;
    const ID_SHIP_DEATH_STAR      = 214;
    const ID_SHIP_BATTLECRUISER   = 215;
    const ID_SHIP_SUPERNOVA       = 216;
    const ID_SHIP_ORE_MININER     = 217;

    const ID_DEFENSE_ROCKET_LAUNCHER   = 401;
    const ID_DEFENSE_LIGHT_LASER       = 402;
    const ID_DEFENSE_HEAVY_LASER       = 403;
    const ID_DEFENSE_ION_CANNON        = 404;
    const ID_DEFENSE_GAUSS_CANNON      = 405;
    const ID_DEFENSE_PLASMA_TURRET     = 406;
    const ID_DEFENSE_SMALL_SHIELD_DOME = 407;
    const ID_DEFENSE_LARGE_SHIELD_DOME = 408;

    const ID_SPECIAL_ANTIBALLISTIC_MISSILE  = 502;
    const ID_SPECIAL_INTERPLANETARY_MISSILE = 503;

    const ID_COMBAT_SHIELDS    = 'shield';
    const ID_COMBAT_FIREPOWER  = 'attack';
    const ID_COMBAT_RAPID_FIRE = 'sd';

    const ID_MISSION_ATTACK        = 1;
    const ID_MISSION_GROUP_ATTACK  = 2;
    const ID_MISSION_TRANSPORT     = 3;
    const ID_MISSION_STATION       = 4;
    const ID_MISSION_STATION_ALLY  = 5;
    const ID_MISSION_SPY           = 6;
    const ID_MISSION_SETTLE_COLONY = 7;
    const ID_MISSION_RECYCLE       = 8;
    const ID_MISSION_DESTROY       = 9;
    const ID_MISSION_MISSILES      = 10;
    const ID_MISSION_EXPEDITION    = 15;
    const ID_MISSION_ORE_MINING    = 16;

    public static function getFieldName($id)
    {
        $fieldsAlias = Wootook_Empire_Helper_Config_FieldsAlias::getSingleton();

        if (!isset($fieldsAlias[$id])) {
            return null;
        }

        return $fieldsAlias[$id];
    }
}
