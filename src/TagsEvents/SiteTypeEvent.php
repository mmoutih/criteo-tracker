<?php

namespace Mmoutih\CriteoTracker\TagsEvents;

class SiteTypeEvent extends Event
{
    public function __construct(protected string $type)
    {
        $this->event = EventNames::SITE_TYPE_EVENT;
    }
}
