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

namespace Wootook\Core\Config;

use Wootook\Core\Exception as CoreException;

class Node
    implements \ArrayAccess, \Iterator, \Countable
{
    protected $_children = array();

    protected $_parent = null;

    protected static $_attributeNameCache = array();

    public function __construct(Array $data = array(), $parent = null)
    {
        $this->_init($data, $parent);
    }

    public function setData(Array $data = array())
    {
        $this->_init($data);

        return $this;
    }

    protected function _init(Array $data, $parent = null)
    {
        if ($parent !== null) {
            $this->_parent = $parent;
        }

        $this->_children = array();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->offsetSet($key, new self($value, $this));
            } else  {
                $this->offsetSet($key, $value);
            }
        }

        return $this;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function setConfig($path, $value)
    {
        $explodedPath = explode('/', $path);

        $length = count($explodedPath);
        $currentNode = $this;
        for ($i = 0; $i < $length; $i++) {
            if ($i < ($length - 1)) {
                if (!$currentNode->offsetExists($explodedPath[$i])) {
                    $newNode = new Node(array(), $currentNode);
                    $currentNode->offsetSet($explodedPath[$i], $newNode);
                }
            } else if (is_array($value)) {
                $currentNode->offsetSet($explodedPath[$i], new self($value, $this));
                break;
            } else {
                $currentNode->offsetSet($explodedPath[$i], $value);
                break;
            }

            $currentNode = $currentNode->offsetGet($explodedPath[$i]);

            if (!$currentNode instanceof Node) {
                throw new CoreException\RuntimeException();
            }
        }

        return $this;
    }

    public function getConfig($path)
    {
        $explodedPath = explode('/', $path);

        $length = count($explodedPath);
        $currentNode = $this;
        for ($i = 0; $i < $length; $i++) {
            if (!$currentNode instanceof Node) {
                throw new CoreException\RuntimeException();
            }
            if (!$currentNode->offsetExists($explodedPath[$i])) {
                return null;
            }

            $currentNode = $currentNode->offsetGet($explodedPath[$i]);
        }

        return $currentNode;
    }

    public function toArray()
    {
        $result = array();
        foreach ($this->_children as $key => $child) {
            if ($child instanceof self) {
                $result[$key] = $child->toArray();
            } else if ($child instanceof Leaf) {
                $result[$key] = $child->getValue();
            } else {
                $result[$key] = $child;
            }
        }
        return $result;
    }

    public function reset()
    {
        $this->_children = array();
        $this->_parent = null;

        return $this;
    }

    public function merge(self $node)
    {
        foreach ($node->_children as $offset => $value) {
            if ($value instanceof self) {
                if (!$this->offsetExists($offset)) {
                    $this->offsetSet($offset, new self(array(), $this));
                }
                $this->offsetGet($offset)->merge($value);
            } else {
                $this->offsetSet($offset, $value);
            }
        }

        return $this;
    }

    private function _resolveAttributeName($key)
    {
        if (is_numeric($key)) {
            return $key;
        }
        if (!isset(self::$_attributeNameCache[$key])) {
            self::$_attributeNameCache[$key] = strtolower(preg_replace('#([A-Z])#', '-\\1', $key));
        }
        return self::$_attributeNameCache[$key];
    }

    public function __get($offset)
    {
        return $this->offsetGet($this->_resolveAttributeName($offset));
    }

    public function __set($offset, $value)
    {
        return $this->offsetSet($this->_resolveAttributeName($offset), $value);
    }

    public function __isset($offset)
    {
        return $this->offsetExists($this->_resolveAttributeName($offset));
    }

    public function __unset($offset)
    {
        return $this->offsetUnset($this->_resolveAttributeName($offset));
    }

    public function __clone()
    {
        $clone = new self();

        foreach ($this->_children as $childName => $childNode) {
            if ($childNode instanceof self) {
                $childNode = clone $childNode;
                $childNode->_parent = $clone;
            }

            $clone->offsetSet($childName, $childNode);
        }

        return $clone;
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_children[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
             $this->_children[] = $value;
        } else {
            $this->_children[$offset] = $value;
        }

        return $value;
    }

    public function offsetExists($offset)
    {
        if (isset($this->_children[$offset])) {
            return true;
        }
        return false;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_children[$offset]);
        }
    }

    public function current()
    {
        return current($this->_children);
    }

    public function key()
    {
        return key($this->_children);
    }

    public function next()
    {
        return next($this->_children);
    }

    public function rewind()
    {
        return reset($this->_children);
    }

    public function valid()
    {
        return key($this->_children) !== null;
    }

    public function count()
    {
        return count($this->_children);
    }
}
