<?php

namespace Mmoutih\CriteoTracker\TagsEvents;

class TrackTransactionEvent extends Event
{
    public function __construct(protected string $id, protected array $item)
    {
        $this->event = EventNames::TRACK_TRANSACTION_EVENT;
    }
}
