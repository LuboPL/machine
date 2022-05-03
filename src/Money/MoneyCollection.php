<?php

namespace VendingMachine\Money;

class MoneyCollection implements MoneyCollectionInterface
{
    public $moneyCollection;

    public function add(MoneyInterface $money): void
    {
        $this->moneyCollection[] = $money;
    }

    public function sum(): float
    {
        $sum = [];
        foreach ($this->moneyCollection as $money)
        {
            $sum[] = $money->getValue();
        }
        
        return $sum = round(array_sum($sum), 2);
    }

    public function merge(MoneyCollectionInterface $moneyCollection): void
    {   

    }

    public function empty(): void
    {   
        $this->moneyCollection = null;
    }

    public function toArray(): array
    {
        return $this->moneyCollection;
    }
}
