<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class HomeController extends Controller
{
    public function index()
    {    
        if (auth()->check()) {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $crypto_currencies = $user->sortedCryptoCurrencies();
            $fiat_currencies = $user->sortedFiatCurrencies();
        } else {
            $crypto_currencies = Currency::cryptoCurrencies();
            $fiat_currencies = Currency::fiatCurrencies();
        }  

        $selected_crypto = $crypto_currencies->first();
        $selected_fiat = $fiat_currencies->first();
      
        return view('index', [
            'crypto_currencies' => $crypto_currencies,
            'fiat_currencies' => $fiat_currencies,
            'favourite_currencies' => $user->favouriteCurrencies->pluck('id')->toArray(),
            'selected_crypto' => $selected_crypto,
            'selected_fiat' => $selected_fiat,
            'is_crypto_favourite' => isset($user) ? $user->isCurrencyFavourite($selected_crypto) : null,
            'is_fiat_favourite' => isset($user) ? $user->isCurrencyFavourite($selected_fiat) : null
        ]);
    }
    
    public function show(Request $request) 
    {
        $crypto = Currency::where('id', $request->input('crypto'))
                            ->first();
        $fiat = Currency::where('id', $request->input('fiat'))
                            ->first();

        if (auth()->check()) {
            $user_id = Auth::id();
            $user = User::find($user_id);
            $crypto_currencies = $user->sortedCryptoCurrencies();
            $fiat_currencies = $user->sortedFiatCurrencies();

        } else {
            $crypto_currencies = Currency::cryptoCurrencies();
            $fiat_currencies = Currency::fiatCurrencies();
        }    
        
        return view('index', [
            'crypto_currencies' => $crypto_currencies,
            'fiat_currencies' => $fiat_currencies,
            'favourite_currencies' => $user->favouriteCurrencies->pluck('id')->toArray(),
            'selected_crypto' => $crypto,
            'selected_fiat' => $fiat,
            'is_crypto_favourite' => isset($user) ? $user->isCurrencyFavourite($crypto) : null,
            'is_fiat_favourite' => isset($user) ? $user->isCurrencyFavourite($fiat) : null,
            'exchange_rate' => round(Data::getExchangeRate($crypto->code, $fiat->code), 2)
        ]);
    }

}
