<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 *
 * Enter description here ...
 * @author Greg
 *
 */
class Wootook_Core_ErrorProfiler
{
    private static $_singleton = null;

    protected $_errors = array();
    protected $_warnings = array();
    protected $_notices = array();
    protected $_otherErrors = array();
    protected $_exceptions = array();

    private static $_isTraceRegistered = false;

    private $_listen = true;

    private $_mute = true;

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
        if (!$this->_listen) {
            return;
        }

        $trace = debug_backtrace();

        switch ($errno) {
        case E_RECOVERABLE_ERROR:
        case E_USER_ERROR:
        case E_ERROR:
            $this->_errors[] = array(
                'time'    => explode(' ', microtime()),
                'code'    => $errno,
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline,
                'context' => $errcontext,
                'trace'   => $trace
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
                'context' => $errcontext,
                'trace'   => $trace
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
                'context' => $errcontext,
                'trace'   => $trace
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
                'context' => $errcontext,
                'trace'   => $trace
                );
                break;
        }

        return true;
    }

    public function addException($exception)
    {
        $this->_exceptions[] = $exception;
    }

    public function exceptionManager($exception)
    {
        if (!$this->_listen) {
            return;
        }
        $this->_exceptions[] = new Wootook_Core_Exception_Exception('Uncaught Exception: ' . $exception->getMessage(), null, $exception);
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
On: {$date} +{$microsec}µs
Type: {$code}
Message: {$error['message']}
File: {$error['file']}
Line: {$error['line']}

{$this->_renderBacktrace($error['trace'])}

ERROR_EOF;
    }

    protected function _renderBacktrace(Array $traces)
    {
        $output = '<pre>';
        foreach (array_slice($traces, 1) as $key => $trace) {
            if (preg_match('#^(include|require)(_once)?$#', $trace['function'])) {
                $output .= sprintf("#%d %s(%s) called at [%s:%s]\n",
                    $key, $trace['function'], $trace['args'][0], $trace['file'], $trace['line']);
            } else if (!isset($trace['file']) || !isset($trace['line'])) {
                if (isset($trace['class'])) {
                    $output .= sprintf("#%d Internal function <code>%s%s%s(%s)</code>\n",
                        $key, $trace['class'], $trace['type'], $trace['function'], implode(', ', $this->_formatArgs($trace['args'])));
                } else {
                    $output .= sprintf("#%d Internal function <code>%s(%s)</code>\n",
                        $key, $trace['function'], implode(', ', $this->_formatArgs($trace['args'])));
                }
            } else {
                if (isset($trace['class'])) {
                    $output .= sprintf("#%d <code>%s%s%s(%s)</code> called at [%s:%s]\n",
                        $key, $trace['class'], $trace['type'], $trace['function'], implode(', ', $this->_formatArgs($trace['args'])), $trace['file'], $trace['line']);
                } else {
                    $output .= sprintf("#%d <code>%s(%s)</code> called at [%s:%s]\n",
                        $key, $trace['function'], implode(', ', $this->_formatArgs($trace['args'])), $trace['file'], $trace['line']);
                }
            }
        }
        return $output . '</pre>';
    }

    private function _formatArgs($args)
    {
        $argumentList = array();
        foreach ($args as $argument) {
            if (is_object($argument)) {
                $argumentList[] = get_class($argument);
            } else if (is_array($argument)) {
                $keys = array_keys($argument);
                $values = $this->_formatArgs($argument);

                $length = count($values);
                $output = array();
                for ($index = 0; $index < $length; $index++) {
                    $output[] = sprintf('[%s] => %s', $keys[$index], $values[$index]);
                }

                $argumentList[] = '[' . implode(', ', $output) . ']';
            } else {
                $argumentList[] = var_export($argument, true);
            }
        }

        return $argumentList;
    }

    protected function _renderConfig(Wootook_Core_Config_Node $config, $level = 0)
    {
        $output = '<ul style="list-style:none;padding:5px;margin:5px 10px;border:1px solid gray">';
        foreach ($config as $key => $value) {
            $output .= sprintf('<li style="">', $level);
            $output .= sprintf('<span style="text-decoration:underline;padding-right:10px;font-family:monospace;color:darkred;font-weight:bold;font-size:12px;">%s</span>', $key);
            if ($value instanceof Wootook_Core_Config_Node) {
                if (count($value) > 0) {
                    $output .= $this->_renderConfig($value, $level + 1);
                } else {
                    $output .= '<em>Empty node</em>';
                }
            } else {
                $output .= '<code style="font-family:monospace;font-size:12px;">' . htmlspecialchars(var_export($value, true), ENT_QUOTES, 'UTF-8') . '</code>';
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function shutdownManager()
    {
        if ($this->_mute === true) {
            return;
        }

        $index = 0;
        echo '<div style="background:#FFF;border:5px solid #933;border-top-width:15px;border-radius:5px;color:#000;padding:0;margin:20px;text-align:left;margin:50px auto;width:800px;">';
        echo '<h1 style="margin:0;padding:0 10px;text-decoration:none;border-bottom:3px solid #933;">Debug profiler</h1>';
        echo '<div style="margin:0;overflow:auto;">';
        echo '</div>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="errors" class="error-profiler-link">Errors (' . count($this->_errors) . ')</h2>';
        echo '<div class="error-profiler errors">';
        foreach ($this->_errors as $error) {
            ++$index;
            echo '<h2 style="font-size:1.2em;text-decoration:none;margin:0 5px;">Message #' . $index . '</h2>';
            echo '<pre style="margin:0 5px;">';
            echo $this->_renderError($index, $error);
            echo '</pre>';
        }
        echo '</div>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="warnings" class="error-profiler-link">Warnings (' . count($this->_warnings) . ')</h2>';
        echo '<div class="error-profiler warnings">';
        foreach ($this->_warnings as $error) {
            ++$index;
            echo '<h2 style="font-size:1.2em;text-decoration:none;margin:10px 5px 0;">Message #' . $index . '</h2>';
            echo '<pre style="margin:0 5px;">';
            echo $this->_renderError($index, $error);
            echo '</pre>';
        }
        echo '</div>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="notices" class="error-profiler-link">Notices (' . count($this->_notices) . ')</h2>';
        echo '<div class="error-profiler notices">';
        foreach ($this->_notices as $error) {
            ++$index;
            echo '<h2 style="font-size:1.2em;text-decoration:none;margin:10px 5px 0;">Message #' . $index . '</h2>';
            echo '<pre style="margin:0 5px;">';
            echo $this->_renderError($index, $error);
            echo '</pre>';
        }
        echo '</div>';
        foreach ($this->_otherErrors as $errno => $errorList) {
            echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="other' . $errno . '" class="error-profiler-link">Other error errno#' . $errno . ' (' . count($errorList) . ')</h2>';
            echo '<div class="error-profiler other' . $errno . '">';
            foreach ($errorList as $error) {
                ++$index;
                echo '<h2 style="font-size:1.2em;text-decoration:none;margin:10px 5px 0;">Message #' . $index . '</h2>';
                echo '<pre style="margin:0 5px;">';
                echo $this->_renderError($index, $error);
                echo '</pre>';
            }
            echo '</div>';
        }
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="exceptions" class="error-profiler-link">Exceptions (' . count($this->_exceptions) . ')</h2>';
        echo '<div class="error-profiler exceptions">';
        foreach ($this->_exceptions as $exception) {
            ++$index;
            echo '<h2 style="font-size:1.2em;text-decoration:none;margin:10px 5px 0;">Message #' . $index . '</h2>';
            echo '<pre style="margin:0 5px;">';
            echo $exception->getMessage() . PHP_EOL;
            echo $exception->getTraceAsString() . PHP_EOL;
            echo '</pre>';

            $child = 0;
            $current = $exception;
            while (($current = $current->getPrevious()) !== null) {
                $child++;
                echo '<h3 style="font-size:1em;text-decoration:none;margin:10px 30px 0;">Message #' . $index . ', child level #' . $child . '</h3>';
                echo '<pre style="margin:0 30px;">';
                echo $current->getMessage() . PHP_EOL;
                echo $current->getTraceAsString() . PHP_EOL;
                echo PHP_EOL;
                echo '</pre>';
            }
            echo '</div>';
        }
        echo '</div>';
        echo <<<JS_EOF
<script>
(function(){
var sections = jQuery('.error-profiler');
sections.each(function(){
    var element = jQuery(this);
    element.hide();
    });

jQuery('.error-profiler-link').click(function(e){
    var rel = jQuery(this).attr('rel');
    sections.each(function(){
        var element = jQuery(this);
        if (element.hasClass(rel) && !element.hasClass('opened')) {
            element.slideDown(500, function(){element.addClass('opened');});
        } else {
            element.slideUp(500, function(){element.removeClass('opened');});
        }
        });
    });
})();
</script>
JS_EOF;
        echo '<h1 style="margin:0;padding:0 10px;text-decoration:none;border-bottom:3px solid #933;">Configuration profiler</h1>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="global" class="config-profiler-link">Global configuration</h2>';
        echo '<div class="config-profiler global">';
        $config = clone Wootook::getConfig();
        foreach ($config->getConfig('resource/database') as $node) {
            if ($node->params) {
                $node->params->reset();
            }
        }
        echo $this->_renderConfig(Wootook::getConfig(), 0);
        echo '</div>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="website" class="config-profiler-link">Website configuration</h2>';
        echo '<div class="config-profiler website">';
        echo $this->_renderConfig(Wootook::getWebsiteConfig());
        echo '</div>';
        echo '<h2 style="font-size:1.5em;text-decoration:none;margin:0;padding:15px 10px 5px;border-color:#000;border-style:solid;border-width:0 0 1px;background-color:#EEE" rel="game" class="config-profiler-link">Game configuration</h2>';
        echo '<div class="config-profiler game">';
        echo $this->_renderConfig(Wootook::getGameConfig());
        echo '</div>';
        echo '</div>';
        echo <<<JS_EOF
<script>
(function(){
var sections = jQuery('.config-profiler');
sections.each(function(){
    var element = jQuery(this);
    element.hide();
    });

jQuery('.config-profiler-link').click(function(e){
    var rel = jQuery(this).attr('rel');
    sections.each(function(){
        var element = jQuery(this);
        if (element.hasClass(rel) && !element.hasClass('opened')) {
            element.slideDown(500, function(){element.addClass('opened');});
        } else {
            element.slideUp(500, function(){element.removeClass('opened');});
        }
        });
    });
})();
</script>
JS_EOF;
    }

    public static function register()
    {
        set_error_handler(array(self::getSingleton(), 'errorManager'));
        set_exception_handler(array(self::getSingleton(), 'exceptionManager'));

        if (defined('DEBUG')) {
            self::getSingleton()->_mute = false;
        }

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

    public function sleep()
    {
        $this->_listen = false;
    }

    public function wakeup()
    {
        $this->_listen = true;
    }
}
