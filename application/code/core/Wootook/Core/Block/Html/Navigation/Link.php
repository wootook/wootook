<?php

class Wootook_Core_Block_Html_Navigation_Link
    extends Wootook_Core_Block_Template
{
    protected $_label = '';
    protected $_title = '';
    protected $_uri = '';
    protected $_params = array();
    protected $_classes = array('link');

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

    public function setUrl($uri, $params = array())
    {
        $this->_uri = $uri;
        $this->_params = $params;

        return $this;
    }

    public function getLinkUrl($moreParams = array())
    {
        $params = array_merge($this->_params, $moreParams);
        return $this->getUrl($this->_uri, $params);
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

    public function renderClasses($moreClasses)
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
                if (isset($data['url']['params'])) {
                    $this->setUrl($data['url']['uri'], $data['url']['params']);
                } else {
                    $this->setUrl($data['url']['uri']);
                }
            } else {
                $this->setUrl($data['url']);
            }
            unset($data['url']);
        }

        if (isset($data['classes'])) {
            $classes = (array) $data['classes'];
            unset($data['classes']);

            foreach ($classes as $class) {
                $this->set($class);
            }
        }

        parent::__construct($data);

        return $this;
    }
}