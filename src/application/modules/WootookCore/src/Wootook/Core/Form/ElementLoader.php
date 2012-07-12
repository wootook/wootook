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

namespace Wootook\Core\Form;

use Wootook\Core\Profiler,
    Wootook\Core\PluginLoader;

class ElementLoader
    extends PluginLoader\PluginLoader
{
    protected $_form = null;

    public function __construct(Form $form, Array $namespaces = array())
    {
        $this->_form = $form;

        foreach ($namespaces as $namespace => $path) {
            if (is_int($namespace)) {
                $this->registerNamespace($path);
            } else {
                $this->registerNamespace($namespace, $path);
            }
        }
    }

    protected function _load($className, $useSingleton, Array $constructorParams = array())
    {
        $reflection = new \ReflectionClass($className);
        if ($useSingleton && $reflection->implementsInterface('Wootook\\Core\\Base\\Singleton')) {
            $method = $reflection->getMethod('getSingleton');
            return $method->invoke(null);
        }

        try {
            return $reflection->newInstance($this->_form);
        } catch (\ReflectionException $e) {
            Profiler\ErrorProfiler::getSingleton()
                ->addException($e);
            return null;
        }
    }
}
