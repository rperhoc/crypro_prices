<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $parameters = $request->request->all();
        $user_id = Auth::id();
        $user = User::find($user_id);

        $user->toggleFavouriteCurrency($parameters['currency_id']);
        return redirect()->back();
    }

}

