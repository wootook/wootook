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

namespace Wootook\Core\Database\Sql\Dml\Section\Renderer;

use Wootook\Core\Database\Sql\Dml,
    Wootook\Core\Database\Sql\Placeholder;

trait Set
{
    public function renderSet(Dml\Dml $query)
    {
        $fields = array();
        $parts = $query->getPart(Dml\Section\SetAware::SET);
        if (is_array($parts)) {
            foreach ($parts as $field) {
                if ($field['value'] instanceof Placeholder\Placeholder) {
                    $fields[] = "{$query->quoteIdentifier($field['field'])}={$field['value']->toString()}";
                } else if ($field['value'] === null) {
                    $fields[] = "{$query->quoteIdentifier($field['field'])}=NULL";
                } else {
                    $fields[] = "{$query->quoteIdentifier($field['field'])}={$query->quote($field['value'])}";
                }
            }
        }

        if (!empty($fields)) {
            return ' SET ' . implode(', ', $fields);
        }

        return '';
    }
}
