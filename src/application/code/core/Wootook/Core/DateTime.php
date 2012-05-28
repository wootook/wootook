<?php


class Wootook_Core_DateTime
{
    const ISO_8601 = 'c';

    const YEAR        = 'year';
    const YEAR_ISO    = 'year_iso';
    const MONTH       = 'mon';
    const DAY         = 'mday';
    const WEEK        = 'week';
    const WEEKDAY     = 'wday';
    const HOUR        = 'hours';
    const MINUTE      = 'minutes';
    const SECOND      = 'seconds';
    const MICROSECOND = 'microsecond';
    const DAYLIGHT    = 'daylight';
    const MERIDIEM    = 'meridiem';
    const MERIDIEM_AM = 'am';
    const MERIDIEM_PM = 'pm';

    const TIMESTAMP = 'timestamp';

    const PARSER = 'cb';

    const OPERATOR_ADD = '+';
    const OPERATOR_SUB = '-';
    const OPERATOR_SET = '=';

    protected static $_rules = array(
        'd' => array(self::DAY      => '#(0[1-9]|[12][0-9]|3[01])#'),
        'j' => array(self::DAY      => '#([1-9]|[12][0-9]|3[01])#'),
        'D' => array(self::WEEKDAY  => '#(Mon|Tue|Wed|Thu|Fri|Sat|Sun)#i', self::PARSER => '_parseShortWeekday'),
        'N' => array(self::WEEKDAY  => '#([1-7])#', self::PARSER => '_parseWeekday'),
        'w' => array(self::WEEKDAY  => '#([0-6])#'),
        'm' => array(self::MONTH    => '#(0[1-9]|1[0-2])#'),
        'M' => array(self::MONTH    => '#(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)#i', self::PARSER => '_parseShortMonth'),
        'n' => array(self::MONTH    => '#([1-9]|1[0-2])#'),
        'o' => array(self::YEAR_ISO => '#([0-9]{4})#'),
        'Y' => array(self::YEAR     => '#([0-9]{4})#'),
        'y' => array(self::YEAR     => '#([0-9]{2})#', self::PARSER => '_parseShortYear'),

        'a' => array(self::MERIDIEM    => '#(am|pm)#', self::PARSER => '_parseMeridiem'),
        'A' => array(self::MERIDIEM    => '#(AM|PM)#', self::PARSER => '_parseMeridiem'),
        'g' => array(self::HOUR        => '#([0-9]{1,2})#'),
        'G' => array(self::HOUR        => '#([0-9]{1,2})#'),
        'h' => array(self::HOUR        => '#([0-9]{2})#'),
        'H' => array(self::HOUR        => '#([0-9]{2})#'),
        'i' => array(self::MINUTE      => '#([0-9]{2})#'),
        's' => array(self::SECOND      => '#([0-9]{2})#'),
        'u' => array(self::MICROSECOND => '#([0-9]{6})#'),
        'I' => array(self::DAYLIGHT    => '#(0|1)#'),

        // Unimplemented and ignored modifiers
        'l' => array(null => '#.#'),
        'S' => array(null => '#.#'),
        'z' => array(null => '#.#'),
        'W' => array(null => '#.#'),
        'f' => array(null => '#.#'),
        't' => array(null => '#.#'),
        'L' => array(null => '#.#'),
        'O' => array(null => '#.#'),
        'P' => array(null => '#.#'),
        'T' => array(null => '#.#'),
        'Z' => array(null => '#.#'),
        'c' => array(null => '#.#'),
        'r' => array(null => '#.#'),
        'U' => array(null => '#.#')
        );

    protected $_datetime = null;

    public static function init($timezone = null, $force = false)
    {
        static $init = false;

        if ($init === false || $force === true) {
            if ($timezone === null) {
                //$timezone = Wootook::getConfig('system/date/timezone');
            }
            if ($timezone === null) {
                $timezone = 'GMT';
            }

            date_default_timezone_set($timezone);
            $init = true;
        }
    }

    public function __construct($timestamp = null, $format = null)
    {
        self::init();

        if ($timestamp === null) {
            $this->_datetime = time();
        } else if ($format === null) {
            $this->_datetime = intval($timestamp);
        } else {
            $this->_datetime = $this->_parse($timestamp, $format);
        }
    }

    public function getIso($useTimezone = false)
    {
        return $this->toString(self::ISO_8601, $useTimezone);
    }

    public function getTimestamp()
    {
        return $this->_datetime;
    }

    public function now()
    {
        return $this->calc(time(), self::TIMESTAMP, self::OPERATOR_SET);
    }

    public function set($value, $part = self::TIMESTAMP)
    {
        return $this->calc($value, $part, self::OPERATOR_SET);
    }

    public function add($value, $part = self::TIMESTAMP)
    {
        return $this->calc($value, $part, self::OPERATOR_ADD);
    }

