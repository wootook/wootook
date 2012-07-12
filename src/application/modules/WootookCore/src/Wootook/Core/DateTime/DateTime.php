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

namespace Wootook\Core\DateTime;

use  Wootook\Core\Exception as CoreException;

/**
 * Date period management class
 */
class DateTime
    extends \DateTime
{
    /**
     * @var Formatter
     */
    protected $_formatter = null;

    /**
     * @param string|int $dateTime
     * @param null|DateTimeZone $timeZone
     * @param Formatter $formatter
     */
    public function __construct($dateTime = null, DateTimeZone $timeZone = null, Formatter $formatter = null, $useIntlParsing = true)
    {
        if ($formatter === null || $useIntlParsing === false) {
            parent::__construct($dateTime, $timeZone);
        } else {
            parent::__construct();

            $this->setTimestamp($formatter->parse($dateTime));
        }

        $this->_formatter = $formatter;
    }

    /**
     * @param Formatter $formatter
     * @return DateTime
     */
    public function setFormatter(Formatter $formatter)
    {
        $this->_formatter = $formatter;

        return $this;
    }

    /**
     * @return null|Formatter
     */
    public function getFormatter()
    {
        return $this->_formatter;
    }

    /**
     * @param string|int $dateTime
     * @param Formatter $formatter
     * @param null|DateTimeZone $timeZone
     */
    public function set($dateTime, DateTimeZone $timeZone = null, Formatter $formatter)
    {
        if ($formatter === null) {
            $this->setTimestamp(strtotime($dateTime));
        } else {
            $this->setTimestamp($formatter->parse($dateTime));
        }

        return $this;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isEarlier(\DateTime $dateTime)
    {
        return $this < $dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isLater(\DateTime $dateTime)
    {
        return $this > $dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isToday(\DateTime $today = null)
    {
        if ($today === null) {
            $today = new self(null, $this->getTimezone());
        } else {
            $today = clone $today;
        }

        $today->setTime(0, 0, 0);
        if ($this->isEarlier($today)) {
            return false;
        }

        $today->setTime(23, 59, 59);
        if ($this->isLater($today)) {
            return false;
        }

        return true;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isTomorrow(\DateTime $tomorrow = null)
    {
        if ($tomorrow === null) {
            $tomorrow = new self(null, $this->getTimezone());
        } else {
            $tomorrow = clone $tomorrow;
        }
        $tomorrow->add(new \DateInterval('P1D'));

        $tomorrow->setTime(0, 0, 0);
        if ($this->isEarlier($tomorrow)) {
            return false;
        }

        $tomorrow->setTime(23, 59, 59);
        if ($this->isLater($tomorrow)) {
            return false;
        }

        return true;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function isYesterday(\DateTime $yesterday = null)
    {
        if ($yesterday === null) {
            $yesterday = new self(null, $this->getTimezone());
        } else {
            $yesterday = clone $yesterday;
        }
        $yesterday->sub(new \DateInterval('P1D'));

        $yesterday->setTime(0, 0, 0);
        if ($this->isEarlier($yesterday)) {
            return false;
        }

        $yesterday->setTime(23, 59, 59);
        if ($this->isLater($yesterday)) {
            return false;
        }

        return true;
    }

    /**
     * @param null|Formatter $formatter
     * @param string $pattern
     * @return string
     */
    public function toString(Formatter $formatter = null, $pattern = null)
    {
        if ($formatter === null) {
            $formatter = $this->_formatter;
        }

        if ($formatter === null) {
            return null;
        }

        if ($pattern !== null) {
            $formatter = clone $this->_formatter;
            $formatter->setPattern($pattern);
            return $formatter->format($this);
        }

        return $this->_formatter->format($this);
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function __toString()
    {
        return $this->toString();
    }
}
