<?php

namespace App;

use Carbon\Carbon;

class Exchange
{
    private string $timestamp;
    private string $name;

    private Currencies $currencies;


    public function __construct(string $timestamp, string $name, array $currencies)
    {
        $this->timestamp = $timestamp;
        $this->name = $name;
        $this->currencies = new Currencies($currencies);
    }

    public function exchange(string $from, int $amount, string $to): int
    {
        $amountInBase = $amount / $this->getRate($from);
        return $this->getRate($to) * $amountInBase;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return Carbon::createFromTimestamp($this->timestamp)->toDateTimeString();
    }

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