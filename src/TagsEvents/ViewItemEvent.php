<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class ViewItemEvent extends Event
{
    public function __construct(protected string $item)
    {
        $this->event = EventNames::VIEW_ITEM_EVENT;
    }
}
