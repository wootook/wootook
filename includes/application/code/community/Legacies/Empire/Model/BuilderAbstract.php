<?php

abstract class Legacies_Empire_Model_BuilderAbstract
    implements Countable, Serializable, Iterator
{
    /**
     * Planet instance
     * @var Legacies_Empire_Model_Planet
     */
    protected $_currentPlanet = null;

    /**
     * User instance
     * @var Legacies_Empire_Model_User
     */
    protected $_currentUser = null;

    /**
     * construction queue
     * @var array
     */
    private $_queue = null;

    /**
     * construction queue
     * @var array
     */
    protected $_itemClass = 'Legacies_Empire_Model_Builder_Item';

    /**
     * construction queue
     * @var array
     */
    protected $_index = 0;

    /**
     *
     * @param Legacies_Empire_Model_Planet $currentPlanet
     * @param Legacies_Empire_Model_User $currentUser
     */
    public function __construct(Legacies_Empire_Model_Planet $currentPlanet, Legacies_Empire_Model_User $currentUser)
    {
        $this->_currentPlanet = $currentPlanet;
        $this->_currentUser = $currentUser;

        $this->init();
    }

    abstract public function init();

    abstract protected function _initItem();

    public function enqueue()
    {
        $params = func_get_args();
        $item = call_user_func_array(array($this, '_initItem'), $params);
        $item->setIndex($this->_index);
        $this->_queue[$this->_index++] = $item;

        return $this;
    }

    public function dequeue($item)
    {
        unset($this->_queue[(int) $item->getIndex()]);

        return $this;
    }

    protected function getItem($itemIndex)
    {
        if (isset($this->_queue[$itemIndex])) {
            return $this->_queue[$itemIndex];
        }

        return null;
    }

    protected function _serializeQueue()
    {
        $serialize = array();
        foreach ($this->_queue as $item) {
            $serialize[] = $item->getAllDatas();
        }
        return serialize($serialize);
    }

    protected function _unserializeQueue($serialized)
    {
        $this->clearQueue();

        $unserialized = unserialize($serialized);
        if ($unserialized === false) {
            $this->_queue = array();
            return $this;
        }

        foreach ($unserialized as $itemData) {
            call_user_func_array(array($this, 'enqueue'), $itemData);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->_serializeQueue();
    }

    public function getQueue()
    {
        return $this->_queue;
    }

    public function clearQueue()
    {
        $this->_queue = array();
    }

    abstract public function updateQueue($time = null);
    abstract public function appendQueue($typeId, $qty, $time);

    /**
     * Check if an item type is actually buildable on the current
     * planet, depending on the technology and buildings requirements.
     *
     * @param int $typeId
     * @return bool
     */
    public function checkAvailability($typeId)
    {
        $types = Legacies_Empire_Model_Game_Types::getSingleton();
        $requirements = Legacies_Empire_Model_Game_Requirements::getSingleton();

        if (!isset($requirements[$typeId]) || empty($requirements[$typeId])) {
            return true;
        }

        foreach ($requirements[$typeId] as $requirement => $level) {
            if ($types->is($requirement, Legacies_Empire::TYPE_BUILDING) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_RESEARCH) && $this->_currentUser->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_DEFENSE) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            } else if ($types->is($requirement, Legacies_Empire::TYPE_SHIP) && $this->_currentPlanet->hasElement($requirement, $level)) {
                continue;
            }
            return false;
        }

        return true;
    }

    abstract public function getResourcesNeeded($typeId, $level);
    abstract public function getBuildingTime($typeId, $level);

    public function serialize()
    {
        return $this->_serializeQueue();
    }

    public function unserialize($serialized)
    {
        $this->_unserializeQueue($serialized);
    }

    public function count()
    {
        return count($this->_queue);
    }

    public function current()
    {
        return current($this->_queue);
    }

    public function next()
    {
        return next($this->_queue);
    }

    public function key()
    {
        return key($this->_queue);
    }

    public function valid()
    {
        return current($this->_queue) !== false;
    }

    public function rewind()
    {
        reset($this->_queue);
    }
}