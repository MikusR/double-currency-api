<?php

namespace App;

class ExchangeCollection
{
    private array $exchanges;

    public function __construct(array $exchanges = [])
    {
        $this->exchanges = $exchanges;
    }


    public function list(): array
    {
        return $this->exchanges;
    }

    public function add(string $name, Exchange $exchange): void
    {
        $this->exchanges[$name] = $exchange;
    }
}

