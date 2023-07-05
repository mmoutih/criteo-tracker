<?php

namespace Mmoutih\CriteoTracker\TagsEvents;


class AccountEvent extends Event
{
    public function __construct(protected string $account)
    {
        $this->event= EventNames::ACCOUNT_EVENT;
    }
}
