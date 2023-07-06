<?php

namespace Tests;

use Mmoutih\CriteoTracker\CriteoLoader;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewPageEvent;
use Mmoutih\CriteoTracker\TagsEvents\PlainEmailEvent;
use Mmoutih\CriteoTracker\TagsEvents\HashedEmailEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;

class DefaultEventsTest extends CriteoTagTestCase
{
    public function testDefaultEvents()
    {
        $events = $this->loader->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewPageEvent::class));
    }

    public function testPlainEmailEvent()
    {
        $events = CriteoLoader::init(idCriteo:$this->idCriteo,clientEmail:"test@test")->getEvents();
        $this->assertTrue($this->hasEvent($events,PlainEmailEvent::class));
        $this->assertFalse($this->hasEvent($events,HashedEmailEvent::class));
    }

    public function testHashedEmailEvent()
    {
        $events = CriteoLoader::init(idCriteo:$this->idCriteo,clientEmail:"test@test",shouldHashEmail:true)->getEvents();
        $this->assertFalse($this->hasEvent($events,PlainEmailEvent::class));
        $this->assertTrue($this->hasEvent($events,HashedEmailEvent::class));
    }

    public function testEmptyIdCriteo()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('idCriteo can not be empty or not an alphanumeric string');
        $this->expectExceptionCode(1);
        CriteoLoader::init("")->getEvents();
    }

    public function testNonAlphaNumericIdCriteo()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('idCriteo can not be empty or not an alphanumeric string');
        $this->expectExceptionCode(1);
        CriteoLoader::init("+x ")->getEvents();
    }

    public function testInvalidSiteType()
    { 
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage( "siteType only support tow values 'd' for desktop or 'm' for mobile, it's value is set back to 'd'.");
        $this->expectExceptionCode(2);
        CriteoLoader::init(idCriteo:$this->idCriteo,siteType:"x")->getEvents();
    }
}
