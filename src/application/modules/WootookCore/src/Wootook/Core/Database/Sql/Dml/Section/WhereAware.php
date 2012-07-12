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

interface WhereAware
{
    const WHERE   = 'WHERE';

    const OPERATOR_NOT             = 'NOT';
    const OPERATOR_AND             = 'AND';
    const OPERATOR_OR              = 'OR';
    const OPERATOR_XOR             = 'XOR';
    const OPERATOR_NAND            = 'NAND';
    const OPERATOR_NOR             = 'NOR';
    const OPERATOR_NXOR            = 'NXOR';
    const OPERATOR_EQUALS          = 'EQ';
    const OPERATOR_NOT_EQUALS      = 'NEQ';
    const OPERATOR_LOWER           = 'LT';
    const OPERATOR_GREATER         = 'GT';
    const OPERATOR_LOWER_EQUALS    = 'LTEQ';
    const OPERATOR_GREATER_EQUALS  = 'GTEQ';
    const OPERATOR_IS_NULL         = 'NULL';
    const OPERATOR_IN              = 'IN';
    const OPERATOR_NOT_IN          = 'NIN';
    const OPERATOR_FIND_IN_SET     = 'FINSET';
    const OPERATOR_NOT_FIND_IN_SET = 'NFINSET';
    const OPERATOR_DATE            = 'DATE';

    public function where($condition, $value = null);
}
