<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Currency;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index()
    {    
        $crypto_currencies = Currency::getCryptoCurrencies();
        $fiat_currencies = Currency::getFiatCurrencies();
        
        return view('index', [
            'crypto_currencies' => $crypto_currencies,
            'fiat_currencies' => $fiat_currencies,
            'selected_crypto' => $crypto_currencies[0]['code'],
            'selected_fiat' => $fiat_currencies[0]['code']
        ]);
    }
    
    public function show(Request $request, Data $data) 
    {
        $crypto_currencies = Currency::getCryptoCurrencies();
        $fiat_currencies = Currency::getFiatCurrencies();
        $selected_crypto = $request->input('crypto');
        $selected_fiat = $request->input('fiat');
        
        return view('show_price', [
            'crypto_currencies' => $crypto_currencies,
            'fiat_currencies' => $fiat_currencies,
            'selected_crypto' => $selected_crypto,
            'selected_fiat' => $selected_fiat,
            'exchange_rate' => round($data->getExchangeRate($selected_crypto, $selected_fiat), 2)
        ]);
    }

}
