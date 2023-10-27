<?php

namespace App\Api;

use App\Currency;
use App\Exchange;
use App\IsoCodes;

class Fawazahmed
{
    public static function get(): Exchange
    {
        $json = json_decode(
            strtoupper(
                file_get_contents(
                    "https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/eur.json"
                )
            )
        );

        $currencies = [];
        foreach (IsoCodes::get() as $isoCode => $name) {
            if (property_exists($json->EUR, $isoCode)) {
                $currencies[$isoCode] = new Currency($isoCode, $json->EUR->$isoCode);
            }
        }

        return new Exchange('fawazahmed0', $currencies);
    }
}