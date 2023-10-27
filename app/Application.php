<?php

namespace App;

class Application
{
    private ExchangeCollection $exchanges;
    private array $apis;


    public function __construct()
    {
        $this->apis = [
            'exchangeratesapi.io',
            'frankfurter.app',
            'fawazahmed0'
        ];
    }

    public function run(): void
    {
        $this->exchanges = $this->buildExchanges();
        $this->ui();
    }

    public function buildExchanges(): ExchangeCollection
    {
        $exchanges = [];
        foreach ($this->apis as $api) {
            $exchanges[$api] = $this->buildExchange($api);
        }
        return new ExchangeCollection($exchanges);
    }

    public function buildExchange(string $name): ?Exchange
    {
        switch ($name) {
            case 'exchangeratesapi.io':
                return Api\ExchangeratesapiIo::get();
            case 'frankfurter.app':
                return Api\FrankfurterApp::get();
            case 'fawazahmed0':
                return Api\Fawazahmed::get();
            default:
                return null;
        }
    }

    public function ui(): void
    {
        while (true) {
            echo "1. to do currency conversion\n";
            echo "any other key to exit ";
            $choice = (int)readline();

            switch ($choice) {
                case 1:
                    echo "enter amount and currency (ex: 100 USD) ";
                    $userInput = explode(' ', readline());
                    $amount = (int)$userInput[0];
                    if (($amount <= 0) || (is_nan($amount))) {
                        echo "enter positive number\n";
                        break;
                    };
                    $currency = strtoupper($userInput[1]);
                    if (array_key_exists($currency, IsoCodes::get()) === false) {
                        echo "no such currency\n";
                        break;
                    }
                    $currencyTo = strtoupper(readline("Currency to "));
                    if (array_key_exists($currencyTo, IsoCodes::get()) === false) {
                        echo "no such currency\n";
                        break;
                    }
                    echo "You want to convert $amount " . IsoCodes::getName($currency);
                    echo " into " . IsoCodes::getName($currencyTo) . "\nYou get:\n";

                    /**
                     * @var Exchange $exchange
                     */
                    foreach ($this->exchanges->list() as $exchange) {
                        echo "Exchange: " . $exchange->getName() .
                            " you would get " .
                            $exchange->exchange($currency, 100 * $amount, $currencyTo) / 100 .
                            " " . IsoCodes::getName($currencyTo) . "\n";
                    }
                    break;
                default:
                    die;
            }
        }
    }

}