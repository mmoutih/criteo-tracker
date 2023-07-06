<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class AddToCartEvent extends Event
{
    public function __construct(protected array $item)
    {
        $this->event = EventNames::ADD_TO_CARTE_EVENT;
    }
}
