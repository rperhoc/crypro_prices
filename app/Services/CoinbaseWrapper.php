<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CoinbaseWrapper
{
    private const API_BASE = 'https://api.coinbase.com/v2/';

    public static function exchangeRate(string $crypto_code, string $fiat_code, string $price = 'spot')
    {
        $endpoint = sprintf(self::API_BASE . 'prices/%s-%s/%s', $crypto_code, $fiat_code, $price);
        try {
            return self::apiData($endpoint)['amount'];
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function cryptoData()
    {
        try {
            return self::apiData(self::API_BASE . 'currencies/crypto');
        } catch (\Exception $e) {
            Log::error('Failed to fetch crypto data: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function fiatData()
    {
        try {
            return self::apiData(self::API_BASE . 'currencies');
        } catch (\Exception $e) {
            Log::error('Failed to fetch fiat data: ' . $e->getMessage());
            throw $e;
        }
    }

    private static function apiData($endpoint)
    {
        $response = Http::get($endpoint);

        if ($response->successful()) {
            return $response->json()['data'];
        } else {
            throw new \Exception('API request failed: ' . $response->status());
        } 
    }

}