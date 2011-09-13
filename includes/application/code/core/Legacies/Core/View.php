<?php

class Legacies_Core_View
    extends Legacies_Object
{
    protected $_template = null;
    protected $_partials = array();
    protected $_layout = null;
    protected $_nameInLayout = null;

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

    public function renderTime($time, $unique = false)
    {
        if ($time >= 10) {
            $seconds = $time % 60;
            $minutes = (int) (($time - $seconds) / 60) % 60;
            $hours = (int) ((($time - $seconds) / 60) - $minutes) / 60;

            if ($hours > 24) {
                $dayHours = (int) $hours % 24;
                $days = (int) ($hours - $dayHours) / 24;

                return $this->__('%1$d day(s) and %2$d hour(s)', $days, $dayHours);
            } else if ($hours > 0) {
                return $this->__('%1$d hour(s), %2$d minute(s) and %3$d second(s)', $hours, $minutes, $seconds);
            } else if ($minutes > 0) {
                return $this->__('%1$d minute(s) and %2$d second(s)', $minutes, $seconds);
            } else {
                return $this->__('%1$d second(s)', $seconds);
            }
        } else if (!$unique && $time > 0) {
            return $this->__('%1$d per minute', 60 / $time);
        } else {
            return $this->__('instantaneous');
        }
    }

    protected function escape($unescaped)
    {
        return htmlspecialchars($unescaped, ENT_QUOTES, 'UTF-8');
    }

    public function __($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return Legacies::translate(Legacies::getDefaultLocale(), $message, $args);
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
            if ($user !== null && $user->getId()) {
                $baseUrl = $user->getSkinPath();
            }
            if ($baseUrl == '') {
                $baseUrl = DEFAULT_SKINPATH;
            }
        }

        return $this->getUrl($baseUrl . $uri);
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

    public function getAllPartials()
    {
        return $this->_partials;
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

    /**
     *
     * @return Legacies_Core_Layout
     */
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

    public function setNameInLayout($name)
    {
        $this->_nameInLayout = $name;

        return $this;
    }

    public function getNameInLayout()
    {
        return $this->_nameInLayout;
    }
}