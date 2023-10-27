<?php

namespace App\Api;

use App\Currency;
use App\Exchange;
use App\IsoCodes;

class FrankfurterApp
{
    public static function get(): Exchange
    {
        $json = json_decode(
            file_get_contents(
                "https://api.frankfurter.app/latest"
            )
        );

        $currencies = [];
        foreach (IsoCodes::get() as $isoCode => $name) {
            if (property_exists($json->rates, $isoCode)) {
                $currencies[$isoCode] = new Currency($isoCode, $json->rates->$isoCode);
            }
        }
        $currencies['EUR'] = new Currency('EUR', 1);
        return new Exchange('frankfurter.app', $currencies);
    }
}