<?php

namespace Wootook\Core\DependencyInjection;

use Wootook\Core\Base\Service,
    Wootook\Core\Config,
    Wootook\Core\Exception as CoreException;

class Registry
{
    use Service\App;

    protected $_instances = array();

    protected function _construct()
    {
    }

    public function clear()
    {
        $this->_instances = array();

        return $this;
    }

    public function add($identifier, $value)
    {
        if ($this->has($identifier)) {
            throw new CoreException\RuntimeException('Instance already exists.');
        }
        $this->set($identifier, $value);
    }

    public function set($identifier, $value)
    {
        if (isset($this->_instances[$identifier])) {
            throw new CoreException\BadMethodCallException;
        }
        $this->_instances[$identifier] = $value;

        return $this;
    }

    public function get($identifier)
    {
        return $this->_instances[$identifier];
    }

    public function delete($identifier)
    {
        unset($this->_instances[$identifier]);

        return $this;
    }

    public function has($identifier)
    {
        return isset($this->_instances[$identifier]);
    }
}
