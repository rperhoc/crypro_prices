<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register()
    {
        return view('users.register');
    }

    public function toggleFavourite(Request $request) 
    {
        $user = Auth::user();    
        $currency = Currency::find($request->request->all()['currency_id']);
        $user->toggleFavouriteCurrency($currency);
        
        return redirect()->back();
    }

}

