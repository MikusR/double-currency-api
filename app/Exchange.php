<?php

namespace App;


class Exchange
{
    private string $timestamp;
    private string $name;

    private CurrencyCollection $currencies;


    public function __construct(string $name, array $currencies)
    {
        $this->name = $name;
        $this->currencies = new CurrencyCollection($currencies);
    }

    public function exchange(string $from, int $amount, string $to): int
    {
        $amountInBase = $amount / $this->getRate($from);
        return $this->getRate($to) * $amountInBase;
    }

    /**
     * @return string
     */
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getRate(string $isoCode): float
    {
        return $this->currencies->get($isoCode)->getRate();
    }


}