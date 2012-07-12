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

namespace Wootook\Core\Model;

class Translator
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
