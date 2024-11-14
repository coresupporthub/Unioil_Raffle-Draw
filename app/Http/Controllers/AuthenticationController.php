<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthenticationController extends Controller
{
    public function signin(Request $req){
        $data = [
            'email'=> $req->email,
            'password' => $req->password
        ];

        if(Auth::attempt($data)){
            $req->session()->regenerate();
            return response()->json(['success'=> true, 'Authentication is successful']);
        }else{
            return response()->json(['success'=> false, "Email and password does not match"]);
        }
    }
}
