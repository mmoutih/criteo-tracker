<?php

namespace Mmoutih\CriteoTracker;

use DateTime;
use Mmoutih\CriteoTracker\TagsEvents\Event;
use Mmoutih\CriteoTracker\TagsEvents\AccountEvent;
use Mmoutih\CriteoTracker\TagsEvents\ZipCodeEvent;
use Mmoutih\CriteoTracker\TagsEvents\SiteTypeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewHomeEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewItemEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewListEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewPageEvent;
use Mmoutih\CriteoTracker\TagsEvents\AddToCartEvent;
use Mmoutih\CriteoTracker\TagsEvents\PlainEmailEvent;
use Mmoutih\CriteoTracker\TagsEvents\ViewSearchEvent;
use Mmoutih\CriteoTracker\TagsEvents\HashedEmailEvent;
use Mmoutih\CriteoTracker\Exceptions\InvalidArgumentException;
use Mmoutih\CriteoTracker\TagsEvents\ViewBasketEvent;

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
     * @param string $idCriteo if idCriteo is empty or not alphanumeric string it throw an InvalidArgumentException
     * @param string|null $clientEmail Optional. If clientEmail is not to null a setEmail event will be triggered
     * @param string $siteType Possible values are 'd' for desktop and 'm' for mobile, if siteType ise set to value different 
     * from 'm' or 'd' this will throw InvalidArgumentException
     * @param string|null $zipCode Optional. If zipCode is set not null a setZipcode event will be triggered
     * @param bool $isViewPage If isViewPage is set to true viewPage event will be triggered
     * @param bool $shouldHashEmail If shouldHasEmail is set to true the clientEmail will bes hashed before sent.
     * @return self
     */
    public static function init(
        string $idCriteo,
        string $clientEmail = null,
        string $siteType = 'd',
        string $zipCode = null,
        bool $isViewPage = false,
        bool $shouldHashEmail = false
    ): self {

        $loader = new self($idCriteo);
        $loader->handelAccountEvent($idCriteo);
        $loader->handelEmailEvent($clientEmail, $shouldHashEmail);
        $loader->handelSiteTypeEvent($siteType);
        $loader->handelZipCodeEvent($zipCode);
        $loader->handelViewPageEvent($isViewPage);
        return $loader;
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
     * Add viewHome to the events list
     * @return self
     */
    public function viewHomePage(): self
    {
        $this->addEvent(new ViewHomeEvent());
        return $this;
    }

    /**
     * Add viewList to the events list
     * @param array $itemsIds Should ne an array of alphanumeric entries, it throw InvalidArgumentException it not.
     * @param string|int $categoryId Optional.
     * @param string $keywords Optional. Keywords of the search landing to the page.
     * @param string|DateTime $checkin Optional. throw InvalidArgumentException if is not a valid date
     * @param string|DateTime $checkout Optional. throw InvalidArgumentException if is not a valid date
     * @param int $nbrAdults Optional.
     * @param int $nbrChildren Optional.
     * @param int $nbrInfants Optional.
     */
    public function viewListPage(
        array $itemsIds = null,
        string|int $categoryId = null,
        string $keywords = null,
        string|DateTime $checkin = null,
        string|DateTime $checkout = null,
        int $nbrAdults = null,
        int $nbrChildren = null,
        int $nbrInfants = null
    ): self {
        $this->validateItemsIds($itemsIds);
        $this->handelViewListEvent($itemsIds, $categoryId, $keywords);
        list($checkin, $checkout) = $this->validateDates($checkin, $checkout);
        $this->handelViewSearchEvent($checkin, $checkout, $nbrAdults, $nbrChildren, $nbrInfants);
        return $this;
    }

    /**
     * Add viewItem to the events list
     * @param string $itemId Should ne an array of alphanumeric entries, it throw InvalidArgumentException it not.
     * @param string|DateTime $checkin Optional. throw InvalidArgumentException if is not a valid date
     * @param string|DateTime $checkout Optional. throw InvalidArgumentException if is not a valid date
     * @param int $nbrAdults Optional.
     * @param int $nbrChildren Optional.
     * @param int $nbrInfants Optional.
     */
    public function viewItemPage(
        string $itemId,
        string|DateTime $checkin = null,
        string|DateTime $checkout = null,
        int $nbrAdults = null,
        int $nbrChildren = null,
        int $nbrInfants = null
    ): self {
        $itemId = trim($itemId);
        if (empty($itemId) or !$this->IsValidId($itemId))
            throw new InvalidArgumentException("Invalid id, id should be alphanumerical value.", 3);
        $this->addEvent(new ViewItemEvent($itemId));
        list($checkin, $checkout) = $this->validateDates($checkin, $checkout);
        $this->handelViewSearchEvent($checkin, $checkout, $nbrAdults, $nbrChildren, $nbrInfants);
        return $this;
    }

    /**
     * Add addToCart to the event lists
     * @param array $item Should be an array  [id,price,quantity], it throw InvalidArgumentException it not.
     * @param string|DateTime $checkin Optional. throw InvalidArgumentException if is not a valid date
     * @param string|DateTime $checkout Optional. throw InvalidArgumentException if is not a valid date
     * @param int $nbrAdults Optional.
     * @param int $nbrChildren Optional.
     * @param int $nbrInfants Optional.
     */
    public function addToCartEvent(
        array $item,
        string|DateTime $checkin = null,
        string|DateTime $checkout = null,
        int $nbrAdults = null,
        int $nbrChildren = null,
        int $nbrInfants = null
    ): self {
        $this->validateItem($item);
        $this->addEvent(new AddToCartEvent([$item]));
        list($checkin, $checkout) = $this->validateDates($checkin, $checkout);
        $this->handelViewSearchEvent($checkin, $checkout, $nbrAdults, $nbrChildren, $nbrInfants);
        return $this;
    }

    /**
     * Add addToCart to the event lists
     * @param array $items Should be an array  [[id,price,quantity]], it throw InvalidArgumentException it not.
     * @param string|DateTime $checkin Optional. throw InvalidArgumentException if is not a valid date
     * @param string|DateTime $checkout Optional. throw InvalidArgumentException if is not a valid date
     * @param int $nbrAdults Optional.
     * @param int $nbrChildren Optional.
     * @param int $nbrInfants Optional.
     */
    public function viewBasketEvent(
        array $items,
        string|DateTime $checkin = null,
        string|DateTime $checkout = null,
        int $nbrAdults = null,
        int $nbrChildren = null,
        int $nbrInfants = null
    ): self {
        if(empty($items))
            throw new InvalidArgumentException("Item id is not a valid id", 5);
        foreach($items as $item)
            $this->validateItem($item);
        $this->addEvent(new ViewBasketEvent($items));
        list($checkin, $checkout) = $this->validateDates($checkin, $checkout);
        $this->handelViewSearchEvent($checkin, $checkout, $nbrAdults, $nbrChildren, $nbrInfants);
        return $this;
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

    /**
     * Add setAccount event to the events list, if idCriteo is empty  or is not an alphanumeric string it throw an InvalidArgumentException
     */
    protected function handelAccountEvent(string $idCriteo): void
    {
        $idCriteo = trim($idCriteo);
        if (empty($idCriteo) ||  !$this->IsValidId($idCriteo))
            throw new InvalidArgumentException('idCriteo can not be empty or not an alphanumeric string', 1);
        $this->addEvent(new AccountEvent($idCriteo));
    }

    /**
     * Add setEmail event to the events list if email is not empty
     */
    protected function handelEmailEvent(?string $clientEmail, bool $shouldHashEmail): void
    {
        $clientEmail = trim($clientEmail);
        if (empty($clientEmail))
            return;
        if ($shouldHashEmail === false)
            $this->addEvent(new PlainEmailEvent($clientEmail));
        else
            $this->addEvent(new HashedEmailEvent($clientEmail));
    }

    /**
     * Add siteType event to the events list after checking it's value.
     * If the value is not 'd' or 'm' it will throw InvalidArgumentException. 
     */
    protected function handelSiteTypeEvent(string $siteType): void
    {

        if (!in_array($siteType, ['m', 'd']))
            throw new InvalidArgumentException("siteType only support tow values 'd' for desktop or 'm' for mobile, it's value is set back to 'd'.", 2);

        $this->addEvent(new SiteTypeEvent($siteType));
    }

    /**
     * Add setZipcode event to the events list if zipCode is not empty
     */
    protected function handelZipCodeEvent(?string $zipCode): void
    {
        if (empty($zipCode))
            return;
        $this->addEvent(new ZipCodeEvent($zipCode));
    }

    /**
     * Add viewPage event to the events list if isViewPage is set to true.
     */
    protected function handelViewPageEvent(bool $isViewPage): void
    {
        if (!$isViewPage)
            return;
        $this->addEvent(new ViewPageEvent());
    }

    /**
     * Add ViewListEvent event to the events.
     */
    protected function handelViewListEvent(?array $itemsIds, ?string $categoryId, ?string $keywords): void
    {
        if (empty($itemsIds))
            return;
        $viewListEvent = new ViewListEvent();
        $viewListEvent->setItems($itemsIds);
        if (!empty($categoryId))
            $viewListEvent->setCategory($categoryId);
        if (!empty($keywords))
            $viewListEvent->setKeywords($keywords);
        $this->addEvent($viewListEvent);
    }

    /**
     * Add ViewSearchEvent event to the events.
     * @param string $checkin Optional
     * @param string $checkout Optional
     * @param int $nbrAdults Optional
     * @param int $nbrChildren Optional
     * @param int $nbrInfants Optional
     */
    protected function handelViewSearchEvent(
        ?string $checkin,
        ?string $checkout,
        ?int $nbrAdults,
        ?int $nbrChildren,
        ?int $nbrInfants
    ): void {
        if (empty($checkin) && empty($checkout))
            return;
        $viewSearchEvent = new ViewSearchEvent();
        if (!empty($checkin)) $viewSearchEvent->setCheckinDate($checkin);
        if (!empty($checkout)) $viewSearchEvent->setCheckoutDate($checkout);
        if (!empty($nbrAdults)) $viewSearchEvent->setNbra($nbrAdults);
        if (!empty($nbrChildren)) $viewSearchEvent->setNbrc($nbrChildren);
        if (!empty($nbrInfants)) $viewSearchEvent->setNbri($checkin);

        $this->addEvent($viewSearchEvent);
    }

    /**
     * validate items ids
     * @param array $itemsIds
     */
    protected function validateItemsIds(?array $itemsIds): void
    {
        if (is_null($itemsIds))
            return;
        if (empty($itemsIds))
            throw new InvalidArgumentException("itemsIds can not be empty", 3);
        $filteredItemsIds = array_filter(
            $itemsIds,
            fn ($id) => $this->IsValidId($id)
        );
        if (count($filteredItemsIds) !== count($itemsIds))
            throw new InvalidArgumentException("Some item ids are not valid ids", 3);
    }

    public function validateItem(array $item)
    {
        $keys = ["id", "price", "quantity"];

        if (!key_exists('id', $item) || !$this->isValidId($item['id']))
            throw new InvalidArgumentException("Item id is not a valid id", 5);
    }

    /**
     * check if is id has valid alphanumeric format
     */
    protected function isValidId(mixed $id): bool
    {

        return !is_array($id) && !is_object($id) && $this->regex($id);
    }

    private function regex(mixed $id): bool
    {
        preg_match('/[a-z_\-0-9]+/i', $id, $matches);
        foreach ($matches as $match)
            if ($match == $id) return true;
        return false;
    }

    /**
     * validate checkin checkout date.
     * @param string|DateTime|null $checkin
     * @param string|DateTime|null $checkout
     * @return array
     */
    protected function validateDates(string|DateTime|null $checkin, string|DateTime|null $checkout): array
    {
        if (empty($checkin) && empty($checkout))
            return [null, null];
        $checkin = empty($checkin) ? $checkin : $this->handelDate($checkin);
        $checkout = empty($checkout) ? $checkout : $this->handelDate($checkout);
        return [$checkin, $checkout];
    }

    /**
     * format date to ISO 8601 from DateTime our string date.
     * throw InvalidArgumentException if not a valid string date 
     * @param string|DateTime $date
     * @return string
     */
    protected function handelDate(string|DateTime $date): string
    {
        if (is_string($date)) {
            $parsedDate = strtotime($date);
            if ($parsedDate === false)
                throw new InvalidArgumentException("Invalid Date " . $date, 4);
            return date("Y-m-d\TH:i:s", $parsedDate);
        }
        return $date->format("Y-m-d\TH:i:s");
    }
}
