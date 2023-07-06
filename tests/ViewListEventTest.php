<?php

namespace Tests;

use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewListEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;

class ViewListEventTest extends CriteoTagTestCase
{
    public function testViewListEventWithoutDate()
    {
        $events = $this->loader->viewListPage([1,2,3])->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewListEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewSearchEvent::class));
    }

    public function testViewListEventWithoutDateAndEmptyListIds()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(3);
        $this->expectExceptionMessage("itemsIds can not be empty");
        $this->loader->viewListPage([])->getEvents();
    }

    public function testViewListEventWithoutDateAndInvalidListIds()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(3);
        $this->expectExceptionMessage("Some item ids are not valid ids");
        $this->loader->viewListPage([[1,2],3])->getEvents();
    }

    public function testViewListEventWithOutItems()
    {

        $events = $this->loader->viewListPage()->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewListEvent::class));
    }

    public function testViewListEventWithDate()
    {

        $events = $this->loader->viewListPage(itemsIds:[1,2,3],checkin:"06-07-2023",checkout:"12-07-2023")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewListEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewSearchEvent::class));
    }

    public function testViewListWithInvalidDateEvent()
    {
        $date = "not a date";
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid Date " . $date);
        $this->expectExceptionCode(4);
        $this->loader->viewListPage(checkin:$date,checkout:$date)->getEvents();
      
    }
}
