<?php

namespace Tests;

use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewHomeEvent;

class ViewHomeEventTest extends CriteoTagTestCase
{
    public function testViewHomeEvent()
    {
        $events = $this->loader->viewHomePage()->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewHomeEvent::class));
    }
}
