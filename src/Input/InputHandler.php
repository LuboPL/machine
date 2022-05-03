<?php

namespace VendingMachine\Input;

use VendingMachine\Exception\InvalidInputException;
use VendingMachine\Exception\Input;

class InputHandler implements InputHandlerInterface
{   
    private Input $input;

    public function __construct(Input $input)
    {
        $this->input = $input;
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }
}
