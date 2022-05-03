<?php

namespace VendingMachine\Input;

use VendingMachine\Action\ActionInterface;
use VendingMachine\Action\Action;
use VendingMachine\Money\MoneyCollectionInterface;
use VendingMachine\Money\MoneyCollection;

class Input implements InputInterface
{
    private MoneyCollection $moneyCollection;
    private Action $action;

    public function __construct(MoneyCollection $moneyCollection, Action $action)
    {
        $this->moneyCollection = $moneyCollection;
        $this->action = $action;
    }

    public function getMoneyCollection(): MoneyCollectionInterface
    {
        return $this->moneyCollection;
    }

    public function getAction(): ActionInterface
    {   
        return $this->action;
    }
}

