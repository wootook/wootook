<?php

class Wootook_Core_ErrorProfiler
{
    private static $_singleton = null;

    protected $_errors = array();
    protected $_warnings = array();
    protected $_notices = array();
    protected $_otherErrors = array();
    protected $_exceptions = array();

    private static $_isTraceRegistered = false;

    private static $_listen = true;

    private $_mute = false;

    /**
     *
     * @return Wootook_Core_ErrorProfiler
     */
    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            self::$_singleton = new self;
        }
        return self::$_singleton;
    }

    public function errorManager($errno, $errstr, $errfile = null, $errline = null, Array $errcontext = array())
    {
        if (!self::$_listen) {
            return;
        }

        switch ($errno) {
        case E_USER_ERROR:
        case E_ERROR:
            $this->_errors[] = array(
                'time'    => explode(' ', microtime()),
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'context' => $errcontext
                );
                break;

        case E_USER_WARNING:
        case E_WARNING:
            $this->_warnings[] = array(
                'time'    => explode(' ', microtime()),
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'context' => $errcontext
                );
                break;

        case E_USER_NOTICE:
        case E_NOTICE:
            $this->_notices[] = array(
                'time'    => explode(' ', microtime()),
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'context' => $errcontext
                );
                break;

        default:
            if (!isset($this->_otherErrors[$errno])) {
                $this->_otherErrors[$errno] = array();
            }

            $this->_otherErrors[$errno][] = array(
                'time'    => explode(' ', microtime()),
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'context' => $errcontext
                );
                break;
        }

        return true;
    }

    public function exceptionManager($exception)
    {
        if (!self::$_listen) {
            return;
        }
        $this->_exceptions[] = $exception;
    }

    protected function _renderError($id, $error)
    {
        $types = array(
            0x0001 => 'E_ERROR',
            0x0002 => 'E_WARNING',
            0x0004 => 'E_PARSE',
            0x0008 => 'E_NOTICE',
            0x0010 => 'E_CORE_ERROR',
            0x0020 => 'E_CORE_WARNING',
            0x0040 => 'E_COMPILE_ERROR',
            0x0080 => 'E_COMPILE_WARNING',
            0x0100 => 'E_USER_ERROR',
            0x0200 => 'E_USER_WARNING',
            0x0400 => 'E_USER_NOTICE',
            0x0800 => 'E_STRICT',
            0x1000 => 'E_RECOVERABLE_ERROR',
            0x2000 => 'E_DEPRECATED',
            0x4000 => 'E_USER_DEPRECATED',
            );

        if (isset($error['code']) && isset($types[$error['code']])) {
            $code = $types[$error['code']];
        } else {
            $code = "Unknown ({$error['code']})";
        }

        $date = date('Y-m-d H:i:s', (int) $error['time'][1]);
        $microsec = (int) ($error['time'][0] * 1000000);

        return <<<ERROR_EOF
Message #{$id}
On: {$date} +{$microsec}µs
Type: {$code}
Message: {$error['message']}
File: {$error['file']}
Line: {$error['line']}
\n
ERROR_EOF;
    }

    public function shutdownManager()
    {
        if ($this->_mute === true) {
            return;
        }

        $index = 0;
        echo '<div style="background:#FFF;border:1px solid #F00;color:#000;padding:10px;margin:20px;text-align:left;">';
        echo '<h1>Debug profiler</h1>';
        if (count($this->_errors) <= 0 && count($this->_warnings) <= 0 &&
            count($this->_notices) <= 0 && count($this->_otherErrors) <= 0 &&
            count($this->_exceptions) <= 0) {
            echo '<p>Profiler was empty.</p>';
        } else {
            echo '<pre>';
            foreach ($this->_errors as $error) {
                echo $this->_renderError(++$index, $error);
            }
            foreach ($this->_warnings as $error) {
                echo $this->_renderError(++$index, $error);
            }
            foreach ($this->_notices as $error) {
                echo $this->_renderError(++$index, $error);
            }
            foreach ($this->_otherErrors as $errorList) {
                foreach ($errorList as $error) {
                    echo $this->_renderError(++$index, $error);
                }
            }
            foreach ($this->_exceptions as $exception) {
                $index++;
                echo "Message #{$index}". PHP_EOL;
                echo $exception->getMessage() . PHP_EOL;
                echo $exception->getTraceAsString();
            }
            echo '</pre>';
        }
        echo '</div>';
    }

    public static function register()
    {
        set_error_handler(array(self::getSingleton(), 'errorManager'));
        set_exception_handler(array(self::getSingleton(), 'exceptionManager'));

        self::getSingleton()->_mute = false;

        if (self::$_isTraceRegistered === false && defined('DEBUG')) {
            self::$_isTraceRegistered = true;
            register_shutdown_function(array(self::getSingleton(), 'shutdownManager'));
        }
    }

    public static function unregister($force = false)
    {
        restore_error_handler();
        restore_exception_handler();

        self::getSingleton()->_mute = true;

        if ((self::$_isTraceRegistered === false && defined('DEBUG')) || $force) {
            self::$_isTraceRegistered = true;
            register_shutdown_function(array(self::getSingleton(), 'shutdownManager'));
        }
    }

    public static function sleep()
    {
        self::$_listen = false;
    }

    public static function wakeup()
    {
        self::$_listen = true;
    }
}
