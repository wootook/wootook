<?php
/**
 * Tis file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
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
 * documentation for further information about customizing XNova.
 *
 */

class Database
{
    static $dbHandle = NULL;
    static $config = NULL;
}

function doquery($query, $table, $fetch = false)
{
    if (!isset(Database::$config)) {
        $config = require dirname(dirname(__FILE__)) . '/config.php';
    }

    if(!isset(Database::$dbHandle))
    {
        Database::$dbHandle = mysql_connect(
            $config['global']['database']['options']['hostname'],
            $config['global']['database']['options']['username'],
            $config['global']['database']['options']['password'])
                or trigger_error(mysql_error() . "$query<br />" . PHP_EOL, E_USER_WARNING);

        mysql_select_db($config['global']['database']['options']['database'], Database::$dbHandle)
            or trigger_error(mysql_error()."$query<br />" . PHP_EOL, E_USER_WARNING);
    }
    $sql = str_replace("{{table}}", "{$config['global']['database']['table_prefix']}{$table}", $query);

    if (false === ($sqlQuery = mysql_query($sql, Database::$dbHandle))) {
        trigger_error(mysql_error() . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
    }

    if($fetch) {
        return mysql_fetch_array($sqlQuery);
    }else{
        return $sqlQuery;
    }
}
