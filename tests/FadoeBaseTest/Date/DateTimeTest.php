<?php

namespace FadoeBaseTest\Date;
use PHPUnit_Framework_TestCase as TestCase;
use FadoeBase\Date\DateTime;

class DateTimeTest extends TestCase
{

    public function testQuarter()
    {
        $dateTime = new DateTime('2012-02-12');
        $this->assertEquals(1, $dateTime->getQuarter());
        $dateTime = new DateTime('2012-04-12');
        $this->assertEquals(2, $dateTime->getQuarter());
        $dateTime = new DateTime('2012-07-12');
        $this->assertEquals(3, $dateTime->getQuarter());
        $dateTime = new DateTime('2012-10-12');
        $this->assertEquals(4, $dateTime->getQuarter());
    }

    public function testWeeksInMonth()
    {
        $dateTime = new DateTime('2012-10-29');
        $this->assertEquals(5, $dateTime->getWeeksInMonth());
        $dateTime = new DateTime('2012-12-13');
        $this->assertEquals(6, $dateTime->getWeeksInMonth());
    }

    public function testWeekdayInMonth()
    {
        $dateTime = new DateTime('2012-10-29');
        $this->assertEquals(5, $dateTime->getWeekdayInMonth());
        $dateTime = new DateTime('2012-10-08');
        $this->assertEquals(2, $dateTime->getWeekdayInMonth());
    }

    public function testFirstDayOfWeek()
    {
        $dateTime = new DateTime('2012-10-24');
        $this->assertEquals(22, $dateTime->getFirstDayOfWeek());
        $dateTime = new DateTime('2012-10-29');
        $this->assertEquals(29, $dateTime->getFirstDayOfWeek());
    }

    public function testLastDayOfWeek()
    {
        $dateTime = new DateTime('2012-10-24');
        $this->assertEquals(28, $dateTime->getLastDayOfWeek());
        $dateTime = new DateTime('2012-10-29');
        $this->assertEquals(4, $dateTime->getLastDayOfWeek());
    }

    public function testSleepWakeup()
    {
        $date = new DateTime('2012-10-12');
        $string = serialize($date);
        $date2 = unserialize($string);
        $this->assertEquals($date->format(), $date2->format());

    }

    public function testTimezone()
    {
        //$date = new DateTime();
        //$date->setTimezone('Europe/Berlin');
        //$this->assertEquals($date->getTimezone(), 'Europe/Berline');
    }

}
