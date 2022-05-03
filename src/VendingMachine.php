<?php

namespace VendingMachine;

use VendingMachine\Action\ActionInterface;
use VendingMachine\Action\Action;
use VendingMachine\Item\ItemInterface;
use VendingMachine\Item\Item;
use VendingMachine\Item\ItemCollection;
use VendingMachine\Money\MoneyCollectionInterface;
use VendingMachine\Money\MoneyCollection;
use VendingMachine\Money\MoneyInterface;
use VendingMachine\Money\Money;
use VendingMachine\Response\ResponseInterface;
use VendingMachine\Response\Response;

class VendingMachine implements VendingMachineInterface
{
    private ItemCollection $itemCollection;
    private MoneyCollection $moneyCollection;

    public function __construct()
    {
        $this->itemCollection = new ItemCollection;
        $this->moneyCollection = new MoneyCollection;
    }
    public function addItem(ItemInterface $item): void    
    {   
        $this->itemCollection->add($item);
    }

    public function dropItem(ItemInterface $item): void
    {
        $this->itemCollection->get($item);
        $item->setCount(0);
    }

    public function insertMoney(MoneyInterface $money): void
    {
        $this->moneyCollection->add($money);
        echo 'Current ballance: ' . $this->moneyCollection->sum().' '; 
        foreach ($this->moneyCollection->toArray() as $money) {
            echo $money->getSymbol().', ';
        }
    }

    public function getInsertedMoney(): MoneyCollectionInterface
    {
        return $this->moneyCollection;
    }

    public function handleAction(ActionInterface $action): ResponseInterface
    {
        $actionName = $action->getName();
        if (in_array($actionName, $actions = ['A','B','C'])) {
            switch ($actionName) {
                case "A":
                    foreach ($this->itemCollection as $item) {
                        foreach ($item as $itemInMachine) {
                            if ($itemInMachine->getSymbol() == 'A') {
                                $item = $itemInMachine;
                            }
                        }
                    }
                if ($this->itemCollection->get($item)->getPrice() > $this->moneyCollection->sum()) {
                    $response = new Response("Not enough money.");
                    echo $response->__toString();
                } elseif ($this->itemCollection->get($item)->getCount() == 0) {
                    $response = new Response("Item not found. Please choose another item.");
                    echo $response->__toString();  
                } else {
                    $response = new Response ($this->itemCollection->get($item)->getSymbol());
                    echo $response->__toString();
                    $this->dropItem($item);
                    $this->moneyCollection->empty();
                }
                    break;

                case "B":
                    foreach ($this->itemCollection as $item) {
                        foreach ($item as $itemInMachine) {
                            if ($itemInMachine->getSymbol() == 'B') {
                                $item = $itemInMachine;
                            }
                        }
                    }
                    if ($this->itemCollection->get($item)->getPrice() > $this->moneyCollection->sum()) {
                        $response = new Response("Not enough money.");
                        echo $response->__toString();
                    } elseif ($this->itemCollection->get($item)->getCount() == 0) {
                        $response = new Response("Item not found. Please choose another item.");
                        echo $response->__toString();  
                    } else {
                        $response = new Response ($this->itemCollection->get($item)->getSymbol());
                        echo $response->__toString();
                        $this->dropItem($item);
                        $this->moneyCollection->empty();
                    }
                    break;

                case "C":
                    foreach ($this->itemCollection as $item) {
                        foreach ($item as $itemInMachine) {
                            if ($itemInMachine->getSymbol() == 'C') {
                                $item = $itemInMachine;
                            }
                        }
                    }
                    if ($this->itemCollection->get($item)->getPrice() > $this->moneyCollection->sum()) {
                        $response = new Response("Not enough money.");
                        echo $response->__toString();
                    } elseif ($this->itemCollection->get($item)->getCount() == 0) {
                        $response = new Response("Item not found. Please choose another item.");
                        echo $response->__toString();  
                    } else {
                        $response = new Response ($this->itemCollection->get($item)->getSymbol());
                        echo $response->__toString();
                        $this->dropItem($item);
                        $this->moneyCollection->empty();
                    }
                    break;
            }
        }

        return $response;
    }
}
