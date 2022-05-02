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

        $this->merge = new MoneyCollection;
        $this->merge = array_merge($this->moneyCollection, $moneyCollection->toArray());
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
        if ($item->getCount() == 0) 
            "Item not found";
        return $item;
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
    private string $name;

    public function __construct(string $name)
    {
       $this->name = $name; 
    }
    
    public function handle(VendingMachineInterface $vendingMachine): ResponseInterface
    {
        $insertedMoney = $vendingMachine->getInsertedMoney();
        foreach ($insertedMoney->toArray() as $money) {
            $response = new Response($money->getSymbol());
        }
        
        return $response;
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
    private MoneyCollection $moneyCollection;
    public function __construct($moneyCollection)
    {
        $this->moneyCollection = $moneyCollection;
    }
    public function parse(string $input): InputInterface
    {
/*         $inputMap = [
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
        } */
        $action = new Action($input);
        switch ($input) {
            case "Q":
            $quarter = new Money(0.25, 'Q');
            $this->moneyCollection->add($quarter);
            echo 'Current ballance: ' . $this->moneyCollection->sum(); 
            foreach ($this->moneyCollection->toArray() as $money) {
                echo $money->getSymbol();
            }

            break;
    
            case "N":
            $nickel = new Money(0.05, 'N');
            $this->moneyCollection->add($nickel);
            echo 'Current ballance: ' . $this->moneyCollection->sum(); 
            foreach ($this->moneyCollection->toArray() as $money) {
                echo $money->getSymbol();
            }
            break;
    
            case "D":
            $dime = new Money(0.10, 'D');
            $this->moneyCollection->add($dime);
            echo 'Current ballance: ' . $this->moneyCollection->sum(); 
            foreach ($this->moneyCollection->toArray() as $money) {
                echo $money->getSymbol();
            }
            break;
    
            case "DOLLAR":
            $dollar = new Money(1.00, 'DOLLAR');  
            $this->moneyCollection->add($dollar);
            echo 'Current ballance: ' . $this->moneyCollection->sum(); 
            foreach ($this->moneyCollection->toArray() as $money) {
                echo $money->getSymbol();
            }
            break;
    
            case "RETURN-MONEY":
            $action = new Action($input);
            break; 
            case "GET-A":
            $action = new Action($input);
            break; 
            case "GET-B":
            $action = new Action($input);
            break; 
            case "GET-C":
            $action = new Action($input);
            break; 
                   
        }
        return new Input($this->moneyCollection, $action); 
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
       /*  echo 'Current ballance: ' . $this->moneyCollection->sum(); 
        foreach ($this->moneyCollection->toArray() as $money) {
            echo $money->getSymbol();
        } */
    }

    public function getInsertedMoney(): MoneyCollectionInterface
    {
        return $this->moneyCollection;
    }

    public function handleAction(ActionInterface $action): ResponseInterface
    {
/*         $actions = ['GET-A','GET-B','GET-C','RETURN-MONEY']; 
        $name = $action->getName();
        if (in_array($name, $actions)) {
            switch ($name) {
                case "GET-A":
                $item = new Item('A');
                $item->setCount(1);
                $item->setPrice(0.65);
                $response = new Response ($this->itemCollection->get($item)->getSymbol());
                break;
                case "GET-B":
                $item = new Item('B');
                $item->setCount(1);
                $item->setPrice(1.0);
                $response = new Response ($this->itemCollection->get($item)->getSymbol());
                break;
                case "GET-C":
                $item = new Item('C');
                $item->setCount(1);
                $item->setPrice(1.5); 
                $response = new Response ($this->itemCollection->get($item)->getSymbol());   
                break;
            }
        } */
        $name = $action->getName();
        if ($name = 'RETURN-MONEY') {
            $this->getInsertedMoney()->toArray();
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




$moneyCollection = new MoneyCollection;
do {
    $readline = readline('Input: ');
    $inputParser = new InputParser($moneyCollection);
    $input = $inputParser->parse($readline);
    $action = $input->getAction()->getName();

/*     switch ($action) {
        case "Q":
        $quarter = new Money(0.25, 'Q');
        $input->getMoneyCollection()->add($quarter);
        $vendingMachine->insertMoney($quarter);
        break;

        case "N":
        $nickel = new Money(0.05, 'N');
        $input->getMoneyCollection()->add($nickel);
        $vendingMachine->insertMoney($nickel);
        break;

        case "D":
        $dime = new Money(0.10, 'D');
        $input->getMoneyCollection()->add($dime);
        $vendingMachine->insertMoney($dime);
        break;

        case "DOLLAR":
        $dollar = new Money(1.00, 'DOLLAR');  
        $input->getMoneyCollection()->add($dollar);
        $vendingMachine->insertMoney($dollar);
        break;

        case "RETURN-MONEY":
        
        var_dump($input);
        var_dump($input->getAction()->handle($vendingMachine));
    } */
} while (in_array($action, $actionMap = ['Q','D','N','DOLLAR','GET-A','GET-B','GET-C','RETURN-MONEY']));

$vendingMachine = new VendingMachine;
$vendingMachine->addItem($itemA);
$vendingMachine->addItem($itemB);
$vendingMachine->addItem($itemC);


foreach ($insertedMoney = $input->getMoneyCollection()->toArray() as $money) {
    $vendingMachine->insertMoney($money);
}
$input->getMoneyCollection();



/* var_dump($vendingMachine->handleAction($action)->__toString());

foreach ($input->getMoneyCollection()->toArray() as $money) {
    $vendingMachine->insertMoney($money);
}

var_dump($action->handle($vendingMachine)); */













