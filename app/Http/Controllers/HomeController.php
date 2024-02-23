<?php

namespace App\Http\Controllers;

use App\Services\CoinbaseWrapper;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {   
        if (auth()->check()) {
            $user = Auth::user();
            $cryptoCurrencies = $user->sortedCryptoCurrencies();
            $fiatCurrencies = $user->sortedFiatCurrencies();
        } else {
            $cryptoCurrencies = Currency::crypto()->get();
            $fiatCurrencies = Currency::fiat()->get();
        }  
        
        $selectedCrypto = $cryptoCurrencies->first();
        $selectedFiat = $fiatCurrencies->first();

        return view('index', [
            'crypto_currencies' => $cryptoCurrencies,
            'fiat_currencies' => $fiatCurrencies,
            'favourite_currencies' => isset($user) ? $user->favouriteCurrencies->pluck('id')->toArray() : null,
            'selected_crypto' => $selectedCrypto,
            'selected_fiat' => $selectedFiat,
            'is_crypto_favourite' => isset($user) ? $user->isCurrencyFavourite($selectedCrypto) : null,
            'is_fiat_favourite' => isset($user) ? $user->isCurrencyFavourite($selectedFiat) : null
        ]);
    }
    
    public function show(Request $request, CoinbaseWrapper $coinbase) 
    {
        $crypto = Currency::where('id', $request->input('crypto'))
                            ->first();
        $fiat = Currency::where('id', $request->input('fiat'))
                            ->first();

        if (auth()->check()) {
            $user = Auth::user();
            $cryptoCurrencies = $user->sortedCryptoCurrencies();
            $fiatCurrencies = $user->sortedFiatCurrencies();

        } else {
            $cryptoCurrencies = Currency::crypto()->get();
            $fiatCurrencies = Currency::fiat()->get();
        }    
        
        return view('index', [
            'crypto_currencies' => $cryptoCurrencies,
            'fiat_currencies' => $fiatCurrencies,
            'favourite_currencies' => isset($user) ? $user->favouriteCurrencies->pluck('id')->toArray() : null,
            'selected_crypto' => $crypto,
            'selected_fiat' => $fiat,
            'is_crypto_favourite' => isset($user) ? $user->isCurrencyFavourite($crypto) : null,
            'is_fiat_favourite' => isset($user) ? $user->isCurrencyFavourite($fiat) : null,
            'exchange_rate' => round($coinbase->exchangeRate($crypto->code, $fiat->code), 2)
        ]);
    }
}
