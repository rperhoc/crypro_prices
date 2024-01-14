<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function updateTable()
    {
        Currency::updateTable();
        return redirect('/');
    }
    
}
