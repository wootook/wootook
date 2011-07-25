<?php

class Legacies_Core_View
    extends Legacies_Object
{
    protected $_template = null;
    protected $_partials = array();
    protected $_layout = null;

    public function __construct(Array $data = array())
    {
        if (isset($data['template'])) {
            $this->_template = $data['template'];
            unset($data['template']);
        }

        parent::__construct($data);
    }

    protected function _prepareRender()
    {
        return $this;
    }

    public function renderNumber($number)
    {
        return Math::render($number);
    }

    protected function escape($unescaped)
    {
        return htmlspecialchars($unescaped, ENT_QUOTES, 'UTF-8');
    }

    public function __($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return Legacies::translate(Legacies::getLocale(), $message, $args);
    }

    public function translate($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return Legacies::translate(Legacies::getLocale(), $message, $args);
    }

    public function renderScript($file)
    {
        $this->_prepareRender();

        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'design' . DIRECTORY_SEPARATOR . 'scripts';

        ob_start();
        include $path . DIRECTORY_SEPARATOR . $file;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    public function render()
    {
        $template = $this->getTemplate();
        if (empty($template)) {
            return null;
        }
        return $this->renderScript($template);
    }

    public function setTemplate($template)
    {
        $this->_template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->_template;
    }

    public function getUrl($uri, Array $params = array())
    {
        // TODO: base path integration
        $serializedParams = array();
        foreach ($params as $paramKey => $paramValue) {
            if ($paramValue) {
                $serializedParams[] = "{$paramKey}={$paramValue}";
            }
        }

        if (count($serializedParams) > 0) {
            return $uri . '?' . implode('&', $serializedParams);
        }
        return $uri;
    }

    public function getSkinUrl($uri)
    {
        static $baseUrl = null;
        if ($baseUrl === null) {
            $user = Legacies_Empire_Model_User::getSingleton();
            if ($user !== null && $user->getId() && ($baseUrl = $user->getSkinPath()) == '') {
                $baseUrl = DEFAULT_SKINPATH;
            }
        }

        return $baseUrl . $uri;
    }

    public function setPartial($name, $content)
    {
        if (!is_string($content) && !$content instanceof self) {
            return $this;
        }

        $this->_partials[$name] = $content;

        return $this;
    }

    public function getPartial($name)
    {
        return $this->_partials[$name];
    }

    public function unsetPartial($name)
    {
        if (isset($this->_partials[$name])) {
            unset($this->_partials[$name]);
        }

        return $this;
    }

    public function hasPartial($name)
    {
        return isset($this->_partials[$name]) && (is_string($this->_partials[$name]) || $this->_partials[$name] instanceof self);
    }

    public function __set($name, $content)
    {
        return $this->setPartial($name, $content);
    }

    public function __get($name)
    {
        $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
        $name[0] = strtolower($name[0]);
        return $this->getPartial($name);
    }

    public function __unset($name)
    {
        return $this->unsetPartial($name);
    }

    public function __isset($name)
    {
        return $this->hasPartial($name);
    }

    public function setLayout($layout)
    {
        $this->_layout = $layout;

        return $this;
    }

    public function getLayout()
    {
        return $this->_layout;
    }

    public function prepareLayout()
    {
    }

    public function beforeToHtml()
    {
    }
}