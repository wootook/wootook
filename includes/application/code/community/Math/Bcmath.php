<?php

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

        $pos = strrpos(strval($a), '.');
        $a = substr(strval($a), 0, $pos);

        $length = $pos + ((3 - ($pos % 3)) % 3);
        $a = str_pad($a, $length, ' ', STR_PAD_LEFT);
        $parts = str_split($a, 3);
        switch ((int) (count($parts) / 3)) {
        case 0:
        case 1:
            return implode('.', $parts);
            break;

        case 2:
            return implode('.', array_slice($parts, 0, 2)) . 'M';
            break;

        case 3:
            return implode('.', array_slice($parts, 0, 2)) . 'G';
            break;

        case 4:
            return implode('.', array_slice($parts, 0, 2)) . 'T';
            break;

        case 5:
            return implode('.', array_slice($parts, 0, 2)) . 'P';
            break;

        case 6:
            return implode('.', array_slice($parts, 0, 2)) . 'Y';
            break;

        default:
            return implode('.', array_slice($parts, 0, sizeof($parts) - 18)) . 'Z';
            break;
        }
    }
}