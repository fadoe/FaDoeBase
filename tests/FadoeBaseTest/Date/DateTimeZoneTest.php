<?php

namespace FadoeBaseTest\Date;
use PHPUnit_Framework_TestCase as TestCase;
use FadoeBase\Date\DateTimeZone;

class DateTimeZoneTest extends TestCase
{

    public function testDateTimeZoneToString()
    {
        $dateTime = new DateTimeZone('Europe/Berlin');

        $this->assertEquals((string) $dateTime, 'Europe/Berlin');
    }

}
