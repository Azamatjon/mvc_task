<?php

namespace Core\Components;

class Pagination
{
    private $items;
    private $total_items;
    private $current_page;
    private $items_per_page;

    public function __construct($items, $total_items, $current_page, $items_per_page)
    {
        $this->items = $items;
        $this->total_items = $total_items;
        $this->current_page = $current_page;
        $this->items_per_page = $items_per_page;
    }

    public function hasPrev()
    {
        if ($this->current_page > 1) {
            return true;
        }
        return false;
    }

    public function hasNext()
    {
        if ($this->current_page < $this->getNumberOfPages()) {
            return true;
        }
        return false;
    }

    public function getCurrentPage()
    {
        return $this->current_page;
    }

    public function getNumberOfPages()
    {
        return ceil($this->total_items / $this->items_per_page);
    }

    public function getItems()
    {
        return $this->items;
    }
}