<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class ViewBasketEvent extends Event
{
    public function __construct(protected array $item)
    {
        $this->event = EventNames::VIEW_BASKET_EVENT;
    }
}
