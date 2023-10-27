<?php

namespace App\Api;

use App\Currency;
use App\Exchange;
use App\IsoCodes;

class ExchangeratesapiIo
{
    public static function get(): Exchange
    {
        $json = json_decode(
            file_get_contents(
                "http://api.exchangeratesapi.io/v1/latest?access_key={$_ENV['API_KEY_EXCHANGE_RATES_API_IO']}"
            )
        );
        if (!$json->success) {
            die;
        }
        $currencies = [];
        foreach (IsoCodes::get() as $isoCode => $name) {
            if (property_exists($json->rates, $isoCode)) {
                $currencies[$isoCode] = new Currency($isoCode, $json->rates->$isoCode);
            }
        }

        return new Exchange('exchangeratesapi.io', $currencies);
    }
}