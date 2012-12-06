<?php

namespace FadoeBase\Date;

class DateTime extends \DateTime
{

    private $_str;

    /**
     * default datetime format
     *
     * @var int
     */
    private $_defaultFormat = DATE_ISO8601;

    /**
     * set date
     *
     * @param  int $year
     * @param  int $month
     * @param  int day
     * @return DateTime
     */
    public function setDate($year, $month, $day)
    {
        parent::setDate($year, $month, $day);
        return $this;
    }

    /**
     * set default datetime format
     *
     * @param string|const $defaultFormat
     * @return DateTime
     */
    public function setDefaultFormat($defaultFormat)
    {
        $this->_defaultFormat = $defaultFormat;
        return $this;
    }

    /**
     * get default datetime format
     *
     * @return string
     */
    public function getDefaultFormat()
    {
        return $this->_defaultFormat;
    }

    /**
     * get formated datetime
     *
     * @param string|null $format
     * @return string
     */
    public function format($format = null)
    {
        if (null !== $format) {
            return parent::format($format);
        }
        return parent::format($this->getDefaultFormat());
    }

    /**
     * set timezone
     *
     * @param \DateTimeZone|string $timezone
     */
    public function setTimezone($timezone)
    {
        if (!$timezone instanceof \DateTimeZone) {
            $timezone = new \DateTimeZone($timezone);
        }
        parent::setTimezone($timezone);
        return $this;
    }

    /**
     * get timezone
     *
     * @return DateTimeZone
     */
    public function getTimezone()
    {
        return new Date\DateTimeZone(parent::getTimezone()->getName());
    }

    /**
     * get the first day of this week
     *
     * @return int
     */
    public function getFirstDayOfWeek()
    {
        $dow = $this->format('N');
        $date = clone $this;
        $dateInterval = new \DateInterval('P' . ($dow - 1) . 'D');
        $date->sub($dateInterval);
        return (int) $date->format('d');
    }

    /**
     * get the last day of the week
     * @return int
     */
    public function getLastDayOfWeek()
    {
        $dow = $this->format('N');
        $date = clone $this;
        $dateInterval = new \DateInterval('P' . (7 - $dow) . 'D');
        $date->add($dateInterval);
        return (int) $date->format('d');
    }

    /**
     * get the quarter
     * @return int
     */
    public function getQuarter()
    {
        return ceil($this->format('m') / 3);
    }

    /**
     * get the total weeks of the month
     * @return int
     */
    public function getWeeksInMonth()
    {
        $tmpDate = clone $this;
        $tmpDate->setDate($tmpDate->format('Y'), $tmpDate->format('m'), 1);
        $firstWeek = $tmpDate->format('W');
        $lastDay = $tmpDate->format('t');
        $tmpDate->setDate($tmpDate->format('Y'), $tmpDate->format('m'), $lastDay);
        $lastWeek = $tmpDate->format('W');

        if ($lastWeek < $firstWeek) {
            $tmpDate->setDate($tmpDate->format('Y'), $tmpDate->format('m'), $lastDay - 5);
            $lastWeek = $tmpDate->format('W') + 1;
        }

        return ($lastWeek - $firstWeek + 1);
    }

    /**
     * get the count of weekday in month from the current date
     *
     * @return int
     */
    public function getWeekdayInMonth()
    {
        return (ceil($this->format('d') / 7));
    }

    /**
     * get datetime formated
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    public function __sleep()
    {
        $this->_str = $this->format('c');
        return array('_str');
    }

    public function __wakeup()
    {
        $this->__construct($this->_str);
    }

}
