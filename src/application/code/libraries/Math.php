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
class Math
{
    const DEFAULT_PRECISTION = 0;

    protected static $_singleton = null;

    public static function setPrecision($precision = null)
    {
        if ($precision === null) {
            $precision = self::DEFAULT_PRECISTION;
        }

        self::getSingleton()->setPrecision($precision);
    }

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            if (extension_loaded('bcmath') && defined('BCNUMBERS')) {
                self::$_singleton = new Math_Bcmath();
            } else {
                self::$_singleton = new Math_Native();
            }
        }
        return self::$_singleton;
    }

    public static function add($a, $b)
    {
        return self::getSingleton()->add($a, $b);
    }

    public static function sub($a, $b)
    {
        return self::getSingleton()->sub($a, $b);
    }

    public static function mul($a, $b)
    {
        return self::getSingleton()->mul($a, $b);
    }

    public static function div($a, $b)
    {
        return self::getSingleton()->div($a, $b);
    }

    public static function comp($a, $b)
    {
        return self::getSingleton()->comp($a, $b);
    }

    public static function pow($a, $b)
    {
        return self::getSingleton()->pow($a, $b);
    }

    public static function mod($a, $b)
    {
        return self::getSingleton()->mod($a, $b);
    }

    public static function ceil($a)
    {
        return self::getSingleton()->ceil($a);
    }

    public static function floor($a)
    {
        return self::getSingleton()->floor($a);
    }

    public static function round($a, $range = 0)
    {
        return self::getSingleton()->round($a, $range);
    }

    public static function render($a)
    {
        return self::getSingleton()->render($a);
    }

    public static function isNegative($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) < 0);
    }

    public static function isPositive($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) > 0);
    }

    public static function isNegativeOrZero($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) <= 0);
    }

    public static function isPositiveOrZero($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) >= 0);
    }

    public static function isZero($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) == 0);
    }

    public static function isNotZero($a)
    {
        return (bool) (self::getSingleton()->comp($a, 0) != 0);
    }

    public static function min($a, $b)
    {
        if (self::getSingleton()->comp($a, $b) > 0) {
            return $b;
        }
        return $a;
    }

    public static function max($a, $b)
    {
        if (self::getSingleton()->comp($a, $b) < 0) {
            return $b;
        }
        return $a;
    }
}