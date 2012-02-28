<?php

class Wootook_Core_Model_Translator
{
    protected $_translations = array();

    public function __construct($path, $locale)
    {
        $fileList = glob($path . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . '*.csv');
        foreach ($fileList as $file) {
            $fp = fopen($file, 'r');
            while (!feof($fp)) {
                $line = fgetcsv($fp);
                if (count($line) >= 2) {
                    $this->_translations[$line[0]] = $line[1];
                }
            }
        }
    }

    public function translate($message, $_ = null)
    {
        $args = func_get_args();
        array_shift($args);

        return $this->translateArgs($message, $args);
    }

    public function translateArgs($message, Array $args = array())
    {
        if (isset($this->_translations[$message])) {
            $message = $this->_translations[$message];
        }

        return vsprintf($message, $args);
    }
}