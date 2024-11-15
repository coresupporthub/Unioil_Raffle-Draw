<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerRegistration extends Controller
{
    public function register(Request $req){
        return response()->json(['data'=> 'test']);
    }
}
