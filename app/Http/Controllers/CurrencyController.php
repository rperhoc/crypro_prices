<?php

namespace App\Http\Controllers;

use App\Models\Currency;

class CurrencyController extends Controller
{
    public function updateTable()
    {
        Currency::updateTable();
        return redirect('/');
    }
    
}
