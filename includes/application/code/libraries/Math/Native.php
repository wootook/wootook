<?php

class Math_Native
{
    public function setPrecision($precision)
    {
    }

    public function add($a, $b)
    {
        return $a + $b;
    }

    public function mul($a, $b)
    {
        return $a * $b;
    }

    public function sub($a, $b)
    {
        return $a - $b;
    }

    public function div($a, $b)
    {
        return $a / $b;
    }

    public function mod($a, $b)
    {
        return $a % $b;
    }

    public function pow($a, $b)
    {
        return pow($a, $b);
    }

    public function comp($a, $b)
    {
        return ($a > $b) ? 1 : (($a < $b) ? -1 : 0);
    }

    public function ceil($x)
    {
        return ceil($x);
    }

    public function floor($x)
    {
        return floor($x);
    }

    public function round($range = 0)
    {
        return round($x, $range);
    }

    public function render($a)
    {
        return number_format($a, 0, ',', '.');
    }
}