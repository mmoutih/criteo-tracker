<?php
namespace Mmoutih\CriteoTracker\TagsEvents;

class ViewListEvent extends Event
{

    protected array  $item;
    protected string|int $category;
    protected string $keywords;

    public function __construct()
    {
        $this->event = EventNames::VIEW_LIST_EVENT;
        $this->item = [];
    }

    /**
     * merge items ids to the items list
     * @param array $items
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->item = array_unique(
            array_merge(
                $this->item,
                $items
            )
        );
        return $this;
    }

    /**
     * Set the value of category id
     * @param string $category
     * @return  self
     */ 
    public function setCategory(string $category) : self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Set the value of search keywords
     * @param string $keywords
     * @return  self
     */ 
    public function setKeywords(string $keywords) : self
    {
        $this->keywords = $keywords;
        return $this;
    }
}
