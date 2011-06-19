<?php

abstract class Legacies_Empire_Model_Game_Abstract
    extends Legacies_Core_Model
    implements Iterator, Countable
{
    public function count()
    {
        return count($this->_data);
    }

    public function current()
    {
        return current($this->_data);
    }

    public function next()
    {
        return next($this->_data);
    }

    public function key()
    {
        return key($this->_data);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->_data);
    }
}