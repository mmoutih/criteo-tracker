<?php
namespace Tests;

use Mmoutih\CriteoTracker\CriteoLoader;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewPageEvent;
use PHPUnit\Framework\TestCase;

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

    public function testDefaultTags()
    {
        $events = $this->loader->getEvents();
        $this->assertTrue($this->hasEvent($events,AccountEvent::class));
        $this->assertTrue($this->hasEvent($events,SiteTypeEvent::class));
        $this->assertFalse($this->hasEvent($events,ViewPageEvent::class));
    }

    protected function hasEvent(array $events, string $className): bool
    {
        foreach($events as $event)
            if($event instanceof $className)
                return true;
        return false;
    }
}
