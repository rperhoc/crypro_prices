<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;


class Data extends Model
{
    use HasFactory;

    private const API_BASE = 'https://api.coinbase.com/v2/';

    public function getRateEndpoint(string $crypto, string $fiat, string $price = 'spot'): string
    {
        return sprintf(self::API_BASE . 'prices/%s-%s/%s', $crypto, $fiat, $price);
    }

    public function getExchangeRate(string $crypto, string $fiat, string $price = 'spot')
    {
        $endpoint = $this->getRateEndpoint($crypto, $fiat);
        try {
            return $this->getApiData($endpoint)['amount'];
        } catch (\Exception $e) {
            Log::error('Failed to fetch exchange rate: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getCryptoData()
    {
        try {
            return self::getApiData(self::API_BASE . 'currencies/crypto');
        } catch (\Exception $e) {
            Log::error('Failed to fetch crypto data: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function getFiatData()
    {
        try {
            return self::getApiData(self::API_BASE . 'currencies');
        } catch (\Exception $e) {
            Log::error('Failed to fetch fiat data: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getApiData($endpoint)
    {
        $response = Http::get($endpoint);

        if ($response->successful()) {
            return $response->json()['data'];
        } else {
            throw new \Exception('API request failed: ' . $response->status());
        }
    }
}
