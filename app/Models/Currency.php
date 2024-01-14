<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';
    protected $fillable = ['code', 'name', 'type'];
    public $timestamps = false;

    public static function updateTable()
    {
        try {
            $crypto_data = Data::getCryptoData();
            #$crypto_data = $data->getCryptoData();
            $fiat_data = Data::getFiatData();
            #$fiat_data = $data->getFiatData();

            // Add all Crypto currencies to table
            foreach ($crypto_data as $currency) {
                // Check if currency is listed in the table
                $exists = self::where('code', $currency['code'])
                            ->where('type', $currency['type'])
                            ->exists();
                // Add currency if it is not listed
                if (!$exists) {
                    self::add($currency);
                }
            }
            // Add all Fiat currencies to table
            foreach ($fiat_data as $currency) {
                // Check if currency is listed in the table
                $exists = self::where('code', $currency['id'])
                            ->where('type', 'fiat')
                            ->exists();
                // Add currency if it is not listed
                if (!$exists) {
                    self::add($currency);
                }
            }
        } catch(\Exception $e) {
            Log::error('Failed to update currencies table: ' . $e->getMessage());
        }
    }

    /**
    * Get an array of all currencies.
    */
    public static function getAllCurrencies() : array
    {
        $data = self::all();
        $currencies = array();
        // Store each currency in array with same attributes as in MySQL table
        foreach ($data as $currency) {
            array_push($currencies, $currency->attributes);
        }
        return $currencies;
    }

    /**
    * Get an array of Crypto currencies.
    */
    public static function getCryptoCurrencies() : array
    {
        $data = self::where('type', 'crypto')->get();
        $currencies = array();
        // Store each currency in array with same attributes as in MySQL table
        foreach ($data as $currency) {
            array_push($currencies, $currency->attributes);
        }
        return $currencies;
    }

    /**
    * Get an array of Fiat currencies.
    */
    public static function getFiatCurrencies() : array
    {
        $data = self::where('type', 'fiat')->get();
        $currencies = array();
        // Store each currency in array with same attributes as in MySQL table
        foreach ($data as $currency) {
            array_push($currencies, $currency->attributes);
        }
        return $currencies;
    }

    private function add(array $currency) 
    {
        self::create([
            'code' => isset($currency['code']) ? $currency['code'] : $currency['id'],
            'name' => $currency['name'],
            'type' => isset($currency['type']) ? $currency['type'] : 'fiat'
        ]);
    }

}
