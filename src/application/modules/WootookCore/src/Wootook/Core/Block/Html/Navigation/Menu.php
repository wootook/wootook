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

namespace Wootook\Core\Block\Html\Navigation;

use Wootook\Core\Block,
    Wootook\Core\Exception as CoreException;

class Menu
    extends Block\Template
{
    public function addLink($name, $label, $title, $uri, Array $params = array(), Array $classes = array(), $template = null, Array $attributes = array())
    {
        $explodedPath = explode('/', $name);
        $baseName = array_pop($explodedPath);
        $parent = $this->_getNode($explodedPath);

        $child = $this->getLayout()
            ->createBlock('core/html.navigation.link', $this->getNameInLayout() . '.' . $baseName, array(
                'url' => array(
                    'uri'    => $uri,
                    'params' => $params
                    ),
                'label'   => $label,
                'title'   => $title,
                'classes' => $classes,
                'attributes' => $attributes
                ));
        $parent->setPartial($baseName, $child);

        if ($template !== null) {
            $child->setTemplate($template);
        }

        return $this;
    }
    public function addStaticLink($name, $label, $title, $uri, Array $params = array(), Array $classes = array(), $template = null, Array $attributes = array())
    {
        $explodedPath = explode('/', $name);
        $baseName = array_pop($explodedPath);
        $parent = $this->_getNode($explodedPath);

        $child = $this->getLayout()
            ->createBlock('core/html.navigation.link', $this->getNameInLayout() . '.' . $baseName, array(
                'url' => array(
                    'uri'    => $uri,
                    'params' => $params,
                    'static' => true
                    ),
                'label'   => $label,
                'title'   => $title,
                'classes' => $classes,
                'attributes' => $attributes
                ));
        $parent->setPartial($baseName, $child);

        if ($template !== null) {
            $child->setTemplate($template);
        }

        return $this;
    }

    public function addExternalLink($name, $label, $title, $url, Array $classes = array(), $template = null, Array $attributes = array())
    {
        $explodedPath = explode('/', $name);
        $baseName = array_pop($explodedPath);
        $parent = $this->_getNode($explodedPath);

        $child = $this->getLayout()
            ->createBlock('core/html.navigation.link', $this->getNameInLayout() . '.' . $baseName, array(
                'url'        => $url,
                'label'      => $label,
                'title'      => $title,
                'classes'    => $classes,
                'attributes' => $attributes
                ));
        $parent->setPartial($baseName, $child);

        if ($template !== null) {
            $child->setTemplate($template);
        }

        return $this;
    }

    public function addPopupLink($name, $label, $title, $uri, Array $params = array(), Array $classes = array(), $template = null, Array $attributes = array())
    {
        $uri = $this->getStaticUrl($uri, $params);

        $attributes['onclick'] = "f('{$params}', '{$label}');return false;";

        return $this->addLink($name, $label, $title, null, array(), $classes, $template, $attributes);
    }

    public function setNodeTitle($path, $title)
    {
        return $this->getNode($path)->setTitle($title);
    }

    public function setNodeTemplate($path, $template)
    {
        return $this->getNode($path)->setTemplate($template);
    }

    public function getNode($path)
    {
        return $this->_getNode(explode('/', $path));
    }

    protected function _getNode($explodedPath)
    {
        $name = array_shift($explodedPath);

        if ($this->hasPartial($name)) {
            $child = $this->getPartial($name);
        } else {
            $child = $this->getLayout()
                ->createBlock('core/html.navigation.node', $this->getNameInLayout() . '.' . $name);

            $this->setPartial($name, $child);
        }

        if ($child instanceof Link) {
            throw new CoreException\RuntimeException('Node is a link. Could not append a child node to a link node.');
        }

        if (count($explodedPath) == 0) {
            return $child;
        }
        return $child->_getNode($explodedPath);
    }
}
