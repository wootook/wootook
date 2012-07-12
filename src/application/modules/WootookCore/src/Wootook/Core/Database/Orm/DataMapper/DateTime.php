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

namespace Wootook\Core\Database\Orm\DataMapper;

use Wootook\Core;

class DateTime
    extends FieldMapper
{
    const DATE_FORMAT_MYSQL = 'Y-m-d G:i:s';

    protected $_format = self::DATE_FORMAT_MYSQL;

    public function setFormat($format = null)
    {
        if ($format === null) {
            $this->_format = self::DATE_FORMAT_MYSQL;
        } else {
            $this->_format = $format;
        }
        return $this;
    }

    public function getFormat()
    {
        return $this->_format;
    }

    public function encode($value)
    {
        if (!$value instanceof Core\DateTime) {
            $value = new Core\DateTime($value);
        }

        return $value->toString($this->getFormat());
    }

    public function decode($value)
    {
        return new Core\DateTime($value, $this->getFormat());
    }
}
