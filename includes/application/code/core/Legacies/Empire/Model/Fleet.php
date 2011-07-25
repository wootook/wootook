<?php
/**
 *
 * Enter description here ...
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 */
class Legacies_Empire_Model_Fleet
    extends Legacies_Core_Entity
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

    public function isOwnedBy(Legacies_Empire_Model_User $user)
    {
        if ($this->getData('fleet_owner') == $user->getId()) {
            return true;
        }
        return false;
    }

    public function isMission($missionType)
    {
        if ($this->getData('fleet_mission') == $missionType) {
            return true;
        }
        return false;
    }

    public function getRowClass()
    {
        if ($this->isMission(Legacies_Empire::ID_MISSION_ATTACK) || $this->isMission(Legacies_Empire::ID_MISSION_GROUP_ATTACK)) {
            return 'attack';
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
}