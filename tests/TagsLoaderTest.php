<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Mmoutih\CriteoTracker\CriteoLoader;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewHomeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewListEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewPageEvent;
use Mmoutih\CriteoTracker\TagsEvents\PlainEmailEvent;
use Mmoutih\CriteoTracker\TagsEvents\HashedEmailEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;

class TagsLoaderTest extends TestCase
{
    protected CriteoLoader $loader;
    protected $idCriteo=123456;

    public function setUp(): void
    {
        $this->loader = CriteoLoader::init($this->idCriteo);
    }

    public function testLoadingTags()
    {
        $header = $this->loader->getCriteoLoaderFile();
        $this->assertStringContainsString($this->idCriteo,$header);
        $this->assertStringContainsString("text/javascript",$header);
        $this->assertStringContainsString("dynamic.criteo.com/js/ld/ld.js",$header);
    }

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

    public function testViewHomeEvent()
    {
        $events = $this->loader->viewHomePage()->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertTrue($this->hasEvent($events,ViewHomeEvent::class));
    }

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

    protected function hasEvent(array $events, string $className): bool
    {
        foreach($events as $event)
            if($event instanceof $className)
                return true;
        return false;
    }
}
