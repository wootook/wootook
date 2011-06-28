<?php
/**
 * This file is part of XNova:Legacies
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

/**
 *
 * Enter description here ...
 * @deprecated
 * @param string $query
 * @param string $table
 * @param bool $fetch
 */
function doquery($query, $table, $fetch = false)
{
    /**
     * @var Legacies_Database $database
     */
    $database = Legacies_Database::getSingleton();

    $sql = str_replace("{{table}}", $database->getTable($table), $query);

    /**
     * @var PDOStatement $statement
     */
    try {
        $statement = $database->prepare($sql);

        $statement->execute(array());
    } catch (PDOException $e) {
        trigger_error($e->getMessage() . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
    }

    if ($fetch) {
        return $statement->fetch(PDO::FETCH_BOTH);
    } else {
        return $statement;
    }
}
