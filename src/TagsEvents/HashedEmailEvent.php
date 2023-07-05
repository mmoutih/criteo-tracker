<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class HashedEmailEvent extends Event
{
    protected string $hash_method = "sha256";
    protected string $email;
    public function __construct(string $email)
    {
        $this->event = EventNames::EMAIL_EVENT;
        $this->email = hash($this->hash_method, $email); 

    }
}
