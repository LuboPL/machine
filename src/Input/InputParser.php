<?php

namespace VendingMachine\Input;

use VendingMachine\Exception\InvalidInputException;
use VendingMachine\Money\Money;
use VendingMachine\Money\MoneyCollection;
use VendingMachine\Action\Action;
use VendingMachine\Input\Input;

class InputParser implements InputParserInterface
{
    /**
     * @throws InvalidInputException
     */
    public function parse(string $input): InputInterface
    {
        $moneyCollection = new MoneyCollection;
        $action = new Action($input);
        switch ($input) {
            case "Q":
            $quarter = new Money(0.25, 'Q');
            $moneyCollection->add($quarter);
            break;
    
            case "N":
            $nickel = new Money(0.05, 'N');
            $moneyCollection->add($nickel);
            break;
    
            case "D":
            $dime = new Money(0.10, 'D');
            $moneyCollection->add($dime);
            break;
    
            case "DOLLAR":
            $dollar = new Money(1.00, 'DOLLAR');  
            $moneyCollection->add($dollar);
            break;
    
            case "RETURN-MONEY":
            $action = new Action($input);
            break; 

            case "GET-A":
            $action = new Action('A');
            break; 

            case "GET-B":
            $action = new Action('B');
            break; 

            case "GET-C":
            $action = new Action('C');
            break;            
        }

        return new Input($moneyCollection, $action); 
    }
}
