<?php

namespace VendingMachine\Action;

use VendingMachine\Response\ResponseInterface;
use VendingMachine\VendingMachineInterface;
use VendingMachine\Response\Response;

class Action implements ActionInterface
{
    private string $name;

    public function __construct(string $name)
    {
       $this->name = $name; 
    }
    
    public function handle(VendingMachineInterface $vendingMachine): ResponseInterface
    {   
        foreach ($vendingMachine->getInsertedMoney()->toArray() as $money) {
            $response = new Response($money->getSymbol());
            echo $response->__toString().', ';
        }
        return $response;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
