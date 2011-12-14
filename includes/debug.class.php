<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

/**
 * Debug class
 *
 * @todo Clean up source code
 */
class Nova_Core_Debug
{
    const CRITICAL      = 0x80;
    const ERROR         = 0x40;
    const WARNING       = 0x20;
    const INFO          = 0x10;
    const MESSAGE       = 0x08;
    const AUDIT_FAILED  = 0x04;
    const AUDIT_SUCCESS = 0x02;
    const DEBUG         = 0x01;

    const DEFAULT_LOGFILE = 'var/log/system.log';

    protected $_logLevelNames = array(
        self::CRITICAL      => 'CRITICAL',
        self::ERROR         => 'ERROR',
        self::WARNING       => 'WARNING',
        self::INFO          => 'INFO',
        self::MESSAGE       => 'MESSAGE',
        self::AUDIT_FAILED  => 'AUDIT_FAILED',
        self::AUDIT_SUCCESS => 'AUDIT_SUCCESS',
        self::DEBUG         => 'DEBUG'
        );

    /**
     * Clean logging method
     *
     * @param string $message
     * @param string $resource
     * @param int $level
     * @return unknown_type
     */
    public function log($message, $resource = self::DEFAULT_LOGFILE, $level = self::DEBUG)
    {
        if(!($fp = fopen($resource, 'a'))) {
            trigger_error('Unable to open logs.', E_USER_ERROR);
            trigger_error($message, E_USER_ERROR);
            return false;
        }
        fprintf($fp, '[%s] | %s - %s', $this->_logLevelNames[$level], date('r'), $message);
        fclose($fp);
        return true;
    }
}

/**
 * @deprecated
 * @todo Clean up source code
 */
class Debug
    extends Nova_Core_Debug
{
    protected $_logMessages = array();

	/**
     * @deprecated
     * @param string $message
     * @return void
     */
    function add($message)
    {
        $this->log($message);
        $this->_logMessages[] = $message;
    }

    /**
     * @deprecated
     * @return void
     */
    function echo_log()
    {
        $messages = implode(PHP_EOL, $this->_logMessages);
        echo  <<<EOF
<dl class="k">
  <dt>
    <a href="admin/settings.php">Debug Log</a>:
  </dt>
  <dd>
    <pre><code>{$messages}</code></pre>
  </dd>
</dl>
EOF;
        die();
    }

    /**
     * @deprecated
     * @todo Clean up source code
     * @param $message
     * @param $title
     * @return unknown_type
     */
    public function error($message, $title)
    {
        if (defined('DEBUG')) {
            echo "<h2>$title</h2><br><font color=red>$message</font><br><hr>";
            echo  "<table>".$this->log."</table>";
        }

        $user = Legacies_Empire_Model_User::getSingleton();

        $db = Legacies_Database::getSingleton();
        $config = include ROOT_PATH . 'config.' . PHPEXT;
        if(!$link) die('La base de donnee n est pas disponible pour le moment, desole pour la gene occasionnee...');
        $query = "INSERT INTO {$db->getTable('errors')} SET
            `error_sender` = {$user->getId()} ,
            `error_time` = {$db->quote(time())},
            `error_type` = {$db->quote($title)},
            `error_text` = {$db->quote($message)}";

        $db->query($query);
        $id = $db->lastInsertId($db->getTable('errors'));
        if (!function_exists('message')) {
            echo "Erreur, merci de contacter l'admin. Erreur n�: <b>".$id."</b>";
        } else {
            message("Erreur, merci de contacter l'admin. Erreur n�: <b>".$id."</b>", "Erreur");
        }
    }
}
