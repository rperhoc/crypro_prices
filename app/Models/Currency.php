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
            $fiat_data = Data::getFiatData();

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
    public static function allCurrencies() : array
    {
        $data = self::all();
        $currencies = array();
        // Store each currency in array with same attributes as in MySQL table
        foreach ($data as $currency) {
            array_push($currencies, $currency->attributes);
        }
        return $currencies;
    }

    public static function cryptoCurrencies()
    {
       return self::where('type', 'crypto')->get();
    }

    public static function fiatCurrencies() 
    {
        return self::where('type', 'fiat')->get();
    }

    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'users_currencies')->withTimestamps(); 
    }

    public function isFavouritedBy(int $user_id) : bool
    {
        return false;
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
