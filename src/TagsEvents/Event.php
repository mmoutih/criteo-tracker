<?php

namespace Mmoutih\CriteoTracker\TagsEvents;

abstract class Event implements \JsonSerializable 
{
    protected string $event;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
