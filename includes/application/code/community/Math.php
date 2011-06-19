<?php

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
            if (extension_loaded('bcmath')) {
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
}