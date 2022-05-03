<?php
namespace VendingMachine;
use VendingMachine\Action\ActionInterface;
use VendingMachine\Action\Action;
use VendingMachine\Item\ItemInterface;
use VendingMachine\Item\Item;
use VendingMachine\Item\ItemCollectionInterface;
use VendingMachine\Item\ItemCollection;
use VendingMachine\Input\InputInterface;
use VendingMachine\Input\Input;
use VendingMachine\Input\InputParserInterface;
use VendingMachine\Input\InputParser;
use VendingMachine\Input\InputHandlerInterface;
use VendingMachine\Input\InputHandler;
use VendingMachine\Money\MoneyCollectionInterface;
use VendingMachine\Money\MoneyCollection;
use VendingMachine\Money\MoneyInterface;
use VendingMachine\Money\Money;
use VendingMachine\Response\ResponseInterface;
use VendingMachine\Response\Response;
require_once 'vendor/autoload.php';

$itemA = new Item('A');
$itemA->setCount(1);
$itemA->setPrice(0.65);

$itemB = new Item('B');
$itemB->setCount(1);
$itemB->setPrice(1.0);

$itemC = new Item('C');
$itemC->setCount(1);
$itemC->setPrice(1.5);

$vendingMachine = new VendingMachine;
$vendingMachine->addItem($itemA);
$vendingMachine->addItem($itemB);
$vendingMachine->addItem($itemC);

do {
    echo "\n";
    $readline = readline('Input: ');
    $inputParser = new InputParser;
    $input = $inputParser->parse($readline);
    $action = $input->getAction();
    $actionName = $action->getName();
    if (in_array($actionName, $inputValue = ['Q','D','N','DOLLAR'])) {
        foreach ($insertedMoney = $input->getMoneyCollection()->toArray() as $money) {
            $vendingMachine->insertMoney($money);
        }
    }
    if ($actionName == 'RETURN-MONEY' && $vendingMachine->getInsertedMoney()->moneyCollection !== null)  {
        $action->handle($vendingMachine);
        $vendingMachine->getInsertedMoney()->empty();
    }
    elseif (in_array($actionName, $actions = ['A','B','C'] ) && $vendingMachine->getInsertedMoney()->moneyCollection !== null) {
        $vendingMachine->handleAction($input->getAction());
    }  
} while ($actionName);











