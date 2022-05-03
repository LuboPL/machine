<?php
namespace VendingMachine;
use VendingMachine\Action\ActionInterface;
use VendingMachine\Action\Action;
use VendingMachine\Item\ItemInterface;
use VendingMachine\Item\Item;
use VendingMachine\Item\ItemCollectionInterface;
use VendingMachine\Input\InputInterface;
use VendingMachine\Input\InputParserInterface;
use VendingMachine\Input\InputHandlerInterface;
use VendingMachine\Money\MoneyCollectionInterface;
use VendingMachine\Money\MoneyCollection;
use VendingMachine\Money\MoneyInterface;
use VendingMachine\Money\Money;
use VendingMachine\Response\ResponseInterface;
use VendingMachine\Response\Response;
require_once 'vendor/autoload.php';

// Implement me

/* class Money implements MoneyInterface
{
    private float $value;
    private string $symbol;

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
} */

/* class MoneyCollection implements MoneyCollectionInterface
{
    public $moneyCollection;
    private array $merge;

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
        $this->merge = new MoneyCollection;
        $this->merge = array_merge($this->moneyCollection, $moneyCollection->toArray());
    }

    public function empty(): void
    {   
        $this->moneyCollection = null;
    }

    public function toArray(): array
    {
        return $this->moneyCollection;
    }
} */

/* class Item implements ItemInterface
{
    private int $count;
    private float $price;
    private string $symbol;

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
} */

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

/* class Action implements ActionInterface
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
} */

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

class InputHandler implements InputHandlerInterface
{   
    private Input $input;

    public function __construct(Input $input)
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

/* class Response implements ResponseInterface
{
    private string $response;

    public function __construct(string $string)
    {
        $this->string = $string;
    }
    public function __toString(): string
    {
        return $this->string;
    }

    public function getReturnCode(): int
    {
        return 0;
    }
} */

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
    $actionName = $input->getAction()->getName();
    if (in_array($actionName, $insert = ['Q','D','N','DOLLAR'])) {
        foreach ($insertedMoney = $input->getMoneyCollection()->toArray() as $money) {
            $vendingMachine->insertMoney($money);
        }
    }
    if ($actionName == 'RETURN-MONEY' && $vendingMachine->getInsertedMoney()->moneyCollection !== null)  {
        $action->handle($vendingMachine)->__toString();
        $vendingMachine->getInsertedMoney()->empty();
    }
    elseif (in_array($actionName, $actions = ['A','B','C'] ) && $vendingMachine->getInsertedMoney()->moneyCollection !== null) {
        $vendingMachine->handleAction($input->getAction());
    }  
} while ($actionName);













