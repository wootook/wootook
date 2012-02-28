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

class Math_Bcmath
{
    public function setPrecision($precision)
    {
        bcscale($precision);
    }

    public function add($a, $b)
    {
        return bcadd($a, $b);
    }

    public function mul($a, $b)
    {
        return bcmul($a, $b);
    }

    public function sub($a, $b)
    {
        return bcsub($a, $b);
    }

    public function div($a, $b)
    {
        return bcdiv($a, $b);
    }

    public function mod($a, $b)
    {
        return bcmod($a, $b);
    }

    public function pow($a, $b)
    {
        return bcpow($a, $b);
    }

    public function comp($a, $b)
    {
        return bccomp($a, $b);
    }

    public function ceil($x)
    {
        $integer = bcmul($x, 1, 0);
        $decimals = bcsub($x, $integer);

        if (bccomp($decimals, 0) > 0) {
            return bcadd($integer, 1);
        } else {
            return $integer;
        }
    }

    public function floor($x)
    {
        $integer = bcmul($x, 1, 0);
        $decimals = bcsub($x, $integer);

        if (bccomp($decimals, 0) < 0) {
            return bcsub($integer, 1);
        } else {
            return $integer;
        }
    }

    public function round($x, $range = 0)
    {
        $integer = bcmul($x, 1, 0);
        $decimals = bcsub($x, $integer, $range);

        if (bccomp($decimals, 0) > 0) {
            return bcadd($integer, '0.' . str_pad('5', $range, STR_PAD_LEFT), $range);
        } else {
            return bcsub($integer, '0.' . str_pad('5', $range, STR_PAD_LEFT), $range);
        }
    }

    public function render($a)
    {
        if (!$a) {
            return '0';
        }
        $negative = false;
        if ($a[0] == '-') {
            $a = substr($a, 1);
            $negative = true;
        }
        if ($a[0] == '+') {
            $a = substr($a, 1);
        }

        $a = strval($a);

        $pos = strrpos($a, '.');
        if ($pos !== false) {
            $a = substr($a, 0, $pos);
        }

        $length = (3 - (strlen($a) % 3)) % 3;
        $a = str_pad($a, $length + strlen($a), ' ', STR_PAD_LEFT);
        $parts = str_split($a, 3);
        $parts[0] = ltrim($parts[0]);
        switch ((int) (count($parts) / 3)) {
        case 0:
        case 1:
            return ($negative ? '-' : '') . implode('.', $parts);
            break;

        case 2:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, 2)) . 'M';
            break;

        case 3:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, 2)) . 'G';
            break;

        case 4:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, 2)) . 'T';
            break;

        case 5:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, 2)) . 'P';
            break;

        case 6:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, 2)) . 'Y';
            break;

        default:
            return ($negative ? '-' : '') . implode('.', array_slice($parts, 0, sizeof($parts) - 18)) . 'Z';
            break;
        }
    }
}