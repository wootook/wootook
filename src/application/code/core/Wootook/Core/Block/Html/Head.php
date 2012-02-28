<?php

class Wootook_Core_Block_Html_Head
    extends Wootook_Core_Block_Template
{
    const TYPE_GLOBAL_CSS = 'global_css';
    const TYPE_GLOBAL_JS  = 'global_js';

    const TYPE_SKIN_CSS   = 'skin_css';
    const TYPE_SKIN_JS    = 'skin_js';

    const TYPE_INLINE_CSS = 'inline_css';
    const TYPE_INLINE_JS  = 'inline_js';

    const TITLE_CONCAT_BEFORE = 'BEFORE';
    const TITLE_CONCAT_AFTER  = 'AFTER';
    const TITLE_OVERWRITE     = 'OVERWRITE';

    protected $_items = array();

    protected $_title = null;
    protected $_titleDefaultConcat = self::TITLE_CONCAT_AFTER;
    protected $_titleSeparator = ' ¤ ';

    protected $_titleConcatTypes = array(
        self::TITLE_CONCAT_AFTER,
        self::TITLE_CONCAT_BEFORE,
        self::TITLE_OVERWRITE
        );

    public function setTitleSeparator($separator)
    {
        $this->_titleSeparator = $separator;

        return $this;
    }

    public function getTitleSeparator()
    {
        return $this->_titleSeparator;
    }

    public function setTitleDefaultConcat($defaultConcat)
    {
        if (in_array($concatType, $this->_titleConcatTypes)) {
            $this->_titleDefaultConcat = $defaultConcat;
        }

        return $this;
    }

    public function getTitleDefaultConcat()
    {
        return $this->_titleDefaultConcat;
    }

    public function setTitle($title, $concatType = self::TITLE_CONCAT_AFTER)
    {
        if (!in_array($concatType, $this->_titleConcatTypes)) {
            $concatType = $this->getTitleDefaultConcat();
        }

        if ($concatType == self::TITLE_OVERWRITE || $this->_title === null) {
            $this->_title = $title;
        } else if ($concatType == self::TITLE_CONCAT_BEFORE) {
            $this->_title = $title . $this->getTitleSeparator() . $this->_title;
        } else if ($concatType == self::TITLE_CONCAT_AFTER) {
            $this->_title .= $this->getTitleSeparator() . $title;
        }

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function addItem($type, Array $options = array())
    {
        if (!isset($this->_items[$type])) {
            $this->_items[$type] = array();
        }
        $this->_items[$type][] = $options;

        return $this;
    }

    public function addJs($script, $type = 'text/javascript', Array $options = array(), $theme = null, $package = null)
    {
        $options['type'] = $type;
        $options['path'] = $script;
        $options['theme'] = $theme;
        $options['package'] = $package;

        $this->addItem(self::TYPE_GLOBAL_JS, $options);

        return $this;
    }

    public function addCss($stylesheet, $type = 'text/css', $condition = null, Array $options = array(), $theme = null, $package = null)
    {
        $options['type'] = $type;
        $options['path'] = $stylesheet;
        $options['condition'] = $condition;
        $options['theme'] = $theme;
        $options['package'] = $package;

        $this->addItem(self::TYPE_GLOBAL_CSS, $options);

        return $this;
    }

    public function addSkinJs($script, $type = 'text/javascript', Array $options = array(), $theme = null, $package = null)
    {
        $options['type'] = $type;
        $options['path'] = $script;
        $options['theme'] = $theme;
        $options['package'] = $package;

        $this->addItem(self::TYPE_SKIN_JS, $options);

        return $this;
    }

    public function addSkinCss($stylesheet, $type = 'text/css', $condition = null, Array $options = array(), $theme = null, $package = null)
    {
        $options['type'] = $type;
        $options['path'] = $stylesheet;
        $options['condition'] = $condition;
        $options['theme'] = $theme;
        $options['package'] = $package;

        $this->addItem(self::TYPE_SKIN_CSS, $options);

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
        $options['content'] = $content;

        $this->addItem(self::TYPE_INLINE_CSS, $options);

        return $this;
    }

    public function getCss()
    {
        if (isset($this->_items[self::TYPE_GLOBAL_CSS])) {
            return $this->_items[self::TYPE_GLOBAL_CSS];
        }
        return array();
    }

    public function getSkinCss()
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
            $url = $this->getStaticUrl($css['path'], array());
            $render .=<<<HTML_EOF
<link rel="stylesheet" type="{$css['type']}" src="{$url}" media="{$css['media']}" />
HTML_EOF;
        }
    }

    public function renderSkinCss()
    {
        $render = '';
        foreach ($this->getSkinCss() as $css) {
            if (!isset($css['media'])) {
                $css['media'] = 'all';
            }
            $url = $this->getSkinUrl($css['path'], array(), $css['theme'], $css['package']);
            $render .=<<<HTML_EOF
<link rel="stylesheet" type="{$css['type']}" src="{$url}" media="{$css['media']}" />
HTML_EOF;
        }
    }

    public function renderInlineCss()
    {
        foreach ($this->getInlineCss() as $css) {
            if (!isset($css['media'])) {
                $css['media'] = 'all';
            }
            $render .=<<<HTML_EOF
<style type="{$css['type']}">/*<![CDATA[*/{$css['content']}/*]]>*/</style>
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
            $url = $this->getStaticUrl($js['path']);
            $render .=<<<HTML_EOF
<script type="{$js['type']}" src="{$url}"></script>
HTML_EOF;
        }
        return $render;
    }

    public function renderSkinJs()
    {
        $render = '';
        foreach ($this->getJs() as $js) {
            if (!isset($js['type'])) {
                $js['type'] = 'text/javascript';
            }
            $url = $this->getSkinUrl($js['path'], array(), $js['theme'], $js['package']);
            $render .=<<<HTML_EOF
<script type="{$js['type']}" src="{$url}"></script>
HTML_EOF;
        }
        return $render;
    }

    public function renderInlineJs()
    {
        foreach ($this->getInlineJs() as $js) {
            $render .=<<<HTML_EOF
<script type="{$js['type']}">/*<![CDATA[*/{$js['content']}/*]]>*/</script>
HTML_EOF;
        }
        return $render;
    }
}