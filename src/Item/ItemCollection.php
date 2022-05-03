<?php

namespace VendingMachine\Item;

use VendingMachine\Exception\ItemNotFoundException;

class ItemCollection implements ItemCollectionInterface
{
    public $itemCollection;
   
    public function add(ItemInterface $item): void
    {
        $this->itemCollection[] = $item;
    }
    /**
     * @throws ItemNotFoundException
     */
    public function get(ItemInterface $item): ItemInterface
    {
        return $item;
    }

    public function count(ItemInterface $item): int
    {
        return $item->getCount();
    }

    public function empty(): void
    {
        $this->itemCollection = null;
    }
}
