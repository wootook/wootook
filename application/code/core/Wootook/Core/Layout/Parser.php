<?php

class Wootook_Core_Layout_Parser
{
    /**
     *
     * Enter description here ...
     * @param unknown_type $filename
     */
    public function parse($filename, $cachePath = null, $cacheLifetime = 86400)
    {
        if ($cachePath !== null && Wootook::fileExists($cachePath) && $cacheLifetime > 0 && (time() - filemtime($cachePath) > $cacheLifetime)) {
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

            if (isset($handle->update) && isset($handle->update[0]['name'])) {
                $handleNode->update = (string) $handle->update[0]['name'];
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

                if (isset($action->params)) {
                    $paramsNode = $actionNode->params;

                    foreach ($action->params[0]->param as $param) {
                        $paramName = (string) $param['name'];
                        if (empty($paramName)) {
                            continue;
                        }

                        $paramsNode->$paramName = (string) $param;
                    }
                }
            }
        }
    }
}
