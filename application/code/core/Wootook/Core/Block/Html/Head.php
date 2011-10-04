<?php

class Wootook_Core_Block_Html_Head
    extends Wootook_Core_Block_Template
{
    const TYPE_GLOBAL_JS  = 'global_js';

    const TYPE_SKIN_CSS   = 'skin_css';
    const TYPE_SKIN_JS    = 'skin_js';

    const TYPE_INLINE_CSS = 'inline_css';
    const TYPE_INLINE_JS  = 'inline_js';

    protected $_items = array();

    public function addItem($type, Array $options = array())
    {
        if (!isset($this->_items[$type])) {
            $this->_items[$type] = array();
        }
        $this->_items[$type][] = $options;

        return $this;
    }

    public function addJs($script, $type = 'text/javascript', Array $options = array())
    {
        $options['type'] = $type;
        $options['path'] = $script;

        $this->addItem(self::TYPE_GLOBAL_JS, $options);

        return $this;
    }

    public function addCss($stylesheet, $type = 'text/css', $condition = null, Array $options = array())
    {
        $options['type'] = $type;
        $options['path'] = $stylesheet;
        $options['condition'] = $condition;

        $this->addItem(self::TYPE_SKIN_CSS, $options);

        return $this;
    }

    public function addSkinJs($script, $type = 'text/javascript', Array $options = array())
    {
        $options['type'] = $type;
        $options['path'] = $script;

        $this->addItem(self::TYPE_SKIN_JS, $options);

        return $this;
    }

    public function addInlineJs($content, $type = 'text/javascript', Array $options = array())
    {
        $options['type'] = $type;
        $options['content'] = $content;

        $this->addItem(self::TYPE_INLINE_JS, $options);

        return $this;
    }

    public function addInlineCss($content, $type = 'text/css', Array $options = array())
    {
        $options['type'] = $type;
        $options['path'] = $path;
        $options['content'] = $content;

        $this->addItem(self::TYPE_INLINE_CSS, $options);

        return $this;
    }

    public function getCss()
    {
        if (isset($this->_items[self::TYPE_SKIN_CSS])) {
            return $this->_items[self::TYPE_SKIN_CSS];
        }
        return array();
    }

    public function getInlineCss()
    {
        if (isset($this->_items[self::TYPE_INLINE_CSS])) {
            return $this->_items[self::TYPE_INLINE_CSS];
        }
        return array();
    }

    public function getJs()
    {
        if (isset($this->_items[self::TYPE_GLOBAL_JS])) {
            return $this->_items[self::TYPE_GLOBAL_JS];
        }
        return array();
    }

    public function getSkinJs()
    {
        if (isset($this->_items[self::TYPE_SKIN_JS])) {
            return $this->_items[self::TYPE_SKIN_JS];
        }
        return array();
    }

    public function getInlineJs()
    {
        if (isset($this->_items[self::TYPE_INLINE_JS])) {
            return $this->_items[self::TYPE_INLINE_JS];
        }
        return array();
    }

    public function renderCss()
    {
        $render = '';
        foreach ($this->getCss() as $css) {
            if (!isset($css['media'])) {
                $css['media'] = 'all';
            }
            $render .=<<<HTML_EOF
<link rel="stylesheet" type="{$css['type']}" src="{$css['path']}" media="{$css['media']}" />
HTML_EOF;
        }
        return $render;
    }

    public function renderJs()
    {
        $render = '';
        foreach ($this->getJs() as $js) {
            if (!isset($js['type'])) {
                $js['type'] = 'text/javascript';
            }
            $render .=<<<HTML_EOF
<script type="{$js['type']}" src="{$js['path']}"></script>
HTML_EOF;
        }
        return $render;
    }
}