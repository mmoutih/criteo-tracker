<?php

namespace Tests;

use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;
use Tests\CriteoTagTestCase;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewItemEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;

class ViewItemEventTest extends CriteoTagTestCase
{
    public function testViewItemEventWithoutDate()
    {
        $events = $this->loader->viewItemPage("1-_")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewItemEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewSearchEvent::class));

    }

    public function testViewItemEventWithInvalidId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid id, id should be alphanumerical value.");
        $this->expectExceptionCode(3);
        $this->loader->viewItemPage(" ")->getEvents();
    }

    public function testViewItemEventWithDate()
    {
        $events = $this->loader->viewItemPage(itemId: 1,checkin:"06-07-2023",checkout:"12-07-2023")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewItemEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewSearchEvent::class));
    }
}
