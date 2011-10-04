<?php
/**
 *
 * Enter description here ...
 *
 * @uses Wootook_Object
 * @uses Legacies_Empire
 */
class Wootook_Empire_Model_Fleet
    extends Wootook_Core_Entity
{
    protected static $_instances = array();

    protected $_eventPrefix = 'fleet';
    protected $_eventObject = 'fleet';

    public static function factory($id)
    {
        if ($id === null) {
            return new self();
        }

        $id = intval($id);
        if (!isset(self::$_instances[$id])) {
            $instance = new self();
            $params = func_get_args();
            call_user_func_array(array($instance, 'load'), $params);
            self::$_instances[$id] = $instance;
        }
        return self::$_instances[$id];
    }

    protected function _init()
    {
        $this->setIdFieldName('fleet_id');
        $this->setTableName('fleets');
    }

    public static function planetListener($eventData)
    {
    }

    public function isOwnedBy(Wootook_Empire_Model_User $user)
    {
        if ($this->getData('fleet_owner') == $user->getId()) {
            return true;
        }
        return false;
    }

    public function getOwner()
    {
        if ($id = $this->getData('fleet_owner')) {
            return Wootook_Empire_Model_User::factory($id);
        }
        return null;
    }

    public function isMission($missionType)
    {
        if ($this->getData('fleet_mission') == $missionType) {
            return true;
        }
        return false;
    }

    public function getRowClass(Wootook_Empire_Model_User $user = null)
    {
        if ($this->isMission(Legacies_Empire::ID_MISSION_ATTACK) || $this->isMission(Legacies_Empire::ID_MISSION_GROUP_ATTACK)) {
            if ($user !== null && $this->isOwnedBy($user)) {
                return 'attack';
            } else {
                return 'ownattack';
            }
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_TRANSPORT)) {
            return 'transport';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_STATION) || $this->isMission(Legacies_Empire::ID_MISSION_STATION_ALLY)) {
            return 'station';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_SETTLE_COLONY)) {
            return 'settle';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_RECYCLE)) {
            return 'recycle';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_DESTROY)) {
            return 'destroy';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_MISSILES)) {
            return 'missiles';
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_EXPEDITION)) {
            return 'expedition';
        }
    }

    public function getMissionLabel()
    {
        if ($this->isMission(Legacies_Empire::ID_MISSION_ATTACK) || $this->isMission(Legacies_Empire::ID_MISSION_GROUP_ATTACK)) {
            return Wootook::__('Attack');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_TRANSPORT)) {
            return Wootook::__('Transport');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_STATION) || $this->isMission(Legacies_Empire::ID_MISSION_STATION_ALLY)) {
            return Wootook::__('Station');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_SETTLE_COLONY)) {
            return Wootook::__('Settle');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_RECYCLE)) {
            return Wootook::__('Recycle');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_DESTROY)) {
            return Wootook::__('Destroy');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_MISSILES)) {
            return Wootook::__('Missiles Launch');
        } else if ($this->isMission(Legacies_Empire::ID_MISSION_EXPEDITION)) {
            return Wootook::__('Expedition');
        }
        return Legacies::__('Unknown');
    }

    public function getStartTime()
    {
        return $this->getData('fleet_start_time');
    }

    public function getActionTime()
    {
        return $this->getData('fleet_end_stay');
    }

    public function getArrivalTime()
    {
        if ($this->isMission(Legacies_Empire::ID_MISSION_STATION) || $this->isMission(Legacies_Empire::ID_MISSION_STATION_ALLY)) {
            return $this->getActionTime();
        }
        return $this->getData('fleet_end_time');
    }

    public function getOriginPlanet()
    {
        $coords = array(
            'galaxy'   => $this->getData('fleet_start_galaxy'),
            'system'   => $this->getData('fleet_start_system'),
            'position' => $this->getData('fleet_start_planet')
            );
        $type = $this->getData('fleet_start_type');

        return Wootook_Empire_Model_Planet::factoryFromCoords($coords, $type);
    }

    public function getDestinationPlanet()
    {
        $coords = array(
            'galaxy'   => $this->getData('fleet_end_galaxy'),
            'system'   => $this->getData('fleet_end_system'),
            'position' => $this->getData('fleet_end_planet')
            );
        $type = $this->getData('fleet_end_type');

        return Wootook_Empire_Model_Planet::factoryFromCoords($coords, $type);
    }
}