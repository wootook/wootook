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

namespace Wootook\Core\Event;

/**
 * EventTarget
 */
trait EventTarget
{
    protected $_eventListeners = array();

    /**
     * @param $type
     * @param Closure $listener
     * @return EventTarget
     */
    public function registerEventListener($type, Closure $listener)
    {
        if (!isset($this->_eventListeners[$type])) {
            $this->_eventListeners[$type] = array();
        }
        $this->_eventListeners[$type][] = $listener;

        return $this;
    }

    /**
     * @param $type
     * @param Closure $listener
     * @return EventTarget
     */
    public function unregisterEventListener($type, Closure $listener)
    {
        if (!isset($this->_eventListeners[$type])) {
            return $this;
        }
        $key = array_search($listener, $this->_eventListeners[$type]);
        unset($this->_eventListeners[$type][$key]);

        return $this;
    }

    /**
     * @param $type
     * @return Event
     */
    public function dispatchEvent($type, Array $datas = array())
    {
        if (!isset($this->_eventListeners[$type])) {
            return $this;
        }

        $event = new Event($datas);
        $event->setType($type);
        $event->setTarget($this);

        foreach ($this->_eventListeners[$type] as $eventListener) {
            $eventListener($event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }
}
