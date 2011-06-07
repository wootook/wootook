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
}