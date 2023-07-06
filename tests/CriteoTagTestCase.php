<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Mmoutih\CriteoTracker\CriteoLoader;

class CriteoTagTestCase extends TestCase
{

    protected CriteoLoader $loader;
    protected $idCriteo=123456;

    public function setUp(): void
    {
        $this->loader = CriteoLoader::init($this->idCriteo);
    }

    protected function hasEvent(array $events, string $className): bool
    {
        foreach($events as $event)
            if($event instanceof $className)
                return true;
        return false;
    }
}
