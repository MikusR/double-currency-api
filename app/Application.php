<?php

namespace App;

class Application
{
    private ExchangeCollection $exchanges;


    public function __construct()
    {
    }

    public function run(): void
    {
        $this->exchanges = $this->buildExchanges();
        $this->ui();
    }

    public function buildExchanges(): ExchangeCollection
    {
        return new ExchangeCollection([$this->buildExchange()]);
    }

    public function buildExchange(): Exchange
    {
        $json = json_decode(
            file_get_contents("http://api.exchangeratesapi.io/v1/latest?access_key={$_ENV['API_KEY']}")
        );
        if (!$json->success) {
            die;
        }
        $currencies = [];
        foreach (IsoCodes::get() as $isoCode => $name) {
            if (property_exists($json->rates, $isoCode)) {
                $currencies[$isoCode] = new Currency($isoCode, $name, $json->rates->$isoCode);
            }
        }
        return new Exchange($json->timestamp, 'exchangeratesapi.io', $currencies);
    }

    public function ui(): void
    {
        while (true) {
            // echo "Date of rates: " . $this->exchanges->getTimestamp() . PHP_EOL;
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
                    // echo ($this->exchanges->exchange($currency, 100 * $amount, $currencyTo)) / 100
                    //  . " " . IsoCodes::getName($currencyTo) . "\n";
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