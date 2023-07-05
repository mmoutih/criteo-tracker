<?php

namespace Mmoutih\CriteoTracker;

use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\Event;
use Mmoutih\CriteoTracker\TagsEvents\HashedEmailEvent;
use Mmoutih\CriteoTracker\TagsEvents\PlainEmailEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewPageEvent;
use Mmoutih\CriteoTracker\TagsEvents\ZipCodeEvent;

final class CriteoLoader
{

    /**
     * @var Event[]
     */
    protected array $events;

    private function __construct(protected string $idCriteo)
    {
        
    }

    /** 
     * initial a loader and add basic events setAccount, setEmail, setSiteType, setZipcode, viewPage.
     * 
     * @param string $idCriteo 
     * @param string|null $clientEmail Optional. If clientEmail is not to null a setEmail event will be triggered
     * @param d|m $siteType Possible values are 'd' for desktop and 'm' for mobile, if siteType ise set to value different 
     * from 'm' or 'd' this will trigger a warning and the value will be set to 'd'
     * @param string|null $zipCode Optional. If zipCode is set not null a setZipcode event will be triggered
     * @param bool $isViewPage If isViewPage is set to true viewPage event will be triggered
     * @param bool $shouldHashEmail If shouldHasEmail is set to true the clientEmail will bes hashed before sent.
     * @return self
     */
    public static function init(
        string $idCriteo,
        string $clientEmail=null,
        string $siteType='d',
        string $zipCode=null,
        bool $isViewPage=false,
        bool $shouldHashEmail=false
    ): self
    {
       
        $loader = new self($idCriteo);
        $loader->handelAccountEvent($idCriteo);
        $loader->handelEmailEvent($clientEmail, $shouldHashEmail);
        $loader->handelSiteTypeEvent($siteType);
        $loader->handelZipCodeEvent($zipCode);
        $loader->handelViewPageEvent($isViewPage);
        return $loader;
    }

    /**
     * Add setAccount event to do events list, if idCriteo is empty a fatal error will be fired
     */
    protected function handelAccountEvent(string $idCriteo): void
    {
        if(empty($idCriteo))
            trigger_error('idCriteo can not be empty', E_USER_ERROR);
        $this->addEvent(new AccountEvent($idCriteo));
    }

    /**
     * Add setEmail event to do events list if email is not empty
     */
    protected function handelEmailEvent(?string $clientEmail, bool $shouldHashEmail): void
    {
        if(empty($clientEmail))
            return;
        if($shouldHashEmail === false)
            $this->addEvent(new PlainEmailEvent($clientEmail));
        else
            $this->addEvent(new HashedEmailEvent($clientEmail));
        
    }

    /**
     * Add siteType event to do events list after checking it's value.
     * If the value is not 'd' or 'm' it will be set to 'd' and warning is fired. 
     */
    protected function handelSiteTypeEvent(string $siteType): void
    {

        if(!in_array($siteType,['m','d'])){
            trigger_error(
                message:"siteType only support tow values 'd' for desktop or 'm' for mobile, it's value is set back to 'd'.", 
                error_level:E_USER_WARNING
            );
            $siteType = 'd';
        }
        $this->addEvent(new SiteTypeEvent($siteType));

    }

     /**
     * Add setZipcode event to do events list if zipCode is not empty
     */
    protected function handelZipCodeEvent(?string $zipCode): void
    {
        if(empty($zipCode))
            return;
        $this->addEvent(new ZipCodeEvent($zipCode));
        
    }
     
    /**
     * Add viewPage event to de events list if isViewPage is set to true.
     */
    protected function handelViewPageEvent(bool $isViewPage) : void
    {
        if(!$isViewPage)
            return;
        $this->addEvent(new ViewPageEvent());
    }
    
    /**
     * return javascript tag that we include in the header
     * @return string
     */
    public function getCriteoLoaderFile(): string
    {
        return <<<SCRIPT
        <script type="text/javascript" src="//dynamic.criteo.com/js/ld/ld.js?a={$this->idCriteo}" async="true"></script>
        SCRIPT;
    }

    /**
     * add event
     * @param Event $event
     * @return self
     */
    public function addEvent(Event $event): self
    {
        $this->events[] = $event;
        return $this;
    }

    /**
     * Get the value of events
     * @return Event[]
     */ 
    public function getEvents(): array
    {
        return $this->events;
    }
}