    public function sub($value, $part = self::TIMESTAMP)
    {
        return $this->calc($value, $part, self::OPERATOR_SUB);
    }

    public function calc($value, $part = self::TIMESTAMP, $type = self::OPERATOR_ADD)
    {
        switch ($part) {
        case self::YEAR:
        case self::MONTH:
        case self::WEEK:
        case self::DAY:
        case self::HOUR:
        case self::MINUTE:
        case self::SECOND:
            $current = getdate($this->_datetime);
            if ($type === self::OPERATOR_ADD) {
                $current[$part] += $value;
            } else if ($type === self::OPERATOR_SUB) {
                $current[$part] -= $value;
            } else if ($type === self::OPERATOR_SET) {
                $current[$part] = $value;
            }

            $this->_datetime = $this->_mktime($current);
            break;

        case self::TIMESTAMP:
            if ($type === self::OPERATOR_ADD) {
                $this->_datetime += intval($value);
            } else if ($type === self::OPERATOR_SUB) {
                $this->_datetime -= intval($value);
            } else if ($type === self::OPERATOR_SET) {
                $this->_datetime = intval($value);
            }
            break;
        // default: break; // omitted
        }

        return $this;
    }

    public function diff(self $date)
    {
        return $this->_datetime - $date->_datetime;
    }

    public function isEarlier(self $date = null)
    {
        if ($date === null) {
            $date = new self();
        }
        return (bool) ($this->diff($date) > 0);
    }

    public function isLater(self $date = null)
    {
        if ($date === null) {
            $date = new self();
        }
        return (bool) ($this->diff($date) < 0);
    }

    protected function _mktime(Array $parts)
    {
        return mktime($parts[self::HOUR], $parts[self::MINUTE], $parts[self::SECOND],
            $parts[self::MONTH], $parts[self::DAY], $parts[self::YEAR]);
    }

    public function toString($format, $useTimezone = true)
    {
        if ($useTimezone) {
            return date($format, $this->_datetime);
        } else {
            return gmdate($format, $this->_datetime);
        }
    }

    public function __toString()
    {
        return $this->toString(self::ISO_8601, false);
    }

    protected function _parseShortYear($match)
    {
        $year = intval($match);
        if ($year < 70) {
            $year += 2000;
        } else if ($year < 100) {
            $year += 1900;
        }
        return $year;
    }

    protected function _parseShortMonth($match)
    {
        if ($key = array_search(strtolower($match), array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'))) {
            return $key + 1;
        }
        return false;
    }

    protected function _parseShortWeekday($match)
    {
        if ($key = array_search(strtolower($match), array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'))) {
            return $key;
        }
        return false;
    }

    protected function _parseWeekday($match)
    {
        return intval($match) % 7;
    }

    protected function _parseMeridiem($match)
    {
        if (in_array(strtolower($match), array(self::MERIDIEM_AM, self::MERIDIEM_PM))) {
            return strtolower($match);
        }
        return false;
    }

    protected function _parse($datetime, $format)
    {
        $parts = array(
            self::YEAR        => false,
            self::YEAR_ISO    => false,
            self::MONTH       => false,
            self::DAY         => false,
            self::WEEKDAY     => false,
            self::HOUR        => false,
            self::MINUTE      => false,
            self::SECOND      => false,
            self::MICROSECOND => false,
            self::DAYLIGHT    => false,
            self::MERIDIEM    => false,
            );

        $offset = 0;

        $length = strlen($format);
        for ($i = 0; $i < $length; $i++) {
            $modifier = $format[$i];
            if (!isset(self::$_rules[$modifier])) {
                $offset = (strpos($datetime, $format[$i], $offset)) + 1;
                continue;
            }

            $rule = self::$_rules[$modifier];
            $callback = null;
            if (isset($rule[self::PARSER])) {
                $callback = $rule[self::PARSER];
                unset($rule[self::PARSER]);
            }

            foreach ($rule as $part => $regex) {
                if (!preg_match($regex, $datetime, $matches, null, $offset)) {
                    continue;
                }

                if ($callback === null) {
                    $parts[$part] = intval($matches[1]);
                } else if (is_callable($callback)) {
                    $parts[$part] = call_user_func($callback, $matches[1]);
                } else {
                    $parts[$part] = $this->$callback($matches[1]);
                }
                $offset += strlen($matches[1]);
            }
        }

        if ($parts[self::MERIDIEM] !== false && $parts[self::MERIDIEM] === self::MERIDIEM_PM) {
            $parts[self::HOUR] = ($parts[self::HOUR] + 12) % 24;
        }

        if ($parts[self::YEAR] !== false || $parts[self::YEAR_ISO] === false) {
            return $this->_mktime($parts);
        } else {
            return $this->_mktime($parts);
        }
    }
}
