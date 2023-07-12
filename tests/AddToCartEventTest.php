<?php

namespace Tests;

use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\AddToCartEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;

class AddToCartEventTest extends CriteoTagTestCase
{
    public function testAddToCartEventWithoutDate()
    {
        $item =  ["id" => "prudct_id_1", "price" => 200, "quantity"=> 1];
        $events = $this->loader->addToCartEvent($item)->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,AddToCartEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewSearchEvent::class));

    }

    
    public function testAddToCartEventWithInvalidItem()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Item id is not a valid id");
        $this->expectExceptionCode(5);
        $this->loader->addToCartEvent([])->getEvents();
    }

    public function testAddToCartEventWithDate()
    {
        $item =  ["id" => "prudct_id_1", "price" => 200, "quantity"=> 1];
        $events = $this->loader->addToCartEvent($item, checkin:"06-07-2023", checkout:"12-07-2023")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,AddToCartEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewSearchEvent::class));
    }
}
