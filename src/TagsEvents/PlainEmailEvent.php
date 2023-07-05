<?php

namespace Mmoutih\CriteoTracker\TagsEvents;

class PlainEmailEvent extends Event
{

    public function __construct(protected string $email)
    {
        $this->event = EventNames::EMAIL_EVENT;
        
    }
}
