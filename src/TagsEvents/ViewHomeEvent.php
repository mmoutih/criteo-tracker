<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class ViewHomeEvent extends Event
{
    public function __construct()
    {
        $this->event = EventNames::VIEW_HOME_EVENT;
    }
}
