<?php
namespace VendingMachine;
use VendingMachine\Action\ActionInterface;
use VendingMachine\Item\ItemInterface;
use VendingMachine\Item\ItemCollectionInterface;
use VendingMachine\Input\InputInterface;
use VendingMachine\Input\InputParserInterface;
use VendingMachine\Input\InputHandlerInterface;
use VendingMachine\Money\MoneyCollectionInterface;
use VendingMachine\Money\MoneyInterface;
use VendingMachine\Response\ResponseInterface;
require_once 'vendor/autoload.php';



// Implement me

class Money implements MoneyInterface
{
    private $value;
    private $symbol;

    public function __construct(float $value, string $symbol)
    {
        $this->value = $value;
        $this->symbol = $symbol;
    }

    public function getValue(): float
    {
        return $this->value;    
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}

class MoneyCollection implements MoneyCollectionInterface
{
    public $moneyCollection;
    public $sum;
    public $merge;

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
        
        return $this->sum = round(array_sum($sum), 2);
    }

    public function merge(MoneyCollectionInterface $moneyCollection): void
    {   
        $string = "";
        foreach ($this->moneyCollection as $money)
        {
            $string .= $money->getSymbol(). ", ";
            $cutString = substr($string, 0, -2);
            $this->merge = $cutString;
        }
    }

    public function empty(): void
    {
        $this->moneyCollection = null;
        $this->sum = null;
        $this->merge = null;
    }

    public function toArray(): array
    {
        return $this->moneyCollection;
    }
}

class Item implements ItemInterface
{
    private $count;
    private $price;
    private $symbol;

    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}

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
        if ($item->getCount() == 0) {
            throw exception;
        }
        return $item;
    }

    public function count(ItemInterface $item): int
    {
        return $item->getCount();
    }

    public function empty(): void
    {

    }
}

class Action implements ActionInterface
{
    public $name;
    public $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function handle(VendingMachineInterface $vendingMachine): ResponseInterface
    {
        $this->response->sum = $vendingMachine;
        $this->response->merge = $vendingMachine;
        return $this->response;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

class Input implements InputInterface
{
    public $moneyCollection;
    public $action;
    public $input;

    public function __construct($moneyCollection, $action)
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
        $name = $this->input;
        $this->action->name = $name;
        return $this->action;
    }
}

class InputHandler implements InputHandlerInterface
{   
    public $input;

    public function __construct($input)
    {
        $this->input = $input;
    }

      /**
     * @throws InvalidInputException
     */
    public function getInput(): InputInterface
    {

        return $this->input;
    }

}

class InputParser implements InputParserInterface
{
    public $input;
    public function __construct($input)
    {
        $this->input = $input;
    }
    /**
     * @throws InvalidInputException
     */
    public function parse(string $input): InputInterface
    {
        $inputCommands = ['N','D','Q','DOLLAR','GET-A','GET-B','GET-C','RETURN-MONEY'];

        if (in_array($input, $inputCommands)) {
            $name = $input;
        }
        $this->input->input = $name;
        return $this->input; 
    }
}


class Response implements ResponseInterface
{
    public $sum;
    public $merge;


    public function __toString(): string
    {
        return $this->sum . $this->merge;
    }

    public function getReturnCode(): int
    {
        return 1;
    }
}

class VendingMachine implements VendingMachineInterface
{
    public $moneyCollection;
    public $response;
    public $itemCollection;

    public function __construct($moneyCollection, $response, $itemCollection)
    {
        $this->moneyCollection = $moneyCollection;
        $this->response = $response;
        $this->itemCollection = $itemCollection;
    }

    public function addItem(ItemInterface $item): void    
    {
        $this->itemCollection->add($item);
    }

    public function dropItem(ItemInterface $item): void
    {
 
    }

    public function insertMoney(MoneyInterface $money): void
    {
        $this->moneyCollection->add($money);
    }

    public function getInsertedMoney(): MoneyCollectionInterface
    {
        $this->moneyCollection;
    }

    public function handleAction(ActionInterface $action): ResponseInterface
    {
        $action->response;
        return $this->response;
    }
}

$nickel = new Money(0.05, 'N');
$dime = new Money(0.10, 'D');
$quarter = new Money(0.25, 'Q');
$dollar = new Money(1.00, 'DOLLAR');

$itemA = new Item('A');
$itemA->setCount(1);
$itemA->setPrice(0.65);

$itemB = new Item('B');
$itemB->setCount(1);
$itemB->setPrice(1.0);

$itemC = new Item('C');
$itemC->setCount(1);
$itemC->setPrice(1.5);

$itemCollection = new ItemCollection;
$moneyCollection = new MoneyCollection;


$response = new Response;
$vendingMachine = new VendingMachine($moneyCollection, $response, $itemCollection);
$action = new Action($response, $vendingMachine);
$input = new Input($moneyCollection, $action);
$inputHandler = new InputHandler($input);
$inputParser = new InputParser($input);

$readline = readline('Input: ');
$inputParser->parse($readline);

if ($input->getAction()->getName() == 'DOLLAR') {
    $vendingMachine->insertMoney($dollar);
    $input->getMoneyCollection()->sum();
    $input->getMoneyCollection()->merge($moneyCollection);
    $action->handle($vendingMachine);
    $vendingMachine->handleAction($action);
}




var_dump($vendingMachine);













/* $moneyCollection->add($nickel);
$moneyCollection->add($dime);
$moneyCollection->add($quarter);
$moneyCollection->add($dollar);

$vendingMachine->addItem($itemA);
$vendingMachine->addItem($itemB);
$vendingMachine->addItem($itemC);

$vendingMachine->insertMoney($dollar);
$vendingMachine->insertMoney($nickel);
$vendingMachine->insertMoney($dime);
$vendingMachine->insertMoney($dollar);
$vendingMachine->insertMoney($quarter);
$vendingMachine->getInsertedMoney();
$vendingMachine->handleAction($action);

$name = $action->getName();

$input = $inputParser->parse($input->getAction()->getName());
$vendingMachine->insertMoney($dime);
$vendingMachine->insertMoney($dime);
$vendingMachine->insertMoney($dime); */




