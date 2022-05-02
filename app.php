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
    private $moneyCollection;
    private float $sum;
    private $merge;

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

        $this->moneyCollection = array_merge($this->moneyCollection, $moneyCollection);
/*         $string = "";
        foreach ($this->moneyCollection as $money)
        {
            $string .= $money->getSymbol(). ", ";
            $cutString = substr($string, 0, -2);
            $this->merge = $cutString;
        } */
    }

    public function empty(): void
    {   
        $this->moneyCollection = new MoneyCollection;
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
    private $itemCollection;
   
    public function add(ItemInterface $item): void
    {
        $this->itemCollection[] = $item;
    }
    /**
     * @throws ItemNotFoundException
     */

    public function get(ItemInterface $item): ItemInterface
    {
        
        foreach ($this->itemCollection as $product) {
            if ($product->getCount() == 0 ) {
                "Item not found";
            }
            if ($item->getSymbol() == $product->getSymbol()) {
                $product;
            }
        }
        return $product;
    }

    public function count(ItemInterface $item): int
    {
        return $item->getCount();
    }

    public function empty(): void
    {
        $this->itemCollection = new ItemCollection;
    }
}

class Action implements ActionInterface
{
    private ?string $name;

    public function __construct(?string $name)
    {
       $this->name = $name; 
    }
    
    public function handle(VendingMachineInterface $vendingMachine): ResponseInterface
    {
        if ($this->name == 'RETURN-MONEY') {
            $response = $vendingMachine->getInsertedMoney()->merge($vendingMachine->getInsertedMoney());
        }
        return new Response($response);
    }

    public function getName(): string
    {
        return $this->name;
    }
}

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
        $inputActions = ['GET-A','GET-B','GET-C','RETURN-MONEY'];  
        if (in_array($input, $inputActions)) {
            switch ($input) {
                case "GET-A":
                $name = 'A';
                break;
                case "GET-B":
                $name = 'B';
                break;
                case "GET-C":
                $name = 'C';
                break;
                case "RETURN-MONEY":
                $name = 'RETURN';
                break;
                default:
                $name = null;
            }
        } 
        $action = new Action($name);
        $inputMap = [
            'N' => $nickel = new Money(0.05, 'N'),
            'D' => $dime = new Money(0.10, 'D'),
            'Q' => $quarter = new Money(0.25, 'Q'),
            'DOLLAR' => $dollar = new Money(1.00, 'DOLLAR')
        ];
        $moneyCollection = new MoneyCollection;
        foreach ($inputMap as $key => $money) {
            if ($input == $key) {
                $moneyCollection->add($money);
            }
        }
        return new Input($moneyCollection, $action); 
    }
}

class Response implements ResponseInterface
{
    private $action;

    public function __construct($action)
    {
        $this->action = $action;
    }
    public function __toString(): string
    {
        return $this->action;
    }

    public function getReturnCode(): int
    {
        return 0;
    }
}

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
        if ($this->itemCollection->count($item) > 0) {
            $item->setCount(0);
        }
    }

    public function insertMoney(MoneyInterface $money): void
    {
        $this->moneyCollection->add($money);
        echo 'Current ballance: ' . $this->moneyCollection->sum(); 
        foreach ($this->moneyCollection->toArray() as $money) {
            echo $money->getSymbol();
        }
    }

    public function getInsertedMoney(): MoneyCollectionInterface
    {
       
        return $this->moneyCollection;
    }

    public function handleAction(ActionInterface $action): ResponseInterface
    {

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
$vendingMachine->dropItem($itemC);
/* var_dump($vendingMachine); */
var_dump($vendingMachine->getInsertedMoney());



$readline = readline('Input: ');
$inputParser = new InputParser;
var_dump($inputHandler = new InputHandler($inputParser->parse($readline)));
$input = $inputHandler->getInput();
foreach ($input->getMoneyCollection()->toArray() as $money) {
    $vendingMachine->insertMoney($money);
}

var_dump($vendingMachine->getInsertedMoney());














