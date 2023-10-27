<?php


namespace App;

class Currency
{
    private string $isoCode;
    private float $rate;

    public function __construct(string $isoCode, string $name, float $rate)
    {
        $this->isoCode = $isoCode;
        $this->name = $name;
        $this->rate = $rate;
    }

    public function getIsoCode(): string
    {
        return $this->isoCode;
    }

    public function getName(): string
    {
        return IsoCodes::getName($this->getIsoCode());
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}