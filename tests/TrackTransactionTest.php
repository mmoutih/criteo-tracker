<?php

namespace Tests\marouane\Documents\SidWork\CriteoTracker\tests;

use Tests\CriteoTagTestCase;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\TrackTransactionEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;

class TrackTransactionTest extends CriteoTagTestCase
{
    public function testTrackTransactionEventWithoutDate()
    {
        $item =  [
            ["id" => "prudct_id_1", "price" => 200, "quantity"=> 1]
        ];
        $events = $this->loader->trackTransactionEvent("transaction_id",$item)->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,TrackTransactionEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewSearchEvent::class));

    }

    public function testTrackTransactionEventWithInvalidTransactionId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("invalid transaction id");
        $this->expectExceptionCode(6);
        $this->loader->trackTransactionEvent("", [])->getEvents();
    }

    public function testTrackTransactionEventWithInvalidItem()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Items can not be empty");
        $this->expectExceptionCode(5);
        $this->loader->trackTransactionEvent("transaction_id", [])->getEvents();
    }

    public function testTrackTransactionEventWithDate()
    {
        $item =  [["id" => "prudct_id_1", "price" => 200, "quantity"=> 1]];
        $events = $this->loader->trackTransactionEvent("transaction_id", $item, checkin:"06-07-2023", checkout:"12-07-2023")->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,TrackTransactionEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewSearchEvent::class));
    }
}
