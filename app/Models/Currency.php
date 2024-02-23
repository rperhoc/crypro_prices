<?php

namespace App\Models;

use App\Services\CoinbaseWrapper;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';
    protected $fillable = ['code', 'name', 'type'];
    public $timestamps = false;

    public static function updateTable()
    {
        $coinbaseWrapper = app()->make(CoinbaseWrapper::class);
        try {
            $cryptoData = $coinbaseWrapper->cryptoData();
            $fiatData = $coinbaseWrapper->fiatData();

            foreach ($cryptoData as $currency) {
                self::firstOrCreate([
                    'code' => $currency['code'],
                    'name' => $currency['name'],
                    'type' => 'crypto'
                ]);
            }

            foreach ($fiatData as $currency) {
                self::firstOrCreate([
                    'code' => $currency['id'],
                    'name' => $currency['name'],
                    'type' => 'fiat'
                ]);
            }

        } catch(\Exception $e) {
            Log::error('Failed to update currencies table: ' . $e->getMessage());
        }
    }

    public function scopeCrypto(Builder $query) : void
    {
        $query->where('type', 'crypto');
    }

    public function scopeFiat(Builder $query) : void
    {
        $query->where('type', 'fiat');
    }

    public function favouritedByUsers()
    {
        return $this->belongsToMany(User::class, 'users_currencies'); 
    }

    public function isFavouritedBy(User $user) : bool
    {
        $currencyUsers = $this->favouritedByUsers()->get();
        foreach ($currencyUsers as $currencyUser) {
            if ($user->id == $currencyUser->id) {
                return true;
            }
        }
        return false;
    }

}
