<?php

namespace App;

class ExchangeCollection
{
    private array $exchanges;

    public function __construct(array $exchanges)
    {
        $this->exchanges = $exchanges;
    }


    public function list(): array
    {
        return $this->exchanges;
    }

}

