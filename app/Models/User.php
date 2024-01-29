<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Currency;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function toggleFavouriteCurrency(int $currency_id) 
    {
        $this->favouriteCurrencies()->toggle($currency_id);
    }

    public function favouriteCurrencies() 
    {
        return $this->belongsToMany(Currency::class, 'users_currencies', 'user_id', 'currency_id')->withTimestamps();
    }

    public function sortedCryptoCurrencies() 
    {
        return Currency::cryptoCurrencies()->sortByDesc(function ($currency) 
        {
            return $this->favouriteCurrencies->contains($currency->id);
        });   
    }

    public function sortedFiatCurrencies() 
    {
        return Currency::fiatCurrencies()->sortByDesc(function ($currency) 
        {
            return $this->favouriteCurrencies->contains($currency->id);
        });                        
    }

    public function isCurrencyFavourite(Currency $currency) : bool 
    {
        return $this->favouriteCurrencies()
                    ->wherePivot('currency_id', $currency->id)
                    ->exists();
    }


}
