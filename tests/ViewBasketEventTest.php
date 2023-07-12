<?php

namespace Tests;

use Tests\CriteoTagTestCase;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewBasketEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;

class ViewBasketEventTest extends CriteoTagTestCase
{
    public function testViewBasketEventWithoutDate()
    {
        $item =  [
            ["id" => "prudct_id_1", "price" => 200, "quantity"=> 1]
        ];
        $events = $this->loader->viewBasketEvent($item)->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewBasketEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewSearchEvent::class));

    }

    
    public function testViewBasketEventWithInvalidItem()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Items can not be empty");
        $this->expectExceptionCode(5);
        $this->loader->ViewBasketEvent([])->getEvents();
    }

    public function testViewBasketEventWithDate()
    {
        $item =  [["id" => "prudct_id_1", "price" => 200, "quantity"=> 1]];
        $events = $this->loader->ViewBasketEvent($item, checkin:"06-07-2023", checkout:"12-07-2023")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewBasketEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewSearchEvent::class));
    }
}
