<?php

namespace  Mmoutih\CriteoTracker\TagsEvents;

class ViewSearchEvent extends Event
{
    protected string $checkin_date;
    protected string $checkout_date;
    protected int $nbra;
    protected int $nbrc;
    protected int $nbri;
    
    public function __construct()
    {
        $this->event = EventNames::VIEW_SEARCH_EVENT;
    }

    /**
     * Set the value of checkin_date
     *
     * @return  self
     */ 
    public function setCheckinDate(string $checkin_date): self
    {
        $this->checkin_date = $checkin_date;

        return $this;
    }

    /**
     * Set the value of checkout_date
     *
     * @return  self
     */ 
    public function setCheckoutDate(string $checkout_date): self
    {
        $this->checkout_date = $checkout_date;

        return $this;
    }

    /**
     * Set the value of nbra
     *
     * @return  self
     */ 
    public function setNbra(int $nbra): self
    {
        $this->nbra = $nbra;

        return $this;
    }

    /**
     * Set the value of nbrc
     *
     * @return  self
     */ 
    public function setNbrc(int $nbrc) : self
    {
        $this->nbrc = $nbrc;

        return $this;
    }

    /**
     * Set the value of nbri
     *
     * @return  self
     */ 
    public function setNbri(int $nbri): self
    {
        $this->nbri = $nbri;

        return $this;
    }
}
