<?php

class Legacies_Core_View
    extends Legacies_Object
{
    protected $_template = null;

    public function __construct(Array $data = array())
    {
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

        return Legacies::translateArgs($message, $args);
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
        return $this->renderScript($this->getTemplate());
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
}