<?php

namespace Mmoutih\CriteoTracker\TagsEvents;



class ViewPageEvent extends Event
{
    public function __construct()
    {
        $this->event = EventNames::VIEW_PAGE_EVENT;
    }
}
