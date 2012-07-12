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

namespace Wootook\Core\Model\Layout;

use Wootook\Core\Config;

class Parser
{
    /**
     *
     * Enter description here ...
     * @param string $filename
     */
    public function parse($filename, $cachePath = null, $cacheLifetime = 86400)
    {
        if ($cachePath !== null && \Wootook::fileExists($cachePath) && $cacheLifetime > 0 && (time() - filemtime($cachePath) <= $cacheLifetime)) {
            return include $cachePath;
        }

        $parsedData = $this->_parse($filename);

        if ($cachePath !== null) {
            $parsedData->save($cachePath);
        }

        return $parsedData->toArray();
    }

    protected function _parse($filename)
    {
        $layout = simplexml_load_file($filename);

        if ($layout->getName() !== 'layout') {
            return array();
        }

        $result = new Config\Adapter\PhpArray();
        foreach ($layout->handle as $handle) {
            $name = (string) $handle['name'];
            if (empty($name)) {
                continue;
            }

            $handleNode = new Config\Node(array(), $result);
            $result->$name = $handleNode;

            if (isset($handle->update)) {
                $updateListNode = new Config\Node(array(), $handleNode);
                $handleNode->update = $updateListNode;

                foreach ($handle->update as $update) {
                    if (isset($update['name'])) {
                        $updateListNode->__set(uniqid(), (string) $update['name']);
                    }
                }
            }

            if (isset($handle->block)) {
                $this->_parseBlock($handle->block[0], $handleNode);
            }

            if (isset($handle->reference)) {
                $referenceListNode = new Config\Node(array(), $handleNode);
                $handleNode->reference = $referenceListNode;

                foreach ($handle->reference as $reference) {
                    $referenceName = (string) $reference['name'];
                    if (empty($referenceName)) {
                        continue;
                    }

                    $referenceNode = new Config\Node(array(), $referenceListNode);
                    $referenceListNode->$referenceName = $referenceNode;

                    $this->_parseBlockBody($reference, $referenceNode);
                }
            }
        }

        return $result;
    }

    protected function _parseBlock(\SimpleXMLElement $handle, Config\Node $blockNode)
    {
        foreach ($handle->attributes() as $attributeName => $attributeValue) {
            $blockNode->$attributeName = (string) $attributeValue;
        }

        $this->_parseBlockBody($handle, $blockNode);
    }

    protected function _parseBlockBody(\SimpleXMLElement $handle, Config\Node $blockNode)
    {
        if (isset($handle->block)) {
            $childrenNode = new Config\Node(array(), $blockNode);
            $blockNode->children = $childrenNode;

            foreach ($handle->block as $block) {
                $blockName = (string) $block['name'];
                if (empty($blockName)) {
                    continue;
                }

                $childNode = new Config\Node(array(), $childrenNode);
                $childrenNode->$blockName = $childNode;

                $this->_parseBlock($block, $childNode);
            }
        }

        if (isset($handle->action)) {
            $actionListNode = new Config\Node(array(), $blockNode);
            $blockNode->actions = $actionListNode;

            foreach ($handle->action as $action) {
                $methodName = (string) $action['method'];
                if (empty($methodName)) {
                    continue;
                }

                $actionNode = new Config\Node(array(
                    'method' => $methodName,
                    'params' => array()
                    ), $actionListNode);

                $actionListNode[] = $actionNode;

                if (isset($action->param)) {
                    $paramsNode = $actionNode->params;

                    foreach ($action->param as $param) {
                        $paramName = (string) $param['name'];
                        if (empty($paramName)) {
                            continue;
                        }

                        if (count($param)) {
                            if (isset($param->const) && isset($param->const['name']) && defined((string) $param->const['name'])) {
                                $paramsNode->$paramName = constant((string) $param->const['name']);
                            } else {
                                $paramArrayNode = new Config\Node(array(), $paramsNode);
                                $paramsNode->$paramName = $paramArrayNode;

                                $this->_parseArrayParam($param, $paramArrayNode);
                            }
                        } else {
                            $paramsNode->$paramName = (string) $param;
                        }
                    }
                }
            }
        }
    }

    protected function _parseArrayParam(\SimpleXMLElement $element, Config\Node $parentNode)
    {
        foreach ($element->children() as $child) {
            if (count($child)) {
                $childNode = new Config\Node(array(), $parentNode);
                $parentNode->{$child->getName()} = $childNode;

                $this->_parseArrayParam($child, $childNode);
                continue;
            }

            $parentNode->{$child->getName()} = (string) $child;
        }
    }
}
