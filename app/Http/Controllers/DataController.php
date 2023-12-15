<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DataController extends Controller
{
    private const API_BASE = 'https://api.coinbase.com/v2/';

    public function index()
    {
        // Store currency API data into array
        //dd($this->getCryptoData());
        return view('index', [
            'crypto_data' => $this->getCryptoData(),
            'fiat_data' => $this->getFiatData()
        ]);
    }
    
    public function getRateEndpoint(string $crypto, string $fiat, string $price = 'spot'): string
    {
        return sprintf(self::API_BASE . 'prices/%s-%s/%s', $crypto, $fiat, $price);
    }

    public function getExchangeRate(string $crypto, string $fiat, string $price = 'spot')
    {
        $endpoint = $this->getRateEndpoint($crypto, $fiat);
        return $this->getApiData($endpoint);
    }

    public function show($crypto, $fiat) 
    {
        $endpoint = $this->getRateEndpoint($crypto, $fiat);

        return view('show_price', [
            'crypto' => $crypto,
            'fiat' => $fiat,
            'exchange_rate' => $this->getExchangeRate($crypto, $fiat)
        ]);
    }

    public function getCryptoData()
    {
        // Dodaj pogoj, da se najprej preveri database
        return $this->getApiData(self::API_BASE . 'currencies/crypto');
    }

    public function getFiatData()
    {
        // Dodaj pogoj, da se najprej preveri database
        return $this->getApiData(self::API_BASE . 'currencies');
    }

    private function getApiData($endpoint)
    {
        return Http::get($endpoint)->json()['data'];
    }
}
