<?php

class Wootook_Core_Model_Layout_Parser
{
    /**
     *
     * Enter description here ...
     * @param unknown_type $filename
     */
    public function parse($filename, $cachePath = null, $cacheLifetime = 86400)
    {
        if ($cachePath !== null && Wootook::fileExists($cachePath) && $cacheLifetime > 0 && (time() - filemtime($cachePath) <= $cacheLifetime)) {
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

        $result = new Wootook_Core_Config_Adapter_Array();
        foreach ($layout->handle as $handle) {
            $name = (string) $handle['name'];
            if (empty($name)) {
                continue;
            }

            $handleNode = new Wootook_Core_Config_Node(array(), $result);
            $result->$name = $handleNode;

            if (isset($handle->update)) {
                $updateListNode = new Wootook_Core_Config_Node(array(), $handleNode);
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
                $referenceListNode = new Wootook_Core_Config_Node(array(), $handleNode);
                $handleNode->reference = $referenceListNode;

                foreach ($handle->reference as $reference) {
                    $referenceName = (string) $reference['name'];
                    if (empty($referenceName)) {
                        continue;
                    }

                    $referenceNode = new Wootook_Core_Config_Node(array(), $referenceListNode);
                    $referenceListNode->$referenceName = $referenceNode;

                    $this->_parseBlockBody($reference, $referenceNode);
                }
            }
        }

        return $result;
    }

    protected function _parseBlock(SimpleXMLElement $handle, Wootook_Core_Config_Node $blockNode)
    {
        foreach ($handle->attributes() as $attributeName => $attributeValue) {
            $blockNode->$attributeName = (string) $attributeValue;
        }

        $this->_parseBlockBody($handle, $blockNode);
    }

    protected function _parseBlockBody(SimpleXMLElement $handle, Wootook_Core_Config_Node $blockNode)
    {
        if (isset($handle->block)) {
            $childrenNode = new Wootook_Core_Config_Node(array(), $blockNode);
            $blockNode->children = $childrenNode;

            foreach ($handle->block as $block) {
                $blockName = (string) $block['name'];
                if (empty($blockName)) {
                    continue;
                }

                $childNode = new Wootook_Core_Config_Node(array(), $childrenNode);
                $childrenNode->$blockName = $childNode;

                $this->_parseBlock($block, $childNode);
            }
        }

        if (isset($handle->action)) {
            $actionListNode = new Wootook_Core_Config_Node(array(), $blockNode);
            $blockNode->actions = $actionListNode;

            foreach ($handle->action as $action) {
                $methodName = (string) $action['method'];
                if (empty($methodName)) {
                    continue;
                }

                $actionNode = new Wootook_Core_Config_Node(array(
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
                                $paramArrayNode = new Wootook_Core_Config_Node(array(), $paramsNode);
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

    protected function _parseArrayParam(SimpleXMLElement $element, Wootook_Core_Config_Node $parentNode)
    {
        foreach ($element->children() as $child) {
            if (count($child)) {
                $childNode = new Wootook_Core_Config_Node(array(), $parentNode);
                $parentNode->{$child->getName()} = $childNode;

                $this->_parseArrayParam($child, $childNode);
                continue;
            }

            $parentNode->{$child->getName()} = (string) $child;
        }
    }
}
