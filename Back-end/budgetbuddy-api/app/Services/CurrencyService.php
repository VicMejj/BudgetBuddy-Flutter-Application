<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $baseUrl = 'https://api.exchangerate.host';

    /**
     * Convert amount from one currency to another
     */
    public function convert($amount, $from = 'TND', $to = 'TND')
    {
        if ($from === $to) return round($amount, 2);

        // Fixed exchange rates (always works)
        $rates = [
            'USD' => ['TND' => 3.11, 'EUR' => 0.93, 'GBP' => 0.79, 'CAD' => 1.36, 'MAD' => 10.06, 'JPY' => 150.32, 'AUD' => 1.52],
            'EUR' => ['USD' => 1.07, 'TND' => 3.34, 'GBP' => 0.85, 'CAD' => 1.46, 'MAD' => 10.82, 'JPY' => 161.42, 'AUD' => 1.63],
            'GBP' => ['USD' => 1.27, 'EUR' => 1.18, 'TND' => 3.94, 'CAD' => 1.72, 'MAD' => 12.74, 'JPY' => 190.41, 'AUD' => 1.92],
            'TND' => ['USD' => 0.32, 'EUR' => 0.30, 'GBP' => 0.25, 'CAD' => 0.44, 'MAD' => 3.23, 'JPY' => 48.33, 'AUD' => 0.49],
            'CAD' => ['USD' => 0.74, 'EUR' => 0.68, 'GBP' => 0.58, 'TND' => 2.30, 'MAD' => 7.40, 'JPY' => 110.74, 'AUD' => 1.12],
            'MAD' => ['USD' => 0.10, 'EUR' => 0.092, 'GBP' => 0.078, 'TND' => 0.31, 'CAD' => 0.14, 'JPY' => 14.95, 'AUD' => 0.15],
            'JPY' => ['USD' => 0.0067, 'EUR' => 0.0062, 'GBP' => 0.0053, 'TND' => 0.021, 'CAD' => 0.0090, 'MAD' => 0.067, 'AUD' => 0.010],
            'AUD' => ['USD' => 0.66, 'EUR' => 0.61, 'GBP' => 0.52, 'TND' => 2.05, 'CAD' => 0.89, 'MAD' => 6.62, 'JPY' => 99.11],
        ];

        // If we have a direct rate, use it
        if (isset($rates[$from][$to])) {
            $converted = $amount * $rates[$from][$to];
            Log::info("Fixed rate conversion: $amount $from = $converted $to");
            return round($converted, 2);
        }

        // Convert via USD as base currency
        if (isset($rates[$from]['USD']) && isset($rates['USD'][$to])) {
            $amountInUSD = $amount * $rates[$from]['USD'];
            $converted = $amountInUSD * $rates['USD'][$to];
            Log::info("USD-based conversion: $amount $from = $converted $to");
            return round($converted, 2);
        }

        Log::warning("No conversion rate found for $from to $to");
        return null;
    }

    /**
     * Get list of available currencies - ALWAYS WORKS
     */
    public function currencies()
    {
        // Always return a fixed list of currencies (no API calls)
        $currencies = [
            'USD' => 'US Dollar',
            'EUR' => 'Euro', 
            'GBP' => 'British Pound',
            'TND' => 'Tunisian Dinar',
            'CAD' => 'Canadian Dollar',
            'MAD' => 'Moroccan Dirham',
            'JPY' => 'Japanese Yen',
            'AUD' => 'Australian Dollar',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan'
        ];

        Log::info('Returning fixed currency list', ['count' => count($currencies)]);
        return $currencies;
    }
}