<?php

class Wootook_Core_Block_Html_Navigation_Link
    extends Wootook_Core_Block_Template
{
    protected $_label = '';
    protected $_title = '';
    protected $_uri = null;
    protected $_url = null;
    protected $_params = array();
    protected $_classes = array('link');
    protected $_attributes = array();

    public function getTemplate()
    {
        if ($this->_template !== null) {
            return $this->_template;
        }
        return 'page/html/navigation/link.phtml';
    }

    public function setLabel($label)
    {
        $this->_label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->_label;
    }

    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setStaticUrl($uri, $params = array())
    {
        if ($uri === null) {
            return $this;
        }

        $this->_uri = $uri;
        $this->_params = $params;
        $this->_url = $this->getStaticUrl($uri, $params);

        return $this;
    }

    public function setUrl($uri, $params = array())
    {
        if ($uri === null) {
            return $this;
        }

        $this->_uri = $uri;
        $this->_params = $params;
        $this->_url = $this->getUrl($uri, $params);

        return $this;
    }

    public function setExternalUrl($url)
    {
        $this->_url = $url;
        $this->_uri = null;
        $this->_params = array();

        return $this;
    }

    public function getLinkUrl($moreParams = array())
    {
        if (empty($moreParams) || $this->_uri === null) {
            return $this->_url;
        }
        $params = array_merge($this->_params, $moreParams);
        return $this->getStaticUrl($this->_uri, $params);
    }

    public function setAttribute($attributeName, $attributeValue)
    {
        $this->_attributes[$attributeName] = $attributeValue;

        return $this;
    }

    public function getAttribute($attributeName)
    {
        if ($this->hasAttribute($attributeName)) {
            return $this->_attributes[$attributeName];
        }
        return null;
    }

    public function hasAttribute($attributeName)
    {
        if (isset($this->_attributes[$attributeName])) {
            return true;
        }
        return false;
    }

    public function unsetAttribute($attributeName)
    {
        if ($this->hasAttribute($attributeName)) {
            unset($this->_attributes[$attributeName]);
        }
        return $this;
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function addClass($class)
    {
        $this->_classes[] = $class;

        return $this;
    }

    public function clearClasses()
    {
        $this->_classes = array();

        return $this;
    }

    public function renderClasses(Array $moreClasses = array())
    {
        $classes = array_merge($this->_classes, $moreClasses);
        return implode(' ', $classes);
    }

    public function __construct(Array $data = array())
    {
        if (isset($data['label'])) {
            $this->setLabel($data['label']);
            unset($data['label']);
        }

        if (isset($data['title'])) {
            $this->setTitle($data['title']);
            unset($data['title']);
        }

        if (isset($data['url'])) {
            if (is_array($data['url']) && isset($data['url']['uri'])) {
                if (isset($data['url']['static']) && $data['url']['static']) {
                    if (isset($data['url']['params'])) {
                        $this->setStaticUrl($data['url']['uri'], $data['url']['params']);
                    } else {
                        $this->setStaticUrl($data['url']['uri']);
                    }
                } else {
                    if (isset($data['url']['params'])) {
                        $this->setUrl($data['url']['uri'], $data['url']['params']);
                    } else {
                        $this->setUrl($data['url']['uri']);
                    }
                }
            } else {
                $this->setExternalUrl($data['url']);
            }
            unset($data['url']);
        }

        if (isset($data['classes'])) {
            $classes = (array) $data['classes'];
            unset($data['classes']);

            foreach ($classes as $class) {
                $this->addClass(trim($class));
            }
        }

        if (isset($data['attributes'])) {
            $attributes = (array) $data['attributes'];
            unset($data['attributes']);

            foreach ($attributes as $attributeName => $attributeValue) {
                $this->setAttribute($attributeName, $attributeValue);
            }
        }

        parent::__construct($data);

        return $this;
    }
}
