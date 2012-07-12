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

namespace Wootook\Core\Database\Sql\Dml\Section;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Placeholder;

trait Column
{
    public function column($column = '*', $table = null)
    {
        $parts = $this->getPart(Dml\Section\ColumnAware::COLUMNS);
        if (is_array($column)) {
            foreach ($column as $alias => $field) {
                if (is_int($alias)) {
                    $parts[] = array(
                        'table' => $table,
                        'alias' => null,
                        'field' => $field
                    );
                } else {
                    $parts[] = array(
                        'table' => $table,
                        'alias' => $alias,
                        'field' => $field
                    );
                }
            }
        } else {
            if ($column instanceof Placeholder\Placeholder) {
                $this->_placeholders[] = $column;
            }

            $parts[] = array(
                'table' => $table,
                'alias' => null,
                'field' => $column
            );
        }
        $this->setPart(Dml\Section\ColumnAware::COLUMNS, $parts);

        return $this;
    }
}
