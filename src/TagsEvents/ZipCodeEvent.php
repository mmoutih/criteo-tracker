<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class ZipCodeEvent extends Event
{
    public function __construct(protected string $zipcode)
    {
        $this->event = EventNames::ZIP_CODE_EVENT;
    }
}
