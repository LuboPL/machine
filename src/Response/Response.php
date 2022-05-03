<?php

namespace VendingMachine\Response;

class Response implements ResponseInterface
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
}
